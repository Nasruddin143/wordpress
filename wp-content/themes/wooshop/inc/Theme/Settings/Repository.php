<?php
/**
 * Theme Settings Repository
 *
 * @package WooShop
 */

namespace WooShop\Theme\Settings;

defined('ABSPATH') || exit;

class Repository
{

    /**
     * Cached settings.
     *
     * @var array|null
     */
    protected static ?array $cache = null;

    /**
     * Return all settings.
     *
     * @return array
     */
    public static function all(): array
    {

        if (null !== self::$cache) {
            return self::$cache;
        }

        $defaults = Defaults::all();

        foreach ($defaults as $key => $value) {
            $defaults[$key] = get_theme_mod($key, $value);
        }

        self::$cache = $defaults;

        return self::$cache;
    }

    /**
     * Get one setting.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {

        $settings = self::all();

        if (array_key_exists($key, $settings)) {
            return $settings[$key];
        }

        return $default;
    }

    /**
     * Check if setting exists.
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {

        return array_key_exists($key, self::all());
    }

    /**
     * Save setting.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set(string $key, $value): void
    {

        set_theme_mod($key, $value);

        self::$cache = null;
    }

    /**
     * Remove setting.
     *
     * @param string $key
     * @return void
     */
    public static function remove(string $key): void
    {

        remove_theme_mod($key);

        self::$cache = null;
    }

    /**
     * Reset all settings.
     *
     * @return void
     */
    public static function reset(): void
    {

        foreach (Defaults::all() as $key => $value) {
            remove_theme_mod($key);
        }

        self::$cache = null;
    }

    /**
     * Clear cache.
     *
     * @return void
     */
    public static function flush(): void
    {

        self::$cache = null;
    }
}