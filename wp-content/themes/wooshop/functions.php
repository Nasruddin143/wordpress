<?php
/**
 * WooShop Theme
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

add_action('wp_head', function () {
    global $template;
    echo "<!-- Template Used: " . basename($template) . " -->";
});

require_once get_template_directory() . '/inc/Core/Autoloader.php';

\WooShop\Core\Autoloader::register();

$loader = new \WooShop\Core\Loader();

$loader->boot();