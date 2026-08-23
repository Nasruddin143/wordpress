<?php
/**
 * Module Manager.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

/**
 * Module manager.
 */
final class ModuleManager
{

    /**
     * Registered modules.
     *
     * @var array<string, Module>
     */
    private array $modules = array();

    /**
     * Register modules from configuration.
     *
     * Supports both flat and grouped configurations.
     *
     * Flat:
     *
     * array(
     *     Setup::class,
     *     Asset::class
     * )
     *
     * Grouped:
     *
     * array(
     *     'theme' => array(
     *         Setup::class,
     *         Asset::class,
     *     ),
     *     'woocommerce' => array(
     *         WooCommerce_Setup::class,
     *     )
     * )
     *
     * @param array<int|string, mixed> $module_config Module configuration.
     *
     * @return void
     */
    public function register(array $module_config): void
    {

        foreach ($module_config as $module) {

            /*
             * Direct module class.
             */
            if (is_string($module)) {
                $this->register_module($module);
                continue;
            }

            /*
             * Module group.
             *
             * This allows Theme and WooCommerce modules to be grouped
             * in Config/modules.php while still using this same manager.
             */
            if (is_array($module)) {
                $this->register($module);
            }
        }
    }

    /**
     * Register a single module.
     *
     * @param string $module_class Fully qualified module class name.
     *
     * @return void
     */
    private function register_module(string $module_class): void
    {

        /*
         * Prevent duplicate registration.
         */
        if (isset($this->modules[$module_class])) {
            return;
        }

        /*
         * Module class must exist.
         */
        if (!class_exists($module_class)) {
            return;
        }

        /*
         * Instantiate the module with this same manager.
         */
        $module = new $module_class($this);

        /*
         * Ensure the class follows the Module contract.
         */
        if (!$module instanceof Module) {
            return;
        }

        /*
         * Register the module's WordPress hooks.
         */
        $module->register();

        /*
         * Store the module.
         */
        $this->modules[$module_class] = $module;
    }

    /**
     * Get a registered module.
     *
     * @param class-string<Module> $module_class Module class.
     *
     * @return Module|null
     */
    public function get(string $module_class): ?Module
    {

        return $this->modules[$module_class] ?? null;
    }

    /**
     * Check whether a module is registered.
     *
     * @param class-string<Module> $module_class Module class.
     *
     * @return bool
     */
    public function has(string $module_class): bool
    {

        return isset($this->modules[$module_class]);
    }

    /**
     * Get all registered modules.
     *
     * @return array<string, Module>
     */
    public function all(): array
    {

        return $this->modules;
    }
}