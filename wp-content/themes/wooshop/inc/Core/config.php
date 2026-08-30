<?php
/**
 * WooShop Configuration Loader
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

/**
 * Class Config
 */
final class Config
{
    /**
     * Configuration directory.
     *
     * @var string
     */
    private string $directory;

    /**
     * Cached configuration.
     *
     * @var array<string, array<string, mixed>>
     */
    private array $cache = [];

    /**
     * Constructor.
     *
     * @param string $directory Configuration directory.
     */
    public function __construct(string $directory)
    {
        $this->directory = trailingslashit($directory);
    }

    /**
     * Get configuration.
     *
     * @param string $name Configuration name.
     *
     * @return array<string, mixed>
     */
    public function get(string $name): array
    {
        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        $file = $this->directory . $name . '.php';

        if (!is_readable($file)) {
            return [];
        }

        $config = require $file;

        if (!is_array($config)) {
            return [];
        }

        $this->cache[$name] = $config;

        return $config;
    }
}