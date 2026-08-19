<?php
/**
 * WooShop Base Module
 *
 * Provides the common foundation for all WooShop modules.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Abstract Class Module
 *
 * Base class inherited by Theme and WooCommerce modules.
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
     * @param Container $container WooShop service container.
     */
    public function __construct( Container $container ) {

        $this->container = $container;

    }

    /**
     * Register module functionality.
     *
     * Child modules must implement their hooks and functionality here.
     *
     * @return void
     */
    abstract public function register(): void;

    /**
     * Get the service container.
     *
     * @return Container
     */
    protected function container(): Container {

        return $this->container;

    }

}