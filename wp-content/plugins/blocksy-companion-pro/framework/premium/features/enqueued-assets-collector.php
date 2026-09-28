<?php

namespace Blocksy;

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Captures the styles and scripts enqueued during a unit of work.
 *
 * Snapshots the wp_styles()/wp_scripts() queues on construction, then on
 * collect() diffs them and returns only the handles enqueued in between —
 * resolved into a shippable shape (handle + src + inline data) ready to be
 * injected on the client.
 *
 * Used to deliver the assets of content that is rendered out-of-band, where the
 * normal page lifecycle won't enqueue/print them for us — e.g. AJAX popups, and
 * in the future mega menu loading, etc.
 *
 * Usage:
 *
 *   $collector = new EnqueuedAssetsCollector();
 *   // ... render something that enqueues styles/scripts ...
 *   $assets = $collector->collect(); // ['styles' => [...], 'scripts' => [...]]
 *
 * Reference consumer: ContentBlocksPopupsLogic::render_popup_with_assets()
 * in framework/premium/features/content-blocks/popups.php — it wraps the popup
 * render with a collector and ships the result as the AJAX response. Note this
 * captures only assets that go through wp_enqueue_style/script; handle-less
 * inline CSS (e.g. Blocksy's per-popup dynamic CSS) is collected separately and
 * attached by the consumer.
 */
class EnqueuedAssetsCollector {
	private $styles_before;
	private $scripts_before;
	private $include_queued_styles;
	private $include_queued_scripts;
	private static $frontend_context_installed = false;
	private static $original_current_screen;
	private static $had_current_screen = false;

	// Fake a frontend context during exactly the two windows that decide
	// asset flavor — the plugins_loaded-through-init hook span (block and
	// callback registration) and our own wp_ajax handler (render). Plugin
	// file load keeps its honest admin-ajax context, so load-time admin
	// includes still run, and admin_init is dropped for the request the same
	// way a real frontend request never has one.
	public static function declare_frontend_ajax_action($action) {
		if (! wp_doing_ajax()) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (! isset($_REQUEST['action'])) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_action = sanitize_text_field(wp_unslash($_REQUEST['action']));

		if ($current_action !== $action) {
			return;
		}

		// Render window: our own action hook, opened at the earliest priority
		// and never restored — the request ends at wp_die().
		add_action('wp_ajax_' . $action, function () {
			self::install_screen_stub();
		}, PHP_INT_MIN);

		add_action('wp_ajax_nopriv_' . $action, function () {
			self::install_screen_stub();
		}, PHP_INT_MIN);

		if (self::$frontend_context_installed) {
			return;
		}

		self::$frontend_context_installed = true;

		// Registration window: from the first plugins_loaded callback through
		// the last init callback — one consistent frontend-truth block for
		// the whole hook-registration span, so a plugin reading is_admin() at
		// plugins_loaded and again during init gets the same answer (Feeds
		// for YouTube registers its init:10 admin callback at
		// plugins_loaded:10 and only includes the file defining it at
		// init:4). Plugin file load stays admin-true (WooCommerce gates its
		// admin includes there). Hooking plugins_loaded at PHP_INT_MIN is
		// possible because this method runs at companion file load, before
		// the dispatch.
		add_action('plugins_loaded', function () {
			self::$had_current_screen = isset($GLOBALS['current_screen']);
			self::$original_current_screen = self::$had_current_screen
				? $GLOBALS['current_screen']
				: null;

			self::install_screen_stub();
		}, PHP_INT_MIN);

		add_action('init', function () {
			if (self::$had_current_screen) {
				$GLOBALS['current_screen'] = self::$original_current_screen;
				return;
			}

			unset($GLOBALS['current_screen']);
		}, PHP_INT_MAX);

		// admin-ajax.php fires admin_init unconditionally, but a plugin that
		// saw frontend truth inside the window built no admin components,
		// while the admin_init callbacks it registered outside the window
		// (plugin file load) still run and dereference them — WPCode Lite
		// hooks admin_init from a file it includes at load time and builds
		// wpcode()->library_auth at plugins_loaded:-1, which it skips
		// (#5499). Never make admin_init observe the window instead of
		// dropping it: its callbacks are registered under a context we don't
		// control. Our own render handlers are on wp_ajax_*, which fires
		// after this.
		add_action('admin_init', function () {
			remove_all_actions('admin_init');
		}, PHP_INT_MIN);
	}

	public function __construct($args = []) {
		$args = wp_parse_args($args, [
			'include_queued_styles' => [],
			'include_queued_scripts' => []
		]);

		$this->styles_before = wp_styles()->queue;
		$this->scripts_before = wp_scripts()->queue;
		$this->include_queued_styles = $args['include_queued_styles'];
		$this->include_queued_scripts = $args['include_queued_scripts'];
	}

	public function collect() {
		return [
			'styles' => $this->collect_styles(),
			'scripts' => $this->collect_scripts()
		];
	}

	private static function install_screen_stub() {
		$GLOBALS['current_screen'] = new class {
			public function in_admin($admin = null) {
				return false;
			}

			public function __get($key) {
				return null;
			}

			public function __call($name, $args) {
				return false;
			}
		};
	}

	private function collect_styles() {
		$styles = wp_styles();
		$assets = [];

		$new_handles = $this->resolve_handles_with_dependencies(
			array_unique(array_merge(
				array_diff($styles->queue, $this->styles_before),
				array_intersect($this->include_queued_styles, $styles->queue)
			)),
			$styles
		);

		foreach ($new_handles as $handle) {
			if (! isset($styles->registered[$handle])) {
				continue;
			}

			$obj = $styles->registered[$handle];
			$inline_style = $styles->get_data($handle, 'after');

			// A handle with neither a file nor inline CSS has nothing to ship.
			if (! $obj->src && ! $inline_style) {
				continue;
			}

			$entry = ['handle' => $handle];

			$src = $this->resolve_src($obj, $styles->base_url);

			if ($src) {
				$entry['src'] = $src;
			}

			if ($inline_style) {
				$entry['inline'] = implode("\n", $inline_style);
			}

			$assets[] = $entry;
		}

		return $assets;
	}

	private function collect_scripts() {
		$scripts = wp_scripts();
		$assets = [];

		$new_handles = $this->resolve_handles_with_dependencies(
			array_unique(array_merge(
				array_diff($scripts->queue, $this->scripts_before),
				array_intersect($this->include_queued_scripts, $scripts->queue)
			)),
			$scripts
		);

		foreach ($new_handles as $handle) {
			if (! isset($scripts->registered[$handle])) {
				continue;
			}

			$obj = $scripts->registered[$handle];

			if (! $obj->src) {
				continue;
			}

			$entry = [
				'handle' => $handle,
				'src' => $this->resolve_src($obj, $scripts->base_url)
			];

			$data = $scripts->get_data($handle, 'data');

			if ($data) {
				$entry['data'] = $data;
			}

			$assets[] = $entry;
		}

		return $assets;
	}

	// Resolve a registered dependency's src into an absolute, versioned URL,
	// matching how WP_Dependencies would print it.
	private function resolve_src($obj, $base_url) {
		if (! $obj->src) {
			return '';
		}

		$src = $obj->src;

		if (! preg_match('|^(https?:)?//|', $src)) {
			$src = $base_url . $src;
		}

		if ($obj->ver) {
			$src = add_query_arg('ver', $obj->ver, $src);
		}

		return $src;
	}

	private function resolve_handles_with_dependencies($handles, $dependencies) {
		$result = [];

		$append = function ($handle) use (&$append, &$result, $dependencies) {
			if (in_array($handle, $result, true)) {
				return;
			}

			if (! isset($dependencies->registered[$handle])) {
				return;
			}

			foreach ($dependencies->registered[$handle]->deps as $dependency) {
				$append($dependency);
			}

			$result[] = $handle;
		};

		foreach ($handles as $handle) {
			$append($handle);
		}

		return $result;
	}
}
