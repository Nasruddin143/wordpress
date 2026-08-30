<?php
/**
 * WooShop Loader
 *
 * Bootstraps the WooShop theme framework.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

/**
 * Class Loader
 */
final class Loader
{
    /**
     * Whether WooShop has already been booted.
     *
     * @var bool
     */
    private static bool $booted = false;

    /**
     * Boot WooShop.
     *
     * @return void
     */
    public static function boot(): void
    {
        if (self::$booted) {
            return;
        }

        self::$booted = true;

        /*
         * ---------------------------------------------------------
         * Theme paths
         * ---------------------------------------------------------
         */

        $theme_directory = get_template_directory();

        $core_directory = $theme_directory . '/inc/Core';

        /*
         * ---------------------------------------------------------
         * Bootstrap Autoloader
         *
         * Loader.php itself is manually loaded by functions.php.
         * Therefore Autoloader.php must be loaded manually once
         * before the WooShop autoloader can load the remaining
         * Core classes.
         * ---------------------------------------------------------
         */

        $autoloader_file = $core_directory . '/Autoloader.php';

        if (!is_readable($autoloader_file)) {
            return;
        }

        require_once $autoloader_file;

        /*
         * ---------------------------------------------------------
         * Register Autoloader
         * ---------------------------------------------------------
         */

        $autoloader = new Autoloader($theme_directory . '/inc');

        $autoloader->register();

        /*
         * ---------------------------------------------------------
         * Container
         * ---------------------------------------------------------
         */

        $container = new Container();

        /*
         * ---------------------------------------------------------
         * Configuration
         * ---------------------------------------------------------
         */

        $config = new Config($theme_directory . '/inc/Config');

        $container->set('config', $config);

        /*
         * ---------------------------------------------------------
         * Assets Manager
         * ---------------------------------------------------------
         */

        $assets_manager = new AssetsManager($container, $config->get('assets'), $config->get('conditions'));

        $container->set('assets', $assets_manager);

        $assets_manager->register();

        /*
         * ---------------------------------------------------------
         * Module Manager
         * ---------------------------------------------------------
         */

        $module_manager = new ModuleManager($container, $config->get('modules'));

        $container->set('modules', $module_manager);

        /*
         * ---------------------------------------------------------
         * Load Modules
         * ---------------------------------------------------------
         */

        $module_manager->load();

        /*
         * ---------------------------------------------------------
         * Global Container
         * ---------------------------------------------------------
         */

        $GLOBALS['wooshop_container'] = $container;
    }
}