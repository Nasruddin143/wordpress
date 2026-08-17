<?php
/**
 * WooShop Autoloader.
 *
 * Provides PSR-4 style class loading for the WooShop namespace.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined("ABSPATH") || exit();

/**
 * Handles WooShop class autoloading.
 */
class Autoloader
{
    /**
     * Namespace prefix.
     *
     * @var string
     */
    protected string $prefix = "WooShop\\";

    /**
     * Base directory for the namespace.
     *
     * @var string
     */
    protected string $base_dir;

    /**
     * Constructor.
     *
     * @param string $base_dir Base directory for WooShop classes.
     */
    public function __construct(string $base_dir)
    {
        $this->base_dir = trailingslashit($base_dir);
    }

    /**
     * Register the autoloader.
     *
     * @return void
     */
    public function register(): void
    {
        spl_autoload_register([$this, "load"]);
    }

    /**
     * Load a WooShop class.
     *
     * @param string $class Fully qualified class name.
     * @return void
     */
    public function load(string $class): void
    {
        if (!str_starts_with($class, $this->prefix)) {
            return;
        }

        $relative_class = substr($class, strlen($this->prefix));

        $file =
            $this->base_dir . str_replace("\\", "/", $relative_class) . ".php";

        if (file_exists($file)) {
            require_once $file;
        }
    }
}
