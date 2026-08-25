<?php
/**
 * WooShop Service Container.
 *
 * @package WooShop
 */

namespace WooShop\Core;

use RuntimeException;

defined('ABSPATH') || exit;

/**
 * Service container.
 */
final class Container
{

    /**
     * Registered services.
     *
     * @var array<string,object>
     */
    private array $services = array();

    /**
     * Register a service.
     *
     * @param string $id Service identifier.
     * @param object $service Service instance.
     * @return void
     */
    public function set(string $id, object $service): void
    {
        $this->services[$id] = $service;
    }

    /**
     * Determine whether a service exists.
     *
     * @param string $id Service identifier.
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    /**
     * Retrieve a service.
     *
     * @template T of object
     *
     * @param string $id Service identifier.
     * @return object
     *
     * @throws RuntimeException When service does not exist.
     */
    public function get(string $id): object
    {
        if (!isset($this->services[$id])) {
            throw new RuntimeException(sprintf( /* translators: %s: Service identifier. */ __('WooShop service "%s" is not registered.', 'wooshop'), $id));
        }

        return $this->services[$id];
    }

    /**
     * Remove a service.
     *
     * @param string $id Service identifier.
     * @return void
     */
    public function remove(string $id): void
    {
        unset($this->services[$id]);
    }

    /**
     * Get all registered services.
     *
     * @return array<string,object>
     */
    public function all(): array
    {
        return $this->services;
    }
}