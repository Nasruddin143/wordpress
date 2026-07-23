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
	/**
	 * Sidebar Widgets 
	 */
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


	/**
	 * Advertisement Banners 
	 */

	for ($i = 1; $i <= 3; $i++) {

		register_sidebar(array(
			'name' => sprintf(__('Advertisement %d', 'wooshop'), $i),
			'id' => 'advertisement-banner-' . $i,
			'description' => sprintf(__('Widgets in Page Column %d.', 'wooshop'), $i),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '',
			'after_title' => '',
		));
	}

	/**
	 * Register Footer widget area.
	 */

	register_sidebar(array(
		'name' => sprintf(__('Footer Links', 'wooshop')),
		'id' => 'footer-sidebar-1',
		'description' => sprintf(__('Widgets in Footer Column 1.', 'wooshop')),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widget-title mb-4 text-second">',
		'after_title' => '</h4>',
	));
}
add_action('widgets_init', 'wooshop_widgets_init');


add_filter('get_search_form', 'wooshop_search_form');

function wooshop_search_form()
{

	return '
    <form role="search" method="get" class="search-form" action="' . esc_url(home_url('/')) . '">

        <div class="input-group">

            <input type="search" class="form-control" placeholder="Search.." name="s">

            <button class="btn btn-primary">

				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
				<circle cx="11" cy="11" r="8" />
				<line x1="21" x2="16.65" y1="21" y2="16.65" />
				</svg>

            </button>

        </div>

    </form>';

}


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

function wooshop_enqueue_embla()
{

	if (!is_front_page()) {
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


/**
 * Bootstrap Comment Callback
 */

function wooshop_comment_callback($comment, $args, $depth)
{

	$GLOBALS['comment'] = $comment;

	?>

	<li <?php comment_class('mb-4'); ?> id="comment-<?php comment_ID(); ?>">

		<article class="card shadow-sm border-0">

			<div class="card-body">

				<div class="d-flex">

					<div class="flex-shrink-0 me-3">

						<?php

						echo get_avatar(

							$comment,

							70,

							'',

							'',

							array(

								'class' => 'rounded-circle'

							)

						);

						?>

					</div>

					<div class="flex-grow-1">

						<div class="d-flex justify-content-between align-items-center mb-2">

							<div>

								<h6 class="mb-0">

									<?php comment_author_link(); ?>

								</h6>

								<small class="text-muted">

									<?php

									echo esc_html(

										get_comment_date()

									);

									?>

								</small>

							</div>

						</div>

						<?php if ($comment->comment_approved == '0'): ?>

							<div class="alert alert-warning py-2">

								<?php esc_html_e('Your comment is awaiting moderation.', 'wooshop'); ?>

							</div>

						<?php endif; ?>

						<div class="comment-content mt-3">

							<?php comment_text(); ?>

						</div>

						<div class="mt-3">

							<?php

							comment_reply_link(

								array_merge(

									$args,

									array(

										'depth' => $depth,

										'max_depth' => $args['max_depth'],

										'reply_text' => __('Reply', 'wooshop'),

										'class' => 'btn btn-sm btn-outline-primary'

									)

								)

							);

							?>

						</div>

					</div>

				</div>

			</div>

		</article>

	</li>

	<?php

}