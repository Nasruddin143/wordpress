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
 * Abstract Module class.
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
     * Register module hooks.
     *
     * Every WooShop module must implement this method.
     *
     * @return void
     */
    abstract public function register(): void;
}