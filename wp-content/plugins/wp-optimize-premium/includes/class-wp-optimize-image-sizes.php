<?php

if (!defined('ABSPATH')) die('No direct access allowed');

if (!class_exists('WP_Optimize_Image_Sizes')) :

class WP_Optimize_Image_Sizes {

	const OPTION_KEY = 'wpo_registered_image_sizes';
	const THEME_KEY = 'wpo_registered_image_sizes_theme';

	/**
	 * Add methods related to images sizes
	 */
	private function __construct() {
		// Cache sizes registered by the theme & plugins
		add_action('init', array($this, 'maybe_store_current_site_image_sizes'), 999);

		// Force a recompute next time a plugin's state changes.
		add_action('activated_plugin', array($this, 'remove_current_site_image_sizes'));
		add_action('deactivated_plugin', array($this, 'remove_current_site_image_sizes'));

		// Force check when a theme is updated.
		add_action('upgrader_process_complete', array($this, 'maybe_remove_cached_sizes_on_upgrade'), 10, 2);
	}

	/**
	 * Returns singleton instance of this class
	 *
	 * @return WP_Optimize_Image_Sizes Singleton Instance
	 */
	public static function get_instance() {
		static $instance = null;
		if (null === $instance) {
			$instance = new self();
		}
		return $instance;
	}

	/**
	 * Store current site's image sizes, but only if the theme has changed
	 * since the last time we stored them (or nothing is stored yet).
	 *
	 * @return void
	 */
	public function maybe_store_current_site_image_sizes(): void {
		$current_theme = get_stylesheet();
		$stored_theme  = get_option(self::THEME_KEY, false);
		$stored_sizes  = get_option(self::OPTION_KEY, false);

		if (false !== $stored_sizes && $stored_theme === $current_theme) {
			return;
		}

		$this->store_current_site_image_sizes();
	}

	/**
	 * Store sizes for the current site.
	 *
	 * @return void
	 */
	public function store_current_site_image_sizes(): void {
		update_option(self::OPTION_KEY, get_intermediate_image_sizes());
		update_option(self::THEME_KEY, get_stylesheet());
	}

	/**
	 * Remove stored sizes for the current site so they're recomputed on the next request.
	 *
	 * @return void
	 */
	public function remove_current_site_image_sizes(): void {
		delete_option(self::OPTION_KEY);
		delete_option(self::THEME_KEY);
	}

	/**
	 * Remove stored sizes when a upgrade completes.
	 *
	 * Covers the cases where a theme, plugin, or even WP Core upgrade adds/removes add_image_size() calls
	 *
	 * @param \WP_Upgrader         $upgrader
	 * @param array<string, mixed> $hook_extra
	 * @return void
	 */
	public function maybe_remove_cached_sizes_on_upgrade($upgrader, $hook_extra): void { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable -- Required by upgrader_process_complete hook signature; not needed since all sites are cleared unconditionally.
		if (!is_multisite()) {
			$this->remove_current_site_image_sizes();
			return;
		}

		$blog_ids = get_sites(array('fields' => 'ids', 'number' => 0));
		foreach ($blog_ids as $blog_id) {
			$blog_id = (int) $blog_id;

			if (get_current_blog_id() === $blog_id) {
				$this->remove_current_site_image_sizes();
				continue;
			}

			switch_to_blog($blog_id);
			$this->remove_current_site_image_sizes();
			restore_current_blog();
		}
	}

	/**
	 * Get stored sizes for a specific site without switching global state.
	 * Works for both single-site and multisite.
	 *
	 * @param int $blog_id
	 * @return string[]|null Null if this site has never been recorded, or if the stored value is present but not an array.
	 */
	public function get_site_image_sizes(int $blog_id): ?array {
		if (!is_multisite() || get_current_blog_id() === $blog_id) {
			$sizes = get_option(self::OPTION_KEY, null);
		} else {
			$sizes = get_blog_option($blog_id, self::OPTION_KEY, null);
		}

		if (!is_array($sizes)) {
			return null;
		}

		/** @var string[] $sizes */
		return $sizes;
	}

	/**
	 * Get sizes for every site in the network, computing on demand (via switch_to_blog) for any site that hasn't recorded yet.
	 *
	 * @param int[] $blog_ids       Defaults to all sites.
	 * @param bool  $store_computed Whether to persist on-demand results so the next call is a pure read.
	 * @return array<int, string[]>
	 */
	public function get_network_image_sizes(array $blog_ids = array(), bool $store_computed = true): array {

		if (!is_multisite()) {
			return array(get_current_blog_id() => $this->get_or_compute_current_site_sizes());
		}

		if (empty($blog_ids)) {
			$blog_ids = array_map('intval', get_sites(array('fields' => 'ids', 'number' => 0)));
		}

		$network_sizes = array();

		foreach ($blog_ids as $blog_id) {
			$sizes = $this->get_site_image_sizes($blog_id);

			if (null !== $sizes) {
				$network_sizes[$blog_id] = $sizes;
				continue;
			}

			// Never recorded — compute it now in that site's actual context.
			switch_to_blog($blog_id);
			$computed = get_intermediate_image_sizes();
			if ($store_computed) {
				update_option(self::OPTION_KEY, $computed);
				update_option(self::THEME_KEY, get_stylesheet());
			}
			restore_current_blog();

			$network_sizes[$blog_id] = $computed;
		}

		return $network_sizes;
	}

	/**
	 * Get sizes for the current site, computing and persisting them on demand if nothing has been recorded yet.
	 *
	 * @return string[] List of registered image size names for the current site.
	 */
	private function get_or_compute_current_site_sizes(): array {
		$sizes = $this->get_site_image_sizes(get_current_blog_id());
		if (null === $sizes) {
			$this->store_current_site_image_sizes();
			$sizes = get_intermediate_image_sizes();
		}
		return $sizes;
	}
}

endif;
