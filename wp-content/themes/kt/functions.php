<?php

/**
 * kt functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package kt
 */

if (!defined('ABSPATH')) exit;

/**
 * -----------------------------------------------------------------------------
 * THEME VERSION
 * -----------------------------------------------------------------------------
 */
if (!defined('_S_VERSION')) {
    define('_S_VERSION', '3.1.0');
}

/**
 * -----------------------------------------------------------------------------
 * THEME AUTOLOADER
 * -----------------------------------------------------------------------------
 */
require_once get_template_directory() . '/inc/Core/Autoloader.php';


/**
 * -----------------------------------------------------------------------------
 * THEME INIT
 * -----------------------------------------------------------------------------
 */
add_action('after_setup_theme', function () {
    \KT\Core\Theme::get_instance();
});


/**
 * -----------------------------------------------------------------------------
 * THEME TRACE WHICH TEMPLATE FILE IS BEING USED (Temperory added)
 * -----------------------------------------------------------------------------
 */
add_action('wp_head', function () {
    global $template;
    echo "<!-- Template Used: " . basename($template) . " -->";
});


// if (!defined('ABSPATH')) {
// 	exit; // Security: Prevent direct access
// }



// function kt_load_inc($file, $condition) {
//     if ($condition) {
//         require_once get_template_directory() . '/inc/' . $file . '.php';
//     }
// }

// add_action('wp', function () {

// 	kt_load_inc('related-products', is_single());
//     // kt_load_inc('home', is_front_page());
//     // kt_load_inc('blog', is_home() || is_single());
//     // kt_load_inc('product', is_singular('product'));
//     // kt_load_inc('contact', is_page('contact'));

// });

// require_once get_template_directory() . '/inc/setup.php';
// require_once get_template_directory() . '/inc/enqueue.php';
// require_once get_template_directory() . '/inc/kt-bootstrap-5-nav-walker.php';
// require_once get_template_directory() . '/inc/cpt-master.php';
// require_once get_template_directory() . '/inc/kt-metabox.php';
// require_once get_template_directory() . '/inc/sidebar.php';
// require_once get_template_directory() . '/inc/helpers.php';
// require_once get_template_directory() . '/inc/cleanup.php';
// require_once get_template_directory() . '/inc/performance.php';
// require_once get_template_directory() . '/inc/cache.php';
// require_once get_template_directory() . '/inc/post-cache.php';
// require_once get_template_directory() . '/inc/customizer.php';
// require_once get_template_directory() . '/inc/internal-linking.php';

// VC Elements
// require_once get_template_directory() . '/vc-elements/vc-banner-carousel.php';
// require_once get_template_directory() . '/vc-elements/vc-image-content-box.php';
// require_once get_template_directory() . '/vc-elements/vc-product-slider.php';
// require_once get_template_directory() . '/vc-elements/vc-brand-carousel.php';
// require_once get_template_directory() . '/vc-elements/vc-client-testimonials-slider.php';

// Jetpack
// if (defined('JETPACK__VERSION')) {
// 	require_once get_template_directory() . '/inc/jetpack.php';
// }

// WooCommerce
// if (class_exists('WooCommerce')) {
// 	require_once get_template_directory() . '/inc/woocommerce.php';
// }