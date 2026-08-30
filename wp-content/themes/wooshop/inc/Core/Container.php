<?php
/**
 * WooShop Service Container
 *
 * @package WooShop
 */

namespace WooShop\Core;

use RuntimeException;

defined('ABSPATH') || exit;

/**
 * Class Container
 */
final class Container
{
    /**
     * Registered services.
     *
     * @var array<string, mixed>
     */
    private array $services = [];

    /**
     * Set a service.
     *
     * @param string $id      Service identifier.
     * @param mixed  $service Service instance/value.
     *
     * @return void
     */
    public function set(string $id, mixed $service): void
    {
        $this->services[$id] = $service;
    }

    /**
     * Determine whether a service exists.
     *
     * @param string $id Service identifier.
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }

    /**
     * Get a service.
     *
     * @template T
     *
     * @param string $id Service identifier.
     *
     * @return mixed
     */
    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            throw new RuntimeException(
                sprintf('WooShop service "%s" is not registered.', $id)
            );
        }

        return $this->services[$id];
    }

    /**
     * Remove a service.
     *
     * @param string $id Service identifier.
     *
     * @return void
     */
    public function remove(string $id): void
    {
        unset($this->services[$id]);
    }
}