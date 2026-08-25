<?php
/**
 * Configuration Manager.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

/**
 * Configuration Manager.
 */
final class Config
{

    /**
     * Loaded configuration.
     *
     * @var array<string, mixed>
     */
    private array $config = array();

    /**
     * Load a configuration file.
     *
     * @param string $key Configuration key.
     * @param array<string, mixed> $default Default value.
     * @return array<string, mixed>
     */
    public function get(string $key, array $default = array()): array
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        $file = get_template_directory() . '/inc/Config/' . $key . '.php';

        if (!file_exists($file)) {

            $this->config[$key] = $default;
            return $default;

        }

        $value = require $file;

        if (!is_array($value)) {

            $value = $default;

        }

        $this->config[$key] = $value;

        return $value;
    }
}