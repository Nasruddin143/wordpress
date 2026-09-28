<?php

if (!defined('ABSPATH')) die('No direct access allowed');

/**
 * All commands that are intended to be available for calling from any sort of control interface (e.g. wp-admin, UpdraftCentral) go in here. All public methods should either return the data to be returned, or a WP_Error with associated error code, message and error data.
 */
class WP_Optimize_Premium_Commands extends WP_Optimize_Commands {

	/**
	 * Saves DB Analysis setting values
	 *
	 * @param  array $settings An array of data that includes true or false for click option.
	 * @return array
	 */
	public function save_table_analysis_options($settings) {
		if (isset($settings['is_enabled']) && 'true' === $settings['is_enabled']) {
			$this->options->update_option(WPO_DB_Table_Analysis::ENABLED_SETTING_NAME, 1);
		} else {
			$this->options->update_option(WPO_DB_Table_Analysis::ENABLED_SETTING_NAME, 0);
		}
		
		return array('message' => __('DB Analysis option updated.', 'wp-optimize'));
	}

	/**
	 * Wipe table usage data
	 *
	 * @return array
	 */
	public function wipe_table_analysis_data() {
		WPO_DB_Table_Analysis::wipe_usage_data();

		return array('message' => __('All data was deleted', 'wp-optimize'));
	}

	/**
	 * Get the contents of the table analysis data
	 *
	 * @param  array $data An array of data that includes the selected plugin if any
	 * @return array
	 */
	public function get_table_analysis_data($data) {
		$dashboard = WP_Optimize_Premium()->get_db_table_analysis_dashboard();

		$is_enabled = $this->options->get_option(WPO_DB_Table_Analysis::ENABLED_SETTING_NAME);

		ob_start();
		$dashboard->print_report_ui($is_enabled, $data['selected-plugin']);
		$html = ob_get_clean();
		
		return array('html' => $html);
	}

	/**
	 * Purge fonts cache.
	 *
	 * @return array
	 */
	public function purge_fonts_cache(): array {
		$can_purge = WP_Optimize()->get_minify()->can_purge_cache();
		$is_cli = defined('WP_CLI') && WP_CLI;

		if (!$can_purge && !$is_cli) {
			return array(
				'error' => __('You do not have permission to purge the cache', 'wp-optimize'),
			);
		}

		$fonts_cache = WP_Optimize_Host_Google_Fonts::instance();
		$success = $fonts_cache->purge();

		$result = array(
			'success' => $success,
			'stats'   => $fonts_cache->get_cache_stats(),
		);

		if (!$success) {
			$result['error'] = __("Couldn't purge the cache", 'wp-optimize');
		}

		// To prevent 404 warnings of static assets, we need to purge page cache and minify cache if those are enabled.
		$page_cache = WP_Optimize()->get_page_cache();

		// If minify is enabled, purging minify cache will also purge page cache
		if (wp_optimize_minify_config()->is_enabled()) {
			$minify_commands = new WP_Optimize_Minify_Commands();
			$minify_commands->purge_minify_cache();
		} elseif ($page_cache->is_enabled()) {
			// Purge page cache only if minify is disabled
			$page_cache_purged = $page_cache->purge();
			if ($page_cache_purged) {
				$page_cache->file_log('Full Cache Purge triggered by: font cache purge');
			}
		}

		return $result;
	}
}
