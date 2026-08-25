<?php
/**
 * WooShop Configuration Manager.
 *
 * Loads and caches theme configuration files.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

/**
 * Configuration manager.
 */
final class Config
{

    /**
     * Configuration cache.
     *
     * @var array<string, mixed>
     */
    private array $cache = array();

    /**
     * Theme configuration directory.
     *
     * @var string
     */
    private string $config_path;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->config_path = get_template_directory() . '/inc/Config';
    }

    /**
     * Get a configuration file.
     *
     * Example:
     *
     * $config->get( 'assets' );
     *
     * Loads:
     *
     * inc/Config/assets.php
     *
     * @param string $key Configuration key.
     * @param array<string,mixed> $default Default value.
     * @return array<string,mixed>
     */
    public function get(string $key, array $default = array()): array
    {
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $file = $this->config_path . '/' . $key . '.php';

        if (!is_file($file) || !is_readable($file)) {
            $this->cache[$key] = $default;

            return $default;
        }

        $config = require $file;

        if (!is_array($config)) {
            $config = $default;
        }

        $this->cache[$key] = $config;

        return $config;
    }

    /**
     * Check whether a configuration exists.
     *
     * @param string $key Configuration key.
     * @return bool
     */
    public function has(string $key): bool
    {
        if (isset($this->cache[$key])) {
            return true;
        }

        $file = $this->config_path . '/' . $key . '.php';

        return is_file($file) && is_readable($file);
    }

    /**
     * Clear configuration cache.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->cache = array();
    }
}