<?php
/**
 * WooShop functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WooShop
 */

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

require_once get_template_directory() . '/inc/nav-walker.php';

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function wooshop_setup()
{
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on WooShop, use a find and replace
	 * to change 'wooshop' to the name of your theme in all the template files.
	 */
	load_theme_textdomain('wooshop', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support('title-tag');

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'primary' => esc_html__('Primary', 'wooshop'),
		)
	);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'wooshop_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height' => 68,
			'width' => 68,
			'flex-width' => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'wooshop_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wooshop_content_width()
{
	$GLOBALS['content_width'] = apply_filters('wooshop_content_width', 640);
}
add_action('after_setup_theme', 'wooshop_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wooshop_widgets_init()
{
	register_sidebar(
		array(
			'name' => esc_html__('Sidebar', 'wooshop'),
			'id' => 'sidebar-1',
			'description' => esc_html__('Add widgets here.', 'wooshop'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		)
	);
}
add_action('widgets_init', 'wooshop_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function wooshop_scripts()
{
	wp_enqueue_style('bootswatch-pulse', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), _S_VERSION);
	wp_enqueue_style('wooshop-style', get_stylesheet_uri(), array(), _S_VERSION);
	wp_style_add_data('wooshop-style', 'rtl', 'replace');

	wp_enqueue_script('wooshop-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);
	wp_enqueue_script('wooshop-bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js', array(), _S_VERSION, true);
	wp_enqueue_script('wooshop-common', get_template_directory_uri() . '/assets/js/common.js', array(), _S_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'wooshop_scripts');

function wooshop_enqueue_embla() {

    if ( ! is_front_page() ) {
        return;
    }

    wp_enqueue_style(
        'embla-css',
        get_template_directory_uri() . '/assets/css/embla.css',
        array(),
        '1.0'
    );

    wp_enqueue_script(
        'embla',
        get_template_directory_uri() . '/assets/js/embla.min.js',
        array(),
        '8.6.0',
        true
    );

    wp_enqueue_script(
        'embla-init',
        get_template_directory_uri() . '/assets/js/embla-init.js',
        array('embla'),
        '1.0',
        true
    );

}
add_action('wp_enqueue_scripts', 'wooshop_enqueue_embla');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if (class_exists('WooCommerce')) {
	require get_template_directory() . '/inc/woocommerce.php';
}


/**
 * Load Bootstrap Slider Custom Post type and Slider Code file.
 */
require get_template_directory() . '/inc/slider-banner.php';

