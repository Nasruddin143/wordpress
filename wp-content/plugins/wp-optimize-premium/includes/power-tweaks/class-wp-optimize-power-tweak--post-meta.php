<?php

if (!defined('ABSPATH')) die('No direct access allowed');

class WP_Optimize_Power_Tweak__Post_Meta extends WP_Optimize_Power_Tweak {

	/**
	 * Tweak identifier
	 *
	 * @var string
	 */
	protected $tweak_name = 'post-meta';

	/**
	 * FAQ link
	 *
	 * @var string
	 */
	protected $tweak_link = 'https://teamupdraft.com/documentation/wp-optimize/topics/database-optimization/faqs/can-i-index-the-meta_key-in-the-postmeta-table/?utm_source=wpo-plugin&utm_medium=referral&utm_campaign=paac&utm_content=read-more-about-index-post-meta-table&utm_creative_format=text';

	/**
	 * Action type (`activate` for recurring, `run` for one-shot actions)
	 *
	 * @var string
	 */
	protected $action_type = 'run';

	/**
	 * Cached result of get_sites_status().
	 *
	 * @var array|null
	 */
	private $sites_status_cache = null;

	/**
	 * Initialize the tweaks
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get the labels
	 *
	 * @return array
	 */
	public function get_labels() {
		return array(
			'title' => __('Index Post Meta Table', 'wp-optimize'),
			'run' => __('Resize and index the meta_key column', 'wp-optimize'),
			'description' => __('By default, searches on the WordPress "post meta" database table are significantly slower than necessary because the table permits very rarely-used key sizes of longer than 191 characters, which prevents indexing the table.', 'wp-optimize') . ' ' . __('This tweak checks if anything in your database uses such long keys, and if not, creates an index by lowering the limit down to 191 characters.', 'wp-optimize'),
			'details' => __('Changes the table scheme for your postmeta table by reducing the maximum length of the meta_key field down to 191 characters (if nothing already exists longer than that).', 'wp-optimize')
		);
	}

	/**
	 * Run the tweak. On multisite, indexes all eligible subsites postmeta tables.
	 *
	 * @return array
	 */
	public function run() {
		if (is_multisite()) {
			return $this->run_multisite();
		}

		$success = array('message' => __('The post meta index was created successfully.', 'wp-optimize'));
		$failure = array('message' => __('The post meta index creation was unsuccessful.', 'wp-optimize'));

		if($this->create_post_meta_index()) return $success;
		return $failure;
	}

	/**
	 * Test the availability of the tweak.
	 * On multisite, returns true if at least one site has an indexable postmeta table.
	 *
	 * The multisite branch loops every site in the network, so it's only cheap to call from contexts
	 * that are already scoped to the network admin: rendering the Power Tweaks tab (which is only ever
	 * registered on network_admin_menu, see WP_Optimize_Admin::__construct()) or handling its AJAX
	 * run/activate/deactivate actions. This tweak's action_type is 'run' (one-shot), so it's never added
	 * to the active_power_tweaks option, and therefore never auto-run by
	 * WP_Optimize_Power_Tweaks::initialize_tweaks() on arbitrary front-end/subsite page loads.
	 *
	 * @return boolean
	 */
	public function test_availability() {
		if (is_multisite()) {
			foreach ($this->get_sites_status() as $site) {
				if ('eligible' === $site['status']) return true;
			}
			return false;
		}

		$last_run = WP_Optimize()->get_options()->get_option('tweak_last_run_post-meta', false);
		if (false === $last_run) {
			return $this->can_create_post_meta_index();
		}
		return false;
	}

	/**
	 * Create the post meta index by shrinking the column to 191 chars.
	 *
	 * @return boolean
	 */
	private function create_post_meta_index() {
		return $this->alter_post_meta_index(191);
	}

	/**
	 * Check whether an index can be created for the current site's postmeta table.
	 *
	 * @return boolean
	 */
	private function can_create_post_meta_index() {
		global $wpdb;
		$col_info = $wpdb->get_col_length($wpdb->postmeta, 'meta_key');
		if (!is_array($col_info) || $col_info['length'] <= 191) return false;
		// Bypass the transient here: this result gates an actual ALTER TABLE, so it must reflect
		// the current data, not a cached value that may be stale.
		return !$this->has_long_meta_keys(false);
	}

	/**
	 * Alter the meta_key column length and rebuild the index.
	 *
	 * @param  int $column_length
	 * @return boolean
	 */
	private function alter_post_meta_index($column_length = 255) {
		global $wpdb;
		$sql = "SHOW INDEX FROM $wpdb->postmeta WHERE KEY_NAME = 'meta_key'";
		$result = $wpdb->query($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Safe, no user input used
		if (false !== $result) {
			$sql = "DROP INDEX meta_key ON $wpdb->postmeta";
			$result = $wpdb->query($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Safe, no user input used
		}

		$result = $wpdb->query(
			$wpdb->prepare(
				"ALTER TABLE $wpdb->postmeta MODIFY COLUMN meta_key VARCHAR(%d)",
				$column_length
			)
		);

		if (false !== $result) {
			$sql = "CREATE INDEX meta_key ON $wpdb->postmeta (meta_key)";
			$result = $wpdb->query($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Safe, no user input used
			if (false !== $result) return true;
		}
		return false;
	}

	/**
	 * Extend parent data with per-site status on multisite.
	 *
	 * @return array
	 */
	public function get_data() {
		$data = parent::get_data();
		if (is_multisite()) {
			$sites_status              = $this->get_sites_status();
			$data['sites_status']      = $sites_status;
			$data['sites_status_html'] = $this->render_sites_status_table($sites_status);
		}
		return $data;
	}

	/**
	 * Query args for get_sites(), excluding sites that are archived, marked as spam, or deleted,
	 * since those aren't sites we should be switching to and altering tables on.
	 *
	 * @return array
	 */
	private function get_sites_query_args() {
		return array(
			'number'   => 0,
			'archived' => 0,
			'spam'     => 0,
			'deleted'  => 0,
		);
	}

	/**
	 * Loop every site in the network and index eligible postmeta tables.
	 * Per-site last_run is stored via native update_option() inside switch_to_blog(),
	 * so it lands in each site's own wp_N_options table.
	 *
	 * @return array
	 */
	private function run_multisite() {
		$sites   = get_sites($this->get_sites_query_args());
		$indexed = array();
		$failed  = array();

		foreach ($sites as $site) {
			switch_to_blog($site->blog_id);

			if ($this->can_create_post_meta_index()) {
				$site_label = esc_html(get_bloginfo('name') ?: get_bloginfo('url'));
				if ($this->create_post_meta_index()) {
					update_option('wp-optimize-tweak_last_run_post-meta', time());
					$indexed[] = $site_label;
				} else {
					$failed[] = $site_label;
				}
			}

			restore_current_blog();
		}

		// The loop above changed the per-site index state, so the memoized status is stale.
		$this->sites_status_cache = null;

		$parts = array();
		if ($indexed) {
			// translators: %s is a comma-separated list of site names
			$parts[] = sprintf(__('Indexed successfully: %s.', 'wp-optimize'), implode(', ', $indexed));
		}
		if ($failed) {
			// translators: %s is a comma-separated list of site names
			$parts[] = sprintf(__('Failed to index: %s.', 'wp-optimize'), implode(', ', $failed));
		}
		if (empty($parts)) {
			$parts[] = __('No sites required indexing.', 'wp-optimize');
		}

		$sites_status = $this->get_sites_status();

		return array(
			'message'           => implode(' ', $parts),
			'sites_status_html' => $this->render_sites_status_table($sites_status),
		);
	}

	/**
	 * Return per-site status for every site in the network.
	 *
	 * Possible statuses:
	 * eligible – column > 191, no long keys → ready to index
	 * already_indexed – column already ≤ 191
	 * has_long_keys – column > 191 but a meta_key longer than 191 exists → cannot index
	 *
	 * @return array  Keyed by blog_id.
	 */
	public function get_sites_status() {
		if (null !== $this->sites_status_cache) return $this->sites_status_cache;

		$sites  = get_sites($this->get_sites_query_args());
		$result = array();

		foreach ($sites as $site) {
			switch_to_blog($site->blog_id);

			global $wpdb;
			$last_run = get_option('wp-optimize-tweak_last_run_post-meta', false);
			$col_info = $wpdb->get_col_length($wpdb->postmeta, 'meta_key');

			if (!is_array($col_info) || $col_info['length'] <= 191) {
				$status = 'already_indexed';
			} elseif ($this->has_long_meta_keys()) {
				$status = 'has_long_keys';
			} else {
				$status = 'eligible';
			}

			$result[$site->blog_id] = array(
				'name'     => get_bloginfo('name') ?: get_bloginfo('url'),
				'url'      => get_bloginfo('url'),
				'status'   => $status,
				'last_run' => $last_run,
			);

			restore_current_blog();
		}

		$this->sites_status_cache = $result;
		return $result;
	}

	/**
	 * Render the per-site status HTML table.
	 * Used on initial page load and returned in AJAX responses so the DOM can be updated without a reload.
	 *
	 * @param  array $sites_status
	 * @return string
	 */
	private function render_sites_status_table($sites_status) {
		if (empty($sites_status)) return '';

		foreach ($sites_status as &$site) {
			$site['status_label'] = $this->get_status_label($site['status']);
		}
		unset($site);

		return WP_Optimize()->include_template('database/power-tweak-post-meta-sites-status.php', true, array(
			'sites_status' => $sites_status,
		));
	}

	/**
	 * Human-readable label for a site status code.
	 *
	 * @param  string $status
	 * @return string
	 */
	private function get_status_label($status) {
		$labels = array(
			'eligible'        => __('Ready to index', 'wp-optimize'),
			'already_indexed' => __('Already indexed', 'wp-optimize'),
			'has_long_keys'   => __('Has meta keys longer than 191 chars — cannot index', 'wp-optimize'),
		);
		return isset($labels[$status]) ? $labels[$status] : $status;
	}

	/**
	 * Check whether any row in the current site's postmeta table has a meta_key longer than 191 chars.
	 * Cached in a transient (per site) because this scans the whole postmeta table, and the result
	 * rarely changes between checks. Pass $use_cache = false when the result is about to gate an
	 * actual ALTER TABLE, since a stale cached result could let a newly-added long meta_key through.
	 *
	 * @param  boolean $use_cache
	 * @return boolean
	 */
	private function has_long_meta_keys($use_cache = true) {
		if ($use_cache) {
			$cached = get_transient('wpo_post_meta_has_long_keys');
			if ('yes' === $cached) return true;
			if ('no' === $cached) return false;
		}

		global $wpdb;
		$meta_key = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT meta_key FROM {$wpdb->postmeta} WHERE LENGTH(meta_key) > %d LIMIT %d",
				191, 1
			)
		);
		$has_long_keys = null !== $meta_key;
		set_transient('wpo_post_meta_has_long_keys', $has_long_keys ? 'yes' : 'no', DAY_IN_SECONDS);
		return $has_long_keys;
	}
}

return new WP_Optimize_Power_Tweak__Post_Meta();
