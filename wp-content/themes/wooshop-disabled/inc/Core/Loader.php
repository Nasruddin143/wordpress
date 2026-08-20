<?php
/**
 * WooShop Core Loader
 *
 * Bootstraps the WooShop core architecture by registering the
 * autoloader, creating the service container, registering core
 * services, and starting the module manager.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Core;

use ReflectionException;

defined('ABSPATH') || exit;

/**
 * Class Loader
 *
 * Main entry point for the WooShop application architecture.
 */
final readonly class Loader {

    /**
     * Service container.
     *
     * @var Container
     */
    private Container $container;

    /**
     * Configuration manager.
     *
     * @var Config
     */
    private Config $config;

    /**
     * Module manager.
     *
     * @var ModuleManager
     */
    private ModuleManager $module_manager;

    /**
     * Constructor.
     *
     * Initializes the WooShop dependency graph.
     * @throws ReflectionException
     */
    public function __construct() {

        $this->container = new Container();

        $this->config = new Config();

        $this->register_core_services();

        $this->module_manager = $this->container->get(
            ModuleManager::class
        );
    }

    /**
     * Register the WooShop application.
     *
     * This method should be called once from functions.php.
     *
     * @return void
     * @throws ReflectionException
     */
    public function register(): void {

        $this->module_manager->register();
    }

    /**
     * Register core services.
     *
     * Core services are registered before any Theme or WooCommerce
     * module is instantiated.
     *
     * @return void
     */
    private function register_core_services(): void {

        $this->container->set(
            Container::class,
            $this->container
        );

        $this->container->set(
            Config::class,
            $this->config
        );

        $this->container->factory(
            AssetsManager::class,
            function (Container $container): AssetsManager {
                return new AssetsManager(
                    $container->get(Config::class)
                );
            }
        );

        $this->container->factory(
            ModuleManager::class,
            function (Container $container): ModuleManager {
                return new ModuleManager(
                    $container,
                    $this->config
                );
            }
        );
    }

    /**
     * Get the service container.
     *
     * @return Container
     */
    public function container(): Container {

        return $this->container;
    }

    /**
     * Get the configuration manager.
     *
     * @return Config
     */
    public function config(): Config {

        return $this->config;
    }

    /**
     * Get the module manager.
     *
     * @return ModuleManager
     */
    public function module_manager(): ModuleManager {

        return $this->module_manager;
    }
}