<?php
/**
 * WooShop theme bootstrap.
 *
 * Loads the Composer-style theme autoloader and starts
 * the WooShop application through the Core Loader.
 *
 * @return void
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

require_once get_theme_file_path(
    'inc/Core/Autoloader.php'
);

$autoloader = new \WooShop\Core\Autoloader();

$autoloader->register();

$loader = new \WooShop\Core\Loader();

$loader->register();