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

use WooShop\Core\AssetsManager;
use WooShop\Core\Loader;
use WooShop\Core\ModuleManager;
use WooShop\Core;

defined("ABSPATH") || exit();

add_action("wp_head", function () {
    global $template;
    echo "<!-- Template Used: " . basename($template) . " -->";
});


/**
 * WooShop Theme Bootstrap.
 *
 * Starts the WooShop application.
 *
 * @package WooShop
 */

defined("ABSPATH") || exit();

/**
 * Load the WooShop autoloader.
 */
require_once get_template_directory() . "/inc/Core/Autoloader.php";

$wooshop_autoloader = new \WooShop\Core\Autoloader(
    get_template_directory() . "/inc"
);

$wooshop_autoloader->register();

/**
 * Create the service container.
 */
$wooshop_container = new \WooShop\Core\Container();

/**
 * Register shared AssetsManager.
 */
$wooshop_container->instance(
    AssetsManager::class,
    new AssetsManager(require get_template_directory() . "/inc/Config/assets.php")
);

/**
 * Create the module manager.
 */
$wooshop_module_manager = new ModuleManager($wooshop_container);

/**
 * Create the application loader.
 */
$wooshop_loader = new Loader(
    $wooshop_container,
    $wooshop_module_manager
);

/**
 * Load configured modules.
 */

    $wooshop_loader->load(
        require get_template_directory() . "/inc/Config/modules.php"
    );

