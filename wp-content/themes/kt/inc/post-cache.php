<?php

/**
 * -----------------------------------------------------------------------------
 * KT Global Query Cache Layer
 * Classic Theme Compatible
 * InfinityFree Optimized
 * -----------------------------------------------------------------------------
 */

defined('ABSPATH') || exit;

class KT_Query_Cache
{

	private static $group = 'queries';


	/**
	 * Generate cache key
	 */
	private static function get_cache_key($query)
	{
		$vars = $query->query_vars;

		unset(
			$vars['cache_results'],
			$vars['update_post_meta_cache'],
			$vars['update_post_term_cache'],
			$vars['lazy_load_term_meta'],
			$vars['suppress_filters']
		);

		return 'query_' . md5(serialize($vars));
	}


	/**
	 * Should cache this query?
	 */
	private static function should_cache($query)
	{
		if (is_admin()) return false;

		if (!$query instanceof WP_Query) return false;

		if ($query->is_singular) return false;

		if ($query->get('orderby') === 'rand') return false;

		if ($query->get('no_found_rows')) return false;

		if (
			$query->is_archive ||
			$query->is_tax ||
			$query->is_search ||
			$query->get('post_type')
		) {
			return true;
		}

		return false;
	}


	/**
	 * Load cache
	 */
	public static function maybe_load_cache($query)
	{
		if (!self::should_cache($query)) return;

		$key = self::get_cache_key($query);

		$cached = KT_Cache::get($key, self::$group);

		if ($cached !== false) {

			$query->posts = $cached['posts'];
			$query->post_count = $cached['post_count'];
			$query->found_posts = $cached['found_posts'];
			$query->max_num_pages = $cached['max_num_pages'];

			$query->kt_cached = true;

			return;
		}

		$query->kt_cache_key = $key;
	}


	/**
	 * Save cache
	 */
	public static function maybe_save_cache($posts, $query)
	{
		if (!self::should_cache($query)) return $posts;

		if (!empty($query->kt_cached)) return $posts;

		if (empty($query->kt_cache_key)) return $posts;

		$data = [

			'posts' => $posts,

			'post_count' => $query->post_count,

			'found_posts' => $query->found_posts,

			'max_num_pages' => $query->max_num_pages

		];

		KT_Cache::set(

			$query->kt_cache_key,

			$data,

			self::$group

		);

		return $posts;
	}


	/**
	 * Flush all query cache safely
	 */
	public static function flush_on_change()
	{
		global $wpdb;

		// Delete all object cache entries in group
		wp_cache_flush();

		// Optional: clear runtime cache
		if (method_exists('KT_Cache', 'flush_runtime')) {
			KT_Cache::flush_runtime();
		}
	}
}


/**
 * -----------------------------------------------------------------------------
 * HOOKS
 * -----------------------------------------------------------------------------
 */

// Load cache BEFORE DB query
add_action(
	'pre_get_posts',
	['KT_Query_Cache', 'maybe_load_cache'],
	1
);


// Save AFTER DB query
add_filter(
	'posts_results',
	['KT_Query_Cache', 'maybe_save_cache'],
	10,
	2
);


// Flush cache on content change
add_action('save_post', ['KT_Query_Cache', 'flush_on_change']);

add_action('deleted_post', ['KT_Query_Cache', 'flush_on_change']);

add_action('created_term', ['KT_Query_Cache', 'flush_on_change']);

add_action('edited_term', ['KT_Query_Cache', 'flush_on_change']);

add_action('delete_term', ['KT_Query_Cache', 'flush_on_change']);
