<?php
/**
 * WooShop Module Manager.
 *
 * Loads and registers configured WooShop modules.
 *
 * @package WooShop
 */

namespace WooShop\Core;

use ReflectionException;

defined( 'ABSPATH' ) || exit;

/**
 * Manages WooShop modules.
 */
class ModuleManager {

    /**
     * Service container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Registered module classes.
     *
     * @var array
     */
    protected array $modules = [];

    /**
     * Loaded module instances.
     *
     * @var array
     */
    protected array $instances = [];

    /**
     * Constructor.
     *
     * @param Container $container Service container.
     */
    public function __construct( Container $container ) {

        $this->container = $container;
    }

    /**
     * Add a module class.
     *
     * @param string $module Module class name.
     * @return void
     */
    public function add( string $module ): void {

        if ( ! in_array( $module, $this->modules, true ) ) {
            $this->modules[] = $module;
        }
    }

    /**
     * Add multiple module classes.
     *
     * @param array $modules Module class names.
     * @return void
     */
    public function add_many( array $modules ): void {

        foreach ( $modules as $module ) {
            $this->add( $module );
        }
    }

    /**
     * Register all configured modules.
     *
     * @return void
     * @throws ReflectionException
     */
    public function register(): void {

        foreach ( $this->modules as $module_class ) {

            if ( ! class_exists( $module_class ) ) {
                continue;
            }

            $module = $this->container->get(
                $module_class
            );

            if ( ! $module instanceof Module ) {
                continue;
            }

            $module->register();

            $this->instances[ $module_class ] = $module;
        }
    }

    /**
     * Retrieve a registered module instance.
     *
     * @param string $module Module class name.
     * @return Module|null
     */
    public function get( string $module ): ?Module {

        return $this->instances[ $module ] ?? null;
    }
}