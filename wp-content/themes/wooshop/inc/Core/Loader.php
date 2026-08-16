<?php
/**
 * WooShop Application Loader
 *
 * Boots the core application and registered modules.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined("ABSPATH") || exit();

/**
 * Loader class.
 */
class Loader extends \WooShop\Core\Container
{
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
     * Receives the existing WooShop service container.
     *
     * @param Container $container Service container.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Register core services.
     *
     * AssetsManager loads the existing centralized asset
     * configuration without performing database queries.
     *
     * @return void
     */
    public function register_core_services(): void
    {
        $config = new Config($this->container);

        $this->container->set('config', $config);

        $this->container->set(Config::class, $config);

        $assets = new AssetsManager();

        $this->container->set("assets", $assets);

        $this->container->set(AssetsManager::class, $assets);
    }

    /**
     * Boot the WooShop application.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->register_core_services();

        $this->register_modules();
    }

    /**
     * Register configured modules through ModuleManager.
     *
     * ModuleManager is responsible for resolving the shared
     * container dependency and calling each module's register().
     *
     * @return void
     */
    protected function register_modules(): void
    {
        $modules_file = get_stylesheet_directory() . "/inc/Config/modules.php";

        if (!file_exists($modules_file)) {
            return;
        }

        $modules = require $modules_file;

        if (!is_array($modules)) {
            return;
        }

        $this->module_manager = new ModuleManager($this->container);

        $this->container->set("module_manager", $this->module_manager);

        $this->container->set(ModuleManager::class, $this->module_manager);

        /*
         * ModuleManager::register() requires the configured
         * module array. Do not call it without arguments.
         */
        $this->module_manager->register($modules);
    }

    /**
     * Get the service container.
     *
     * @return Container
     */
    public function get_container(): Container
    {
        return $this->container;
    }

    /**
     * Get the module manager.
     *
     * @return ModuleManager|null
     */
    public function get_module_manager(): ?ModuleManager
    {
        return $this->module_manager;
    }
}
