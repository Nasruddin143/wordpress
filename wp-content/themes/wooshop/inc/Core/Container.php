<?php
/**
 * Service Container
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined( 'ABSPATH' ) || exit;

class Container {

    /**
     * Registered services.
     *
     * @var array<string,mixed>
     */
    protected array $services = [];

    /**
     * Resolved services.
     *
     * @var array<string,object>
     */
    protected array $instances = [];

    /**
     * Register a service.
     *
     * @param string $id Service ID.
     * @param mixed  $service Object or Closure.
     *
     * @return void
     */
    public function set( string $id, $service ): void {

        $this->services[ $id ] = $service;
    }

    /**
     * Resolve a service.
     *
     * @param string $id Service ID.
     *
     * @return mixed|null
     */
    public function get( string $id ) {

        if ( isset( $this->instances[ $id ] ) ) {
            return $this->instances[ $id ];
        }

        if ( ! isset( $this->services[ $id ] ) ) {
            return null;
        }

        $service = $this->services[ $id ];

        if ( is_callable( $service ) ) {

            $service = $service( $this );

            $this->instances[ $id ] = $service;
        }

        return $service;
    }

    /**
     * Determine whether a service exists.
     *
     * @param string $id Service ID.
     *
     * @return bool
     */
    public function has( string $id ): bool {

        return isset( $this->services[ $id ] );
    }

    /**
     * Remove a service.
     *
     * @param string $id Service ID.
     *
     * @return void
     */
    public function forget( string $id ): void {

        unset(
            $this->services[ $id ],
            $this->instances[ $id ]
        );
    }
}