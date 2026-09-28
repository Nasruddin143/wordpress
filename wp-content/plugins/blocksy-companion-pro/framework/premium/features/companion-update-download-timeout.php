<?php

namespace Blocksy;

if (! defined('ABSPATH')) {
	exit;
}

/**
 * WordPress downloads an update package with a hardcoded 300s timeout
 * (`download_url($package, 300)` in WP_Upgrader::download_package()) and offers
 * no filter to change it. When the Freemius delivery endpoint is slow or
 * unreachable, that 300s wait almost always outlives the host's own wall-clock
 * request limit (php-fpm `request_terminate_timeout` / the reverse-proxy read
 * timeout, commonly ~120s on shared hosting). The host then hard-kills the PHP
 * worker mid-update — and on the bulk-update path, which enables maintenance
 * mode up-front, that SIGKILL skips the `maintenance_mode(false)` teardown and
 * strands the `.maintenance` file, taking the site down for up to 10 minutes.
 *
 * Clamping the timeout to a value comfortably under any sane host limit makes
 * cURL return a clean `download_failed` WP_Error *inside* the request instead.
 * That error path unwinds normally (maintenance mode is torn down, the update
 * stays offered, nothing is left half-installed), so the update simply fails
 * fast and can be retried — by a re-click, or by the next auto-update cycle.
 *
 * The clamp is scoped as tightly as possible: it only applies to OUR package
 * (matched via `$hook_extra['plugin']`), and only to the one HTTP request that
 * fetches that exact URL. Every other download on the site is untouched.
 * Freemius itself never hooks `upgrader_pre_download` / `http_request_args`, so
 * there is no ordering conflict with the SDK.
 */
class CompanionUpdateDownloadTimeout {
	const DOWNLOAD_TIMEOUT = 60;

	private $package_url = null;

	public function __construct() {
		add_filter(
			'upgrader_pre_download',
			[$this, 'clamp_companion_download_timeout'],
			10,
			4
		);
	}

	public function clamp_companion_download_timeout(
		$reply,
		$package,
		$upgrader,
		$hook_extra = []
	) {
		if (! $this->is_companion_download($hook_extra)) {
			return $reply;
		}

		$this->package_url = $package;

		// `http_request_args` is the only filter that beats the explicit 300s:
		// it runs last in WP_Http::request(), after the caller's args are
		// merged, so it can override them. `http_request_timeout` only sets the
		// default and would lose to the explicit value core passes.
		add_filter('http_request_args', [$this, 'apply_timeout'], 10, 2);

		// Returning $reply unchanged (false) lets WP run its normal
		// download_url() — we only override the timeout, not the download.
		return $reply;
	}

	public function apply_timeout($args, $url) {
		if ($url !== $this->package_url) {
			return $args;
		}

		$args['timeout'] = self::DOWNLOAD_TIMEOUT;

		// The package is fetched with a single request, so stop touching any
		// later request in the same run (bulk updates download several items).
		remove_filter('http_request_args', [$this, 'apply_timeout'], 10);
		$this->package_url = null;

		return $args;
	}

	private function is_companion_download($hook_extra) {
		if (! is_array($hook_extra)) {
			return false;
		}

		if (! isset($hook_extra['plugin'])) {
			return false;
		}

		return $hook_extra['plugin'] === BLOCKSY_PLUGIN_BASE;
	}
}
