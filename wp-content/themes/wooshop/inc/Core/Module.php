<?php
/**
 * WooShop Base Module.
 *
 * Provides the common foundation for all WooShop modules.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Base class for WooShop modules.
 */
abstract class Module {

    /**
     * Service container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Constructor.
     *
     * @param Container $container Service container.
     */
    public function __construct( Container $container ) {

        $this->container = $container;
    }

    /**
     * Register module functionality.
     *
     * Every module must implement its own hooks.
     *
     * @return void
     */
    abstract public function register(): void;

    /**
     * Retrieve the service container.
     *
     * @return Container
     */
    protected function container(): Container {

        return $this->container;
    }
}