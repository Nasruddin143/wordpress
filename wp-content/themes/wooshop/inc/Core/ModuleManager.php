<?php
/**
 * WooShop Module Manager
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

/**
 * Class ModuleManager
 */
final class ModuleManager
{
    /**
     * Service container.
     *
     * @var Container
     */
    private Container $container;

    /**
     * Module configuration.
     *
     * @var array<string, mixed>
     */
    private array $config;

    /**
     * Loaded modules.
     *
     * @var array<string, Module>
     */
    private array $modules = [];

    /**
     * Constructor.
     *
     * @param Container $container Service container.
     * @param array<string, mixed> $config Module configuration.
     */
    public function __construct(Container $container, array $config)
    {
        $this->container = $container;
        $this->config = $config;
    }

    /**
     * Load all enabled modules.
     *
     * @return void
     */
    public function load(): void
    {
        foreach ($this->config as $group => $modules) {
            if (!is_array($modules)) {
                continue;
            }

            foreach ($modules as $module) {
                if (!is_array($module)) {
                    continue;
                }

                if (($module['enabled'] ?? true) !== true) {
                    continue;
                }

                $class = $module['class'] ?? '';

                if (
                    !is_string($class) ||
                    !class_exists($class)
                ) {
                    continue;
                }

                $instance = new $class($this->container);

                if (!$instance instanceof Module) {
                    continue;
                }

                $instance->register();

                $this->modules[$group . '.' . $class] = $instance;
            }
        }
    }

    /**
     * Get loaded modules.
     *
     * @return array<string, Module>
     */
    public function get_modules(): array
    {
        return $this->modules;
    }
}