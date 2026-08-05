<?php
/**
 * Application
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

class Application
{

    /**
     * Container instance.
     *
     * @var Container|null
     */
    protected static ?Container $container = null;

    /**
     * Set container.
     */
    public static function set_container(Container $container): void
    {

        self::$container = $container;
    }

    /**
     * Get container.
     */
    public static function container(): Container
    {

        return self::$container;
    }
}