<?php
/**
 * WooShop Autoloader.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

/**
 * WooShop class autoloader.
 */
final class Autoloader
{

    /**
     * Namespace prefix.
     *
     * @var string
     */
    private const string PREFIX = 'WooShop\\';

    /**
     * Base directory.
     *
     * @var string
     */
    private const string BASE_DIRECTORY = 'inc/';

    /**
     * Register autoloader.
     *
     * @return void
     */
    public static function register(): void
    {
        spl_autoload_register(array(self::class, 'autoload'));
    }

    /**
     * Autoload class.
     *
     * @param string $class Fully qualified class name.
     *
     * @return void
     */
    public static function autoload(string $class): void
    {

        if (!str_starts_with($class, self::PREFIX)) {
            return;
        }

        $relative_class = substr($class, strlen(self::PREFIX));

        $file = get_template_directory() . '/' . self::BASE_DIRECTORY . str_replace('\\', '/', $relative_class) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
}