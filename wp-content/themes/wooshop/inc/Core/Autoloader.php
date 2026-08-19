<?php
/**
 * WooShop PSR-4 Autoloader
 *
 * Provides lightweight PSR-4 class loading for the WooShop
 * namespace without relying on Composer.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Core;

defined('ABSPATH') || exit;

/**
 * Class Autoloader
 *
 * Loads WooShop classes automatically from the inc directory.
 */
final class Autoloader {

    /**
     * WooShop namespace prefix.
     *
     * @var string
     */
    private const PREFIX = 'WooShop\\';

    /**
     * Base directory for WooShop classes.
     *
     * @var string
     */
    private readonly string $base_directory;

    /**
     * Constructor.
     *
     * @param string|null $base_directory Optional base directory.
     */
    public function __construct(?string $base_directory = null) {

        $this->base_directory = trailingslashit(
            $base_directory ?? get_theme_file_path('inc')
        );
    }

    /**
     * Register the autoloader with PHP.
     *
     * @return void
     */
    public function register(): void {

        spl_autoload_register(
            [$this, 'autoload']
        );
    }

    /**
     * Unregister the autoloader.
     *
     * @return void
     */
    public function unregister(): void {

        spl_autoload_unregister(
            [$this, 'autoload']
        );
    }

    /**
     * Load a WooShop class.
     *
     * @param string $class Fully-qualified class name.
     *
     * @return void
     */
    public function autoload(string $class): void {

        if (!str_starts_with($class, self::PREFIX)) {
            return;
        }

        $relative_class = substr(
            $class,
            strlen(self::PREFIX)
        );

        if ('' === $relative_class) {
            return;
        }

        $file = $this->resolve_file($relative_class);

        if (null === $file || !is_file($file)) {
            return;
        }

        require_once $file;
    }

    /**
     * Resolve a class name to a PHP file.
     *
     * WooShop uses:
     *
     * WooShop\Core\Module
     *     ↓
     * inc/Core/Module.php
     *
     * WooShop\Modules\WooCommerce\Wishlist
     *     ↓
     * inc/Modules/WooCommerce/Wishlist.php
     *
     * @param string $relative_class Relative class name.
     *
     * @return string|null
     */
    private function resolve_file(string $relative_class): ?string {

        $parts = explode('\\', $relative_class);

        if ([] === $parts) {
            return null;
        }

        $file = $this->base_directory
            . implode(DIRECTORY_SEPARATOR, $parts)
            . '.php';

        return $file;
    }
}