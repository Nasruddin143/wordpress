<?php
/**
 * WooShop Module Manager
 *
 * Loads module configuration, resolves module dependencies,
 * registers module instances, and controls the WooShop module
 * lifecycle.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Core;

defined('ABSPATH') || exit;

/**
 * Class ModuleManager
 *
 * Central registry and lifecycle manager for WooShop modules.
 */
final class ModuleManager {

    /**
     * Service container.
     *
     * @var Container
     */
    private readonly Container $container;

    /**
     * Configuration manager.
     *
     * @var Config
     */
    private readonly Config $config;

    /**
     * Registered module instances.
     *
     * @var array<string, Module>
     */
    private array $modules = array();

    /**
     * Constructor.
     *
     * @param Container $container WooShop service container.
     * @param Config    $config    Configuration manager.
     */
    public function __construct(
        Container $container,
        Config $config
    ) {
        $this->container = $container;
        $this->config    = $config;
    }

    /**
     * Register all configured WooShop modules.
     *
     * @return void
     */
    public function register(): void {

        $this->register_group('core');
        $this->register_group('theme');

        /*
         * WooCommerce modules are registered only when
         * WooCommerce is available.
         */
        if (class_exists('WooCommerce')) {
            $this->register_group('woocommerce');
        }
    }

    /**
     * Register a module configuration group.
     *
     * @param string $group Configuration group.
     *
     * @return void
     */
    private function register_group(string $group): void {

        $config = $this->config->get('modules');

        if (
            !isset($config[$group])
            || !is_array($config[$group])
        ) {
            return;
        }

        foreach ($config[$group] as $key => $module_config) {

            if (!is_array($module_config)) {
                continue;
            }

            $this->register_module(
                (string) $key,
                $module_config
            );
        }
    }

    /**
     * Register one module.
     *
     * @param string               $key    Module registry key.
     * @param array<string, mixed> $config Module configuration.
     *
     * @return void
     */
    private function register_module(
        string $key,
        array $config
    ): void {

        if (
            isset($config['enabled'])
            && false === (bool) $config['enabled']
        ) {
            return;
        }

        $class = $config['class'] ?? null;

        if (
            !is_string($class)
            || ''
            === $class
            || !class_exists($class)
        ) {
            return;
        }

        if (!is_a($class, Module::class, true)) {
            return;
        }

        /*
         * Resolve dependencies through the container.
         */
        $module = $this->container->make($class);

        if (!$module instanceof Module) {
            return;
        }

        /*
         * Store the module before registration so other modules
         * can resolve it during the registration lifecycle.
         */
        $this->modules[$key] = $module;

        /*
         * Register the module instance itself in the container.
         */
        $this->container->set(
            $class,
            $module
        );

        /*
         * Execute the module registration lifecycle.
         */
        $module->register();
    }

    /**
     * Retrieve a registered module.
     *
     * @template T of Module
     *
     * @param string $key Module registry key.
     *
     * @return T|null
     */
    public function get(string $key): ?Module {

        $module = $this->modules[$key] ?? null;

        return $module instanceof Module
            ? $module
            : null;
    }

    /**
     * Determine whether a module is registered.
     *
     * @param string $key Module registry key.
     *
     * @return bool
     */
    public function has(string $key): bool {

        return isset($this->modules[$key]);
    }

    /**
     * Get all registered modules.
     *
     * @return array<string, Module>
     */
    public function all(): array {

        return $this->modules;
    }

    /**
     * Register one module programmatically.
     *
     * Useful for modules that need to be conditionally added
     * outside the static modules.php configuration.
     *
     * @param string $key   Module registry key.
     * @param string $class Module class.
     *
     * @return Module|null
     */
    public function register_single(
        string $key,
        string $class
    ): ?Module {

        if (
            ''
            === $key
            || !class_exists($class)
            || !is_a($class, Module::class, true)
        ) {
            return null;
        }

        if (isset($this->modules[$key])) {
            return $this->modules[$key];
        }

        $module = $this->container->make($class);

        if (!$module instanceof Module) {
            return null;
        }

        $this->modules[$key] = $module;

        $this->container->set(
            $class,
            $module
        );

        $module->register();

        return $module;
    }
}