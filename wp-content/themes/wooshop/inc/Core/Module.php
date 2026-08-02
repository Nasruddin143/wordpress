<?php
/**
 * Base Module
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

abstract class Module
{
    protected Container $container;

    final public function __construct(Container $container)
    {
        $this->container = $container;
    }

    abstract public function register(): void;
}