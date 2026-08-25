<?php
/**
 * WooShop Loader.
 *
 * Boots the WooShop framework.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Application loader.
 */
final class Loader {

    /**
     * Service container.
     *
     * @var Container
     */
    private Container $container;

    /**
     * Boot WooShop.
     *
     * @return void
     */
    public static function boot(): void {
        $loader = new self();
        $loader->initialize();
    }

    /**
     * Initialize the framework.
     *
     * @return void
     */
    private function initialize(): void {
        $this->container = new Container();

        $this->register_core_services();
        $this->register_modules();
    }

    /**
     * Register core services.
     *
     * @return void
     */
    private function register_core_services(): void {
        $config = new Config();

        $this->container->set( 'config', $config );

        $assets = new AssetsManager( $config );

        $this->container->set( 'assets', $assets );

        $assets->register();
    }

    /**
     * Register theme modules.
     *
     * @return void
     */
    private function register_modules(): void {
        $module_manager = new ModuleManager(
            $this->container
        );

        $this->container->set(
            'modules',
            $module_manager
        );

        $module_manager->register();
    }
}