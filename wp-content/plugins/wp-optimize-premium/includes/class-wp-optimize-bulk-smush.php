<?php
if (!defined('ABSPATH')) die('Access denied.');

if (!class_exists('WP_Optimize_Bulk_Smush')) :

class WP_Optimize_Bulk_Smush {
	
	/**
	 * Constructor
	 */
	private function __construct() {
		// Add custom bulk action to media library
		add_filter('bulk_actions-upload', array($this, 'add_image_compression_bulk_action'));

		// Add new media filter to media library
		add_action('restrict_manage_posts', array($this, 'add_media_filter_dropdown'));
		add_action('parse_query', array($this, 'filter_media_library_query'));
		add_filter('posts_results', array($this, 'filter_media_library_results'), 10, 2);
	}
	
	/**
	 * Returns singleton instance
	 *
	 * @return WP_Optimize_Bulk_Smush
	 */
	public static function get_instance() {
		static $_instance = null;
		if (null === $_instance) {
			$_instance = new self();
		}
		return $_instance;
	}
	
	/**
	 * Adds `Compress/Restore` options to bulk action dropdown
	 *
	 * @param string[] $bulk_actions An array of bulk actions
	 *
	 * @return string[]
	 */
	public function add_image_compression_bulk_action($bulk_actions) {
		if (!WP_Optimize()->current_user_can()) return $bulk_actions;
		// Escaping here because core doesn't do that
		// https://github.com/WordPress/wordpress-develop/blob/6.4.1/src/wp-admin/includes/class-wp-list-table.php#L616
		$bulk_actions['wp_optimize_bulk_compression'] = esc_html__('Compress', 'wp-optimize');
		$bulk_actions['wp_optimize_bulk_restore'] = esc_html__('Restore original', 'wp-optimize');
		return $bulk_actions;
	}
	
	/**
	 * Adds a new filter to media library to filter compressed/uncompressed images
	 */
	public function add_media_filter_dropdown() {
		$screen = get_current_screen();
		
		if (null === $screen || 'upload' !== $screen->id) return;
		if (!WP_Optimize()->current_user_can()) return;
		$status = isset($_REQUEST['wpo_image_optimization_status']) ? sanitize_text_field(wp_unslash($_REQUEST['wpo_image_optimization_status'])) : 0; // phpcs:ignore WordPress.Security.NonceVerification -- retaining status
		$dropdown_options = array(
			'0' => __('All Media Files', 'wp-optimize'),
			'compressed' => __('Compressed', 'wp-optimize'),
			'uncompressed' => __('Uncompressed', 'wp-optimize'),
		);
		WP_Optimize()->include_template("images/upload.php", false, array('status' => $status, 'dropdown_options' => $dropdown_options));
	}
	
	/**
	 * Filters media library items based on compressed/uncompressed status
	 *
	 * @param WP_Query $query WordPress query object
	 *
	 * @return void
	 */
	public function filter_media_library_query($query) {
		global $pagenow, $typenow;
		if (!$query->is_main_query()) return;
		
		if ('upload.php' !== $pagenow) return;

		$filter = TeamUpdraft\WP_Optimize\Includes\Fragments\fetch_superglobal('get', 'wpo_image_optimization_status', 'string', 'sanitize_text_field', '');
		
		if ('attachment' === $typenow && !empty($filter)) {
			$this->security_check();

			$allowed_filters = array('compressed', 'uncompressed');
			if (!in_array($filter, $allowed_filters)) return;
			
			$meta_key = '_wpo-smush-complete';
			$meta_query = array();
			
			if ('compressed' === $filter) {
				$meta_query[] = array(
					array(
						'key' => $meta_key,
						'value' => '1',
					),
				);
			} elseif ('uncompressed' === $filter) {
				$meta_query[] = Updraft_Smush_Manager()->get_uncompressed_images_meta_query();
			}
			
			$query->set('post_mime_type', 'image');
			$query->set('meta_query', $meta_query);
		}
	}
	
	/**
	 * Filter media library query results
	 *
	 * @param WP_Post[] $posts An array of posts
	 * @param WP_Query  $query Query
	 *
	 * @return array
	 */
	public function filter_media_library_results($posts, $query) {
		global $pagenow, $typenow;
		
		if (!$query->is_main_query() || 'upload.php' !== $pagenow || 'attachment' !== $typenow) return $posts;

		if (!empty(TeamUpdraft\WP_Optimize\Includes\Fragments\fetch_superglobal('get', 'wpo_image_optimization_status'))) {
			$this->security_check();
			
			$filter = TeamUpdraft\WP_Optimize\Includes\Fragments\fetch_superglobal('get', 'wpo_image_optimization_status', 'string', 'sanitize_text_field', '');
			$allowed_filters = array('compressed', 'uncompressed');
			if (!in_array($filter, $allowed_filters)) {
				return $posts;
			}

			return array_values(array_filter($posts, function($post) {
				return !in_array($post->ID, $this->get_ewww_io_compressed_images());
			}));

		}
		return $posts;
	}
	
	/**
	 * Retrieves an array of attachment IDs that are compressed by ewww image optimize
	 *
	 * @return array
	 */
	private function get_ewww_io_compressed_images() {
		if (!WP_Optimize()->get_db_info()->table_exists('ewwwio_images')) return array();
		
		global $wpdb;
		return $wpdb->get_col("SELECT DISTINCT(attachment_id) FROM {$wpdb->prefix}ewwwio_images WHERE gallery='media'");
		
	}
	
	/**
	 * Security check
	 */
	private function security_check() {
		if (!WP_Optimize()->current_user_can()) {
			die('You are not allowed to run this command.');
		}

		$verified = TeamUpdraft\WP_Optimize\Includes\Fragments\verify_nonce('wpo_media_filter_nonce', 'wpo_media_filter');
		if (!$verified) {
			die('Security check failed.');
		}
	}

	/**
	 * Get percentage of compressed images.
	 *
	 * @return int
	 */
	public function get_optimized_images_percentage() {
		global $wpdb;

		$image_like = 'image/%';

		// Total images query
		$total_images = (int) $wpdb->get_var($wpdb->prepare(
				"SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = %s AND post_status = %s AND post_mime_type LIKE %s",
				'attachment',
				'inherit',
				$image_like
			)
		);

		if (0 === $total_images) {
			return 0;
		}

		$compressed_images = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM $wpdb->posts p INNER JOIN $wpdb->postmeta pm ON p.ID = pm.post_id WHERE p.post_type = %s AND p.post_status = %s AND p.post_mime_type LIKE %s AND pm.meta_key = %s AND pm.meta_value = %s",
				'attachment',
				'inherit',
				$image_like,
				'_wpo-smush-complete',
				'1'
			)
		);

		return (int) round(($compressed_images / $total_images) * 100);
	}
}
endif;
