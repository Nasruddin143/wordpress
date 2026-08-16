<?php
/**
 * WooShop Autoloader
 *
 * Provides PSR-4 style class autoloading for the WooShop theme.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined("ABSPATH") || exit();

/**
 * Class Autoloader.
 *
 * Automatically loads WooShop classes from the inc directory.
 */
final class Autoloader
{
    /**
     * WooShop namespace prefix.
     *
     * @var string
     */
    private const string PREFIX = "WooShop\\";

    /**
     * Base directory for WooShop classes.
     *
     * @var string
     */
    private const string BASE_DIR = __DIR__ . "/../";

    /**
     * Register the WooShop autoloader.
     *
     * @return void
     */
    public static function register(): void
    {
        spl_autoload_register([self::class, "autoload"]);
    }

    /**
     * Load a WooShop class file.
     *
     * Converts the fully-qualified class name into
     * the corresponding file path inside the inc directory.
     *
     * @param string $class Fully-qualified class name.
     * @return void
     */
    private static function autoload(string $class): void
    {
        if (strncmp($class, self::PREFIX, strlen(self::PREFIX)) !== 0) {
            return;
        }

        $relative_class = substr($class, strlen(self::PREFIX));

        if ("" === $relative_class) {
            return;
        }

        $file =
            self::BASE_DIR . str_replace("\\", "/", $relative_class) . ".php";

        if (is_readable($file)) {
            require_once $file;
        }
    }
}
