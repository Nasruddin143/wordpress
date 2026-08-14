<?php
/**
 * Theme Settings Manager
 *
 * @package WooShop
 */

namespace WooShop\Theme\Settings;

defined('ABSPATH') || exit;

class Manager
{

    /**
     * Get setting.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {

        return Repository::get($key, $default);
    }

    /**
     * Set setting.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set(string $key, mixed $value): void
    {

        Repository::set($key, $value);
    }

    /**
     * Return all settings.
     *
     * @return array
     */
    public static function all(): array
    {

        return Repository::all();
    }
}