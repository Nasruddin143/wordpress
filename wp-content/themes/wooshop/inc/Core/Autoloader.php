<?php
/**
 * WooShop Autoloader
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

/**
 * Class Autoloader
 */
final class Autoloader
{
    /**
     * WooShop namespace prefix.
     *
     * @var string
     */
    private const string NAMESPACE_PREFIX = 'WooShop\\';

    /**
     * WooShop include directory.
     *
     * @var string
     */
    private string $base_directory;

    /**
     * Constructor.
     *
     * @param string $base_directory WooShop include directory.
     */
    public function __construct(string $base_directory)
    {
        $this->base_directory = trailingslashit($base_directory);
    }

    /**
     * Register the autoloader.
     *
     * @return void
     */
    public function register(): void
    {
        spl_autoload_register([$this, 'autoload']);
    }

    /**
     * Autoload WooShop classes.
     *
     * @param string $class Fully qualified class name.
     *
     * @return void
     */
    public function autoload(string $class): void
    {
        if (!str_starts_with($class, self::NAMESPACE_PREFIX)) {
            return;
        }

        /*
         * Remove the WooShop namespace.
         *
         * Example:
         *
         * WooShop\Core\AssetsManager
         *
         * becomes:
         *
         * Core\AssetsManager
         */

        $relative_class = substr($class, strlen(self::NAMESPACE_PREFIX));

        /*
         * Convert namespace separators into directories.
         *
         * Core\AssetsManager
         *
         * becomes:
         *
         * Core/AssetsManager.php
         */

        $relative_file = str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

        /*
         * Build absolute file path.
         */

        $file = $this->base_directory . $relative_file;

        /*
         * Load class file.
         */

        if (is_readable($file)) {
            require_once $file;
        }
    }
}