<?php
/**
 * WooShop Service Container
 *
 * Stores and resolves shared application services.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Container class.
 */
class Container {

    /**
     * Registered services.
     *
     * @var array
     */
    protected array $services = array();

    /**
     * Register a service.
     *
     * @param string $id      Service identifier.
     * @param mixed  $service Service instance.
     * @return void
     */
    public function set(string $id, mixed $service ): void {

        $this->services[ $id ] = $service;
    }

    /**
     * Retrieve a service.
     *
     * @param string $id Service identifier.
     * @return mixed|null
     */
    public function get( string $id ): mixed
    {

        return $this->services[ $id ] ?? null;
    }

    /**
     * Determine whether a service exists.
     *
     * @param string $id Service identifier.
     * @return bool
     */
    public function has( string $id ): bool {

        return isset( $this->services[ $id ] );
    }

    /**
     * Remove a registered service.
     *
     * @param string $id Service identifier.
     * @return void
     */
    public function remove( string $id ): void {

        unset( $this->services[ $id ] );
    }

    /**
     * Get all registered services.
     *
     * @return array
     */
    public function all(): array {

        return $this->services;
    }
}