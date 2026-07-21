<?php
/**
 * Jetpack Compatibility File
 *
 * @package king_tailors
 */

/**
 * Jetpack setup
 */
function kt_jetpack_setup()
{
	/**
	 * Infinite Scroll
	 * SEO SAFE: desktop only + footer pagination fallback
	 */
	if (!wp_is_mobile()) {
		add_theme_support(
			'infinite-scroll',
			array(
				'container' => 'main',
				'render'    => 'kt_infinite_scroll_render',
				'footer'    => 'page',
				'posts_per_page' => get_option('posts_per_page'),
			)
		);
	}

	/**
	 * Responsive Videos
	 * Enable ONLY if embeds are used
	 */
	add_theme_support('jetpack-responsive-videos');

	/**
	 * Content Options
	 */
	add_theme_support(
		'jetpack-content-options',
		array(
			'post-details' => array(
				'stylesheet' => 'kt-style',
				'date'       => '.posted-on',
				'categories' => '.cat-links',
				'tags'       => '.tags-links',
				'author'     => '.byline',
				'comment'    => '.comments-link',
			),
			'featured-images' => array(
				'archive' => true,
				'post'    => true,
				'page'    => true,
			),
		)
	);
}
add_action('after_setup_theme', 'kt_jetpack_setup');

if (!function_exists('kt_infinite_scroll_render')) :
	function kt_infinite_scroll_render()
	{
		while (have_posts()) {
			the_post();

			if (is_search()) {
				get_template_part('template-parts/content', 'search');
			} else {
				get_template_part('template-parts/content', get_post_type());
			}
		}
	}
endif;

/**
 * Disable Jetpack frontend scripts (PageSpeed boost)
 */
add_filter('jetpack_should_load_frontend_scripts', '__return_false');

/**
 * Disable Jetpack CSS
 */
add_filter('jetpack_implode_frontend_css', '__return_false');

/**
 * Disable Jetpack Lazy Load (use WP core)
 */
add_filter('jetpack_lazy_images_enabled', '__return_false');

/**
 * Prevent Jetpack meta duplication
 */
add_filter('jetpack_enable_open_graph', '__return_false');
add_filter('jetpack_enable_seo_tools', '__return_false');
