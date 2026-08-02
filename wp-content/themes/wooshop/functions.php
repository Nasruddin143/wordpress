<?php
/**
 * WooShop Theme Bootstrap
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

require_once get_template_directory() . '/inc/Core/Autoloader.php';

WooShop\Core\Autoloader::register();

(new WooShop\Core\Loader())->boot();