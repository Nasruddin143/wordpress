<?php
/**
 * WooShop Theme
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

require_once get_template_directory() . '/inc/Core/Autoloader.php';

\WooShop\Core\Autoloader::register();

$loader = new \WooShop\Core\Loader();

$loader->boot();