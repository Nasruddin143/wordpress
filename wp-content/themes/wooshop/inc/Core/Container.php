<?php
/**
 * WooShop Service Container
 *
 * Provides lightweight dependency injection and shared service
 * management for the WooShop theme architecture.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Core;

use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use RuntimeException;

defined('ABSPATH') || exit;

/**
 * Class Container
 *
 * Manages WooShop service instances and lazy factories.
 */
final class Container {

    /**
     * Registered service instances.
     *
     * @var array<string, object>
     */
    private array $services = array();

    /**
     * Registered lazy service factories.
     *
     * @var array<string, callable>
     */
    private array $factories = array();

    /**
     * Register an existing service instance.
     *
     * @param string $id      Service identifier.
     * @param object $service Service instance.
     *
     * @return void
     */
    public function set(string $id, object $service): void {

        $this->services[$id] = $service;
    }

    /**
     * Register a lazy service factory.
     *
     * @param string   $id      Service identifier.
     * @param callable $factory Factory callback.
     *
     * @return void
     */
    public function factory(string $id, callable $factory): void {

        $this->factories[$id] = $factory;
    }

    /**
     * Determine whether a service is registered.
     *
     * @param string $id Service identifier.
     *
     * @return bool
     */
    public function has(string $id): bool {

        return isset($this->services[$id])
            || isset($this->factories[$id]);
    }

    /**
     * Resolve a service from the container.
     *
     * Services are shared automatically after the first resolution.
     *
     * @template T of object
     *
     * @param class-string<T> $id Service class or identifier.
     *
     * @return T
     *
     * @throws RuntimeException When the service cannot be resolved.
     * @throws ReflectionException
     */
    public function get(string $id): object {

        if (isset($this->services[$id])) {
            return $this->services[$id];
        }

        if (isset($this->factories[$id])) {

            $service = ($this->factories[$id])($this);

            if (!is_object($service)) {
                throw new RuntimeException(
                    sprintf(
                        'WooShop factory "%s" must return an object.',
                        $id
                    )
                );
            }

            $this->services[$id] = $service;

            unset($this->factories[$id]);

            return $service;
        }

        if (!class_exists($id)) {
            throw new RuntimeException(
                sprintf(
                    'WooShop service "%s" does not exist.',
                    $id
                )
            );
        }

        $service = $this->make($id);

        $this->services[$id] = $service;

        return $service;
    }

    /**
     * Instantiate a class using constructor dependency injection.
     *
     * Dependencies are resolved recursively from the container.
     *
     * @template T of object
     *
     * @param class-string<T> $class Class name.
     *
     * @return T
     *
     * @throws RuntimeException|ReflectionException When the class cannot be instantiated.
     */
    public function make(string $class): object {

        if (!class_exists($class)) {
            throw new RuntimeException(
                sprintf(
                    'WooShop class "%s" does not exist.',
                    $class
                )
            );
        }

        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new RuntimeException(
                sprintf(
                    'WooShop class "%s" is not instantiable.',
                    $class
                )
            );
        }

        $constructor = $reflection->getConstructor();

        if (null === $constructor) {
            return $reflection->newInstance();
        }

        $arguments = array();

        foreach ($constructor->getParameters() as $parameter) {

            $type = $parameter->getType();

            if (!$type instanceof ReflectionNamedType) {

                if ($parameter->isDefaultValueAvailable()) {
                    $arguments[] = $parameter->getDefaultValue();
                    continue;
                }

                throw new RuntimeException(
                    sprintf(
                        'Unable to resolve untyped dependency "%s" in "%s".',
                        $parameter->getName(),
                        $class
                    )
                );
            }

            if ($type->isBuiltin()) {

                if ($parameter->isDefaultValueAvailable()) {
                    $arguments[] = $parameter->getDefaultValue();
                    continue;
                }

                throw new RuntimeException(
                    sprintf(
                        'Unable to resolve builtin dependency "%s" in "%s".',
                        $parameter->getName(),
                        $class
                    )
                );
            }

            $dependency = $type->getName();

            $arguments[] = $this->get($dependency);
        }

        return $reflection->newInstanceArgs($arguments);
    }

    /**
     * Remove a service and its factory.
     *
     * @param string $id Service identifier.
     *
     * @return void
     */
    public function remove(string $id): void {

        unset(
            $this->services[$id],
            $this->factories[$id]
        );
    }

    /**
     * Get all resolved services.
     *
     * @return array<string, object>
     */
    public function all(): array {

        return $this->services;
    }

    /**
     * Remove all services and factories.
     *
     * @return void
     */
    public function clear(): void {

        $this->services  = array();
        $this->factories = array();
    }
}