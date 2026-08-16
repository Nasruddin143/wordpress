<?php
/**
 * WooShop theme bootstrap.
 *
 * Loads the Composer-style theme autoloader and starts
 * the WooShop application through the Core Loader.
 *
 * @return void
 */


/**
 * WooShop theme bootstrap.
 *
 * Loads the Composer-style theme autoloader and starts
 * the WooShop application through the Core Loader.
 *
 * @return void
 */

defined("ABSPATH") || exit();

add_action("wp_head", function () {
    global $template;
    echo "<!-- Template Used: " . basename($template) . " -->";
});

/**
 * WooShop Theme Bootstrap
 *
 * Loads the WooShop autoloader, creates the service container,
 * and boots the core application and registered modules.
 *
 * @package WooShop
 */

defined("ABSPATH") || exit();

/**
 * WooShop theme version.
 *
 * @var string
 */
define("WOOSHOP_VERSION", "1.0.0");

/**
 * WooShop theme directory.
 *
 * @var string
 */
define("WOOSHOP_DIR", get_template_directory());

/**
 * WooShop theme URI.
 *
 * @var string
 */
define("WOOSHOP_URI", get_template_directory_uri());

/**
 * Load the WooShop class autoloader.
 */
require_once WOOSHOP_DIR . "/inc/Core/Autoloader.php";

/**
 * Register WooShop autoloading.
 */
\WooShop\Core\Autoloader::register();

/**
 * Create the WooShop service container.
 *
 * The container stores shared core services and does not
 * perform database queries.
 *
 * @var \WooShop\Core\Container
 */
$wooshop_container = new \WooShop\Core\Container();

/**
 * Create the WooShop application loader.
 *
 * @var \WooShop\Core\Loader
 */
$wooshop_loader = new \WooShop\Core\Loader($wooshop_container);

/**
 * Boot WooShop core services and modules.
 */
$wooshop_loader->boot();
