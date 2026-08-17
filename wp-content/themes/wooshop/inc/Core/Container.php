<?php
/**
 * WooShop Service Container.
 *
 * Handles service registration, shared instances,
 * and automatic constructor dependency resolution.
 *
 * @package WooShop
 */

namespace WooShop\Core;

use ReflectionClass;
use ReflectionException;
use RuntimeException;

defined("ABSPATH") || exit();

/**
 * WooShop dependency injection container.
 */
class Container
{
    /**
     * Service bindings.
     *
     * @var array
     */
    protected array $bindings = [];

    /**
     * Shared service instances.
     *
     * @var array
     */
    protected array $instances = [];

    /**
     * Register a service factory.
     *
     * @param string   $abstract Service class or identifier.
     * @param callable $factory  Service factory.
     * @param bool     $shared   Whether the service is shared.
     * @return void
     */
    public function bind(
        string $abstract,
        callable $factory,
        bool $shared = true
    ): void {
        $this->bindings[$abstract] = [
            "factory" => $factory,
            "shared" => $shared,
        ];
    }

    /**
     * Register an existing service instance.
     *
     * @param string $abstract Service class or identifier.
     * @param mixed  $instance Service instance.
     * @return void
     */
    public function instance(string $abstract, mixed $instance): void
    {
        $this->instances[$abstract] = $instance;
    }

    /**
     * Determine whether a service is registered.
     *
     * @param string $abstract Service class or identifier.
     * @return bool
     */
    public function has(string $abstract): bool
    {
        return isset($this->bindings[$abstract]) ||
            isset($this->instances[$abstract]);
    }

    /**
     * Resolve a service.
     *
     * @param string $abstract Service class name.
     * @return mixed
     * @throws ReflectionException
     */
    public function get(string $abstract): mixed
    {
        /**
         * The container resolves itself automatically.
         */
        if (self::class === $abstract) {
            return $this;
        }

        /**
         * Return an existing shared instance.
         */
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        /**
         * Resolve an explicitly registered binding.
         */
        if (isset($this->bindings[$abstract])) {
            $binding = $this->bindings[$abstract];

            $instance = call_user_func($binding["factory"], $this);

            if ($binding["shared"]) {
                $this->instances[$abstract] = $instance;
            }

            return $instance;
        }

        /**
         * Resolve a class automatically.
         */
        return $this->make($abstract);
    }

    /**
     * Create a class and resolve its constructor dependencies.
     *
     * @param string $class Class name.
     * @return object
     * @throws ReflectionException
     */
    public function make(string $class): object
    {
        if (!class_exists($class)) {
            throw new RuntimeException(
                sprintf('WooShop class "%s" does not exist.', $class)
            );
        }

        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new RuntimeException(
                sprintf('WooShop class "%s" cannot be instantiated.', $class)
            );
        }

        $constructor = $reflection->getConstructor();

        /**
         * Classes without constructors can be created directly.
         */
        if (null === $constructor) {
            return $reflection->newInstance();
        }

        $arguments = [];

        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();

            /**
             * Dependencies must have a class/interface type.
             */
            if (!$type || $type->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $arguments[] = $parameter->getDefaultValue();
                    continue;
                }

                throw new RuntimeException(
                    sprintf(
                        'Unable to resolve dependency "%s" for "%s".',
                        $parameter->getName(),
                        $class
                    )
                );
            }

            /**
             * Resolve the named dependency.
             */
            $dependency = $type->getName();

            /**
             * Container is always the current container instance.
             */
            if (self::class === $dependency) {
                $arguments[] = $this;
                continue;
            }

            $arguments[] = $this->get($dependency);
        }

        return $reflection->newInstanceArgs($arguments);
    }
}
