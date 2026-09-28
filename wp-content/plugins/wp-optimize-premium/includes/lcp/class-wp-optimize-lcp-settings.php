<?php

if (!defined('ABSPATH')) die('No direct access allowed');

if (!class_exists('WP_Optimize_LCP_Settings')) :

/**
 * Class WP_Optimize_LCP_Settings
 *
 * Handles LCP (Largest Contentful Paint) settings.
 */
class WP_Optimize_LCP_Settings {

	/**
	 * Returns singleton instance object
	 *
	 * @return WP_Optimize_LCP_Settings
	 */
	public static function instance() {
		static $_instance = null;
		if (null === $_instance) {
			$_instance = new self();
		}
		return $_instance;
	}

	/**
	 * Determine whether LCP preload functionality is enabled.
	 * Requires page cache and Auto LCP preload settings to be enabled.
	 * Skipped for logged-in users.
	 *
	 * @return bool True if LCP preload should be initialized.
	 */
	public function can_run_lcp_preload(): bool {
		return $this->is_page_cache_enabled()
			&& $this->is_auto_lcp_preload_enabled()
			&& !is_user_logged_in();
	}

	/**
	 * Check whether Auto LCP is enabled in WP Optimize Cache.
	 *
	 * @return bool True if Auto LCP preload is enabled, false otherwise.
	 */
	private function is_auto_lcp_preload_enabled(): bool {
		return (bool) WPO_Cache_Config::instance()->get_option('lcp_preload_enable');
	}

	/**
	 * Check whether page caching is enabled in WP Optimize.
	 * Required for LCP preload since cache files are modified directly.
	 *
	 * @return bool True if page caching is enabled, false otherwise.
	 */
	private function is_page_cache_enabled(): bool {
		return (bool) WPO_Cache_Config::instance()->get_option('enable_page_caching');
	}

	/**
	 * Disable Auto LCP when the page cache is disabled. Hooked to wpo_page_cache_disabled.
	 * Skips the cache purge — the cache is already cleared when this hook fires.
	 *
	 * @return void
	 */
	public function disable_on_cache_disabled(): void {
		if ($this->is_auto_lcp_preload_enabled()) {
			$this->save_lcp_preload_enable(false, false);
		}
	}

	/**
	 * Enable or disable the LCP preload feature.
	 * Also enforces that page caching must be on before LCP can be enabled.
	 *
	 * @param  bool $enabled True to enable LCP preload, false to disable.
	 * @return array<string, mixed>
	 */
	public function update_setting($enabled): array {
		// Cannot enable LCP preload without page cache
		if ($enabled && !$this->is_page_cache_enabled()) {
			if ($this->is_auto_lcp_preload_enabled()) {
				$this->save_lcp_preload_enable(false, false); // force disable; cache already off, no purge needed
			}
			return array(
				'success' => false,
				'message' => __('Page caching is not enabled.', 'wp-optimize') . ' ' . __('Enable page caching first to use Auto LCP.', 'wp-optimize'),
			);
		}

		return $this->save_lcp_preload_enable($enabled);
	}

	/**
	 * Persist the Auto LCP preload enabled state to cache config.
	 * Purges the page cache unless the caller indicates it is already empty.
	 *
	 * @param bool $enabled True to enable LCP preload, false to disable.
	 * @param bool $purge   Whether to purge the page cache after saving. Default true.
	 * @return array<string, mixed>
	 */
	private function save_lcp_preload_enable($enabled, $purge = true): array {
		$cache_config  = WPO_Cache_Config::instance();
		$cache_options = $cache_config->get();
		$cache_options['lcp_preload_enable'] = $enabled ? 1 : 0;

		$updated = $cache_config->update($cache_options);

		if (is_wp_error($updated)) {
			return array(
				'success' => false,
				'message'   => $updated->get_error_message(),
			);
		}

		if ($purge) {
			$cache = WPO_Page_Cache::instance();
			$cache->file_log('Auto LCP preload setting changed so purging the page cache.');
			$cache->purge();
		}

		$message = $enabled ? __('Auto LCP has been enabled', 'wp-optimize') : __('Auto LCP has been disabled', 'wp-optimize');

		return array(
			'success' => true,
			'message' => $message,
		);
	}
}
endif;
