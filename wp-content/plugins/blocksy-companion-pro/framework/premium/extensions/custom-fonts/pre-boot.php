<?php

if (! defined('ABSPATH')) {
	exit;
}

class BlocksyExtensionCustomFontsPreBoot {
	public function __construct() {
		add_action('admin_enqueue_scripts', function () {
			if (! function_exists('get_plugin_data')) {
				require_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}

			$data = get_plugin_data(BLOCKSY__FILE__);

			if (! function_exists('blocksy_is_dashboard_page')) return;
			if (! blocksy_is_dashboard_page()) return;

			wp_enqueue_script(
				'blocksy-ext-custom-fonts-admin-dashboard-scripts',
				BLOCKSY_URL . 'framework/premium/extensions/custom-fonts/dashboard-static/bundle/main.js',
				['ct-options-scripts', 'ct-dashboard-scripts'],
				$data['Version'],
				false
			);
		});
	}

	public function ext_action($payload) {
		$storage = new \Blocksy\Extensions\CustomFonts\Storage();

		if (
			! isset($payload['type'])
			||
			! isset($payload['settings'])
			||
			$payload['type'] !== 'update-settings'
		) {
			return null;
		}

		if (isset($payload['settings']['urls'])) {
			$performance_storage = new \Blocksy\PerformanceTypography();

			$performance_storage->set_settings([
				'custom' => blocksy_companion_akg('urls', $payload['settings'], [])
			]);

			unset($payload['settings']['urls']);
		}

		$storage->set_settings($payload['settings']);

		/**
		 * Fires when the dynamic CSS caches should be invalidated.
		 *
		 * Listeners drop their generated CSS files/transients so the
		 * next request regenerates them.
		 *
		 * @since 1.6.2
		 * @since 1.8.0 Renamed from `blocksy:dynamic-css:regenere_css_files`.
		 */
		do_action('blocksy:dynamic-css:refresh-caches');

		return [
			'settings' => $payload['settings'],
			'font_stacks' => $storage->get_font_stacks()
		];
	}

	public function ext_data() {
		$storage = new \Blocksy\Extensions\CustomFonts\Storage();

		$settings = $storage->get_settings();

		$settings['fonts'] = $storage->get_normalized_fonts_list();

		return [
			'settings' => $settings,
			'font_stacks' => $storage->get_font_stacks()
		];
	}
}

