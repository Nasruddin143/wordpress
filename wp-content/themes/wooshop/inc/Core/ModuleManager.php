<?php
/**
 * WooShop Module Manager
 *
 * Resolves, instantiates, registers, and manages WooShop modules.
 *
 * @package WooShop
 */

namespace WooShop\Core;

use ReflectionClass;
use ReflectionException;
use Throwable;

defined( 'ABSPATH' ) || exit;

/**
 * ModuleManager class.
 */
class ModuleManager {

    /**
     * Service container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Module instances.
     *
     * @var array
     */
    protected array $instances = array();

    /**
     * Constructor.
     *
     * @param Container $container Service container.
     */
    public function __construct( Container $container ) {

        $this->container = $container;
    }

    /**
     * Register modules from configuration.
     *
     * @param array $modules Module configuration.
     * @return void
     * @throws ReflectionException
     */
    public function register( array $modules ): void {

        foreach ( $modules as $group => $classes ) {

            if ( ! is_array( $classes ) ) {
                continue;
            }

            foreach ( $classes as $class ) {

                $this->load( $class );
            }
        }
    }

    /**
     * Load and register a module.
     *
     * @param string $class Module class.
     * @return object|null
     * @throws ReflectionException
     */
    public function load( string $class ): object|null
    {

        if ( isset( $this->instances[ $class ] ) ) {
            return $this->instances[ $class ];
        }

        if ( ! class_exists( $class ) ) {
            return null;
        }

        $module = $this->resolve( $class );

        if ( ! $module ) {
            return null;
        }

        $this->instances[ $class ] = $module;

        if ( $module instanceof Module ) {
            $module->register();
        }

        return $module;
    }

    /**
     * Resolve a module through constructor dependencies.
     *
     * @param string $class Module class.
     * @return object|null
     * @throws ReflectionException
     */
    protected function resolve( string $class ): ?object
    {

        try {

            $reflection = new ReflectionClass( $class );

        } catch ( ReflectionException $exception ) {

            return null;
        }

        if ( ! $reflection->isInstantiable() ) {
            return null;
        }

        $constructor = $reflection->getConstructor();

        if ( ! $constructor ) {
            return $reflection->newInstance();
        }

        $arguments = array();

        foreach ( $constructor->getParameters() as $parameter ) {

            $type = $parameter->getType();

            if ( ! $type || $type->isBuiltin() ) {

                if ( $parameter->isDefaultValueAvailable() ) {

                    $arguments[] = $parameter->getDefaultValue();

                    continue;
                }

                return null;
            }

            $dependency = $type->getName();

            if ( $this->container->has( $dependency ) ) {

                $arguments[] = $this->container->get(
                    $dependency
                );

                continue;
            }

            /*
             * Try the short service name.
             */
            try {

                $dependency_reflection = new ReflectionClass(
                    $dependency
                );

                $short_name = strtolower(
                    $dependency_reflection->getShortName()
                );

            } catch ( ReflectionException $exception ) {

                return null;
            }

            if ( ! $this->container->has( $short_name ) ) {
                return null;
            }

            $arguments[] = $this->container->get(
                $short_name
            );
        }

        try {

            return $reflection->newInstanceArgs( $arguments );

        } catch ( Throwable $exception ) {

            return null;
        }
    }

    /**
     * Get a loaded module.
     *
     * @param string $class Module class.
     * @return object|null
     */
    public function get( string $class ): ?object
    {

        return $this->instances[ $class ] ?? null;
    }

    /**
     * Check whether a module is loaded.
     *
     * @param string $class Module class.
     * @return bool
     */
    public function has( string $class ): bool {

        return isset( $this->instances[ $class ] );
    }

    /**
     * Get all loaded modules.
     *
     * @return array
     */
    public function all(): array {

        return $this->instances;
    }
}