<?php
/**
 * PSR-4 Autoloader
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

class Autoloader
{

    /**
     * Namespace prefix.
     */
    protected const string PREFIX = 'WooShop\\';

    /**
     * Base directory.
     */
    protected static string $base_dir;

    /**
     * Register autoloader.
     */
    public static function register(): void
    {

        self::$base_dir = trailingslashit(get_template_directory()) . 'inc/';

        spl_autoload_register([self::class, 'autoload']);
    }

    /**
     * Load class.
     */
    protected static function autoload(string $class): void
    {

        if (!str_starts_with($class, self::PREFIX)) {
            return;
        }

        $relative = substr($class, strlen(self::PREFIX));

        $file = self::$base_dir .
            str_replace('\\', DIRECTORY_SEPARATOR, $relative) .
            '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
}