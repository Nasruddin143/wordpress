<?php
/**
 * WooShop Configuration Manager
 *
 * Provides centralized, cached access to WooShop configuration
 * files stored inside the theme's inc/Config directory.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Core;

defined('ABSPATH') || exit;

/**
 * Class Config
 *
 * Loads and caches WooShop configuration arrays.
 */
final class Config {

    /**
     * Cached configuration data.
     *
     * @var array<string, array<string, mixed>>
     */
    private array $cache = array();

    /**
     * Configuration directory.
     *
     * @var string
     */
    private readonly string $directory;

    /**
     * Constructor.
     *
     * @param string|null $directory Optional configuration directory.
     */
    public function __construct(?string $directory = null) {

        $this->directory = trailingslashit(
            $directory ?? get_theme_file_path('inc/Config')
        );
    }

    /**
     * Retrieve a configuration file.
     *
     * Configuration files are loaded only once per request.
     *
     * @param string $name Configuration filename without extension.
     *
     * @return array<string, mixed>
     */
    public function get(string $name): array {

        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        $file = $this->get_file_path($name);

        if (!is_file($file)) {
            $this->cache[$name] = array();

            return array();
        }

        $config = require $file;

        if (!is_array($config)) {
            $config = array();
        }

        $this->cache[$name] = $config;

        return $config;
    }

    /**
     * Retrieve a nested configuration value.
     *
     * Example:
     *
     * $config->get_value(
     *     'assets',
     *     'styles.bootstrap.src'
     * );
     *
     * @param string $name    Configuration filename.
     * @param string $key     Dot-separated configuration path.
     * @param mixed  $default Default value.
     *
     * @return mixed
     */
    public function get_value(
        string $name,
        string $key,
        mixed $default = null
    ): mixed {

        $config = $this->get($name);

        if ('' === $key) {
            return $config;
        }

        $value = $config;

        foreach (explode('.', $key) as $segment) {

            if (
                !is_array($value)
                || !array_key_exists($segment, $value)
            ) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }

    /**
     * Determine whether a configuration file exists.
     *
     * @param string $name Configuration filename.
     *
     * @return bool
     */
    public function exists(string $name): bool {

        return is_file(
            $this->get_file_path($name)
        );
    }

    /**
     * Determine whether a configuration value exists.
     *
     * @param string $name Configuration filename.
     * @param string $key  Dot-separated configuration path.
     *
     * @return bool
     */
    public function has(string $name, string $key = ''): bool {

        if ('' === $key) {
            return $this->exists($name);
        }

        $config = $this->get($name);
        $value  = $config;

        foreach (explode('.', $key) as $segment) {

            if (
                !is_array($value)
                || !array_key_exists($segment, $value)
            ) {
                return false;
            }

            $value = $value[$segment];
        }

        return true;
    }

    /**
     * Clear cached configuration.
     *
     * @param string|null $name Configuration filename.
     *
     * @return void
     */
    public function clear(?string $name = null): void {

        if (null === $name) {
            $this->cache = array();

            return;
        }

        unset($this->cache[$name]);
    }

    /**
     * Get the configuration directory.
     *
     * @return string
     */
    public function get_directory(): string {

        return $this->directory;
    }

    /**
     * Build a configuration file path.
     *
     * @param string $name Configuration filename.
     *
     * @return string
     */
    private function get_file_path(string $name): string {

        $name = sanitize_file_name($name);

        return $this->directory . $name . '.php';
    }
}