<?php
/**
 * WooShop Base Module
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

/**
 * Abstract Class Module
 */
abstract class Module
{
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
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Register module hooks.
     *
     * @return void
     */
    abstract public function register(): void;
}