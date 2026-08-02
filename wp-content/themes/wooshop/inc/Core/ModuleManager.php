<?php
/**
 * Module Manager
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

class ModuleManager
{

    /**
     * Container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Loaded modules.
     *
     * @var Module[]
     */
    protected array $modules = [];

    /**
     * Constructor.
     *
     * @param Container $container Service container.
     */
    public function __construct(Container $container)
    {

        $this->container = $container;
    }

    /**
     * Register module.
     *
     * @param string $class Module class.
     */
    public function register(string $class): void
    {

        if (!class_exists($class)) {
            return;
        }

        $module = new $class(
            $this->container
        );

        $this->modules[] = $module;

        $this->container->set(
            $class,
            $module
        );

        $module->register();
    }

    /**
     * Load configured modules.
     */
    public function load(): void
    {

        $modules = require get_template_directory() . '/inc/Config/modules.php';

        if (empty($modules) || !is_array($modules)) {
            return;
        }

        foreach ($modules as $class) {

            $this->register($class);
        }
    }
}