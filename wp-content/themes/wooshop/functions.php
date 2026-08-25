<?php
/**
 * WooShop Theme Functions.
 *
 * @package WooShop
 */

use WooShop\Core\Loader;

defined( 'ABSPATH' ) || exit;

add_action('wp_head', function () {
    global $template;
    echo "<!-- Template Used: " . basename($template) . " -->";
});


require_once get_template_directory() . '/inc/Core/Loader.php';

Loader::boot();