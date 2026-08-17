<?php
/**
 * WooShop Loader.
 *
 * Bootstraps the WooShop core services and modules.
 *
 * @package WooShop
 */

namespace WooShop\Core;

use ReflectionException;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WooShop application bootstrapping.
 */
class Loader {

    /**
     * Service container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Module manager.
     *
     * @var ModuleManager
     */
    protected ModuleManager $module_manager;

    /**
     * Constructor.
     *
     * @param Container     $container      Service container.
     * @param ModuleManager $module_manager Module manager.
     */
    public function __construct(
        Container $container,
        ModuleManager $module_manager
    ) {

        $this->container      = $container;
        $this->module_manager = $module_manager;
    }

    /**
     * Load WooShop modules.
     *
     * @param array $modules Module class names.
     * @return void
     */
    /**
     * Load WooShop modules.
     *
     * @param array $groups Module groups.
     * @return void
     * @throws ReflectionException
     */
    public function load( array $groups ): void {

        foreach ( $groups as $group => $modules ) {

            if ( 'woocommerce' === $group && ! class_exists( 'WooCommerce' ) ) {
                continue;
            }

            $this->module_manager->add_many( $modules );
        }

        $this->module_manager->register();
    }

    /**
     * Retrieve the service container.
     *
     * @return Container
     */
    public function container(): Container {

        return $this->container;
    }

    /**
     * Retrieve the module manager.
     *
     * @return ModuleManager
     */
    public function modules(): ModuleManager {

        return $this->module_manager;
    }
}