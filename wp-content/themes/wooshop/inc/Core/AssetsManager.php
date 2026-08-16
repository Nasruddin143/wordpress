<?php
/**
 * WooShop Assets Manager
 *
 * Centralized registration and enqueueing of global,
 * component, and WooCommerce assets.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined("ABSPATH") || exit();

/**
 * Class AssetsManager
 *
 * Manages all registered WooShop assets through a
 * single configuration structure.
 */
class AssetsManager
{
    /**
     * Theme filesystem path.
     *
     * @var string
     */
    protected string $path;

    /**
     * Theme URI.
     *
     * @var string
     */
    protected string $uri;

    /**
     * Global styles.
     *
     * @var array
     */
    protected array $styles = [];

    /**
     * Global scripts.
     *
     * @var array
     */
    protected array $scripts = [];

    /**
     * Component assets.
     *
     * @var array
     */
    protected array $components = [];

    /**
     * WooCommerce assets.
     *
     * @var array
     */
    protected array $woocommerce = [];

    /**
     * Constructor.
     *
     * Loads the centralized asset configuration.
     */
    public function __construct()
    {
        $this->path = get_stylesheet_directory();
        $this->uri = get_stylesheet_directory_uri();

        $this->load_config();
    }

    /**
     * Load asset configuration.
     *
     * @return void
     */
    protected function load_config(): void
    {
        $config_file = $this->path . "/inc/Config/assets.php";

        if (!file_exists($config_file)) {
            return;
        }

        $config = require $config_file;

        if (!is_array($config)) {
            return;
        }

        $this->styles = isset($config["styles"]) ? $config["styles"] : [];

        $this->scripts = isset($config["scripts"]) ? $config["scripts"] : [];

        $this->components = isset($config["components"])
            ? $config["components"]
            : [];

        $this->woocommerce = isset($config["woocommerce"])
            ? $config["woocommerce"]
            : [];
    }

    /**
     * Enqueue a global stylesheet.
     *
     * @param string $handle Asset handle.
     * @return void
     */
    public function enqueue_style(string $handle): void
    {
        if (empty($this->styles[$handle])) {
            return;
        }

        $asset = $this->styles[$handle];

        $this->enqueue_registered_style($handle, $asset);
    }

    /**
     * Enqueue a global script.
     *
     * @param string $handle Asset handle.
     * @return void
     */
    public function enqueue_script(string $handle): void
    {
        if (empty($this->scripts[$handle])) {
            return;
        }

        $asset = $this->scripts[$handle];

        $this->enqueue_registered_script($handle, $asset);
    }

    /**
     * Enqueue a WooCommerce asset.
     *
     * A single WooCommerce configuration entry may contain
     * both a stylesheet and a script.
     *
     * @param string $handle WooCommerce asset handle.
     * @return void
     */
    public function enqueue_woocommerce(string $handle): void
    {
        if (empty($this->woocommerce[$handle])) {
            return;
        }

        $asset = $this->woocommerce[$handle];

        $style_handle = "wooshop-woocommerce-" . $handle;
        $script_handle = "wooshop-woocommerce-" . $handle;

        /**
         * Register and enqueue stylesheet.
         */
        if (!empty($asset["style"])) {
            $this->enqueue_registered_style($style_handle, [
                "src" => $asset["style"],
                "style_deps" => isset($asset["style_deps"])
                    ? $asset["style_deps"]
                    : [],
                "version" => isset($asset["version"])
                    ? $asset["version"]
                    : null,
                "media" => isset($asset["media"]) ? $asset["media"] : "all",
            ]);
        }

        /**
         * Register and enqueue script.
         */
        if (!empty($asset["script"])) {
            $this->enqueue_registered_script($script_handle, [
                "src" => $asset["script"],
                "script_deps" => isset($asset["script_deps"])
                    ? $asset["script_deps"]
                    : [],
                "version" => isset($asset["version"])
                    ? $asset["version"]
                    : null,
                "in_footer" => isset($asset["in_footer"])
                    ? $asset["in_footer"]
                    : true,
            ]);
        }
    }

    /**
     * Enqueue a component asset.
     *
     * Components use the same unified structure as
     * WooCommerce assets.
     *
     * @param string $handle Component handle.
     * @return void
     */
    public function enqueue_component(string $handle): void
    {
        if (empty($this->components[$handle])) {
            return;
        }

        $asset = $this->components[$handle];

        $style_handle = "wooshop-component-" . $handle;
        $script_handle = "wooshop-component-" . $handle;

        /**
         * Register and enqueue component stylesheet.
         */
        if (!empty($asset["style"])) {
            $this->enqueue_registered_style($style_handle, [
                "src" => $asset["style"],
                "style_deps" => isset($asset["style_deps"])
                    ? $asset["style_deps"]
                    : [],
                "version" => isset($asset["version"])
                    ? $asset["version"]
                    : null,
                "media" => isset($asset["media"]) ? $asset["media"] : "all",
            ]);
        }

        /**
         * Register and enqueue component script.
         */
        if (!empty($asset["script"])) {
            $this->enqueue_registered_script($script_handle, [
                "src" => $asset["script"],
                "script_deps" => isset($asset["script_deps"])
                    ? $asset["script_deps"]
                    : [],
                "version" => isset($asset["version"])
                    ? $asset["version"]
                    : null,
                "in_footer" => isset($asset["in_footer"])
                    ? $asset["in_footer"]
                    : true,
            ]);
        }
    }

    /**
     * Register and enqueue a stylesheet.
     *
     * @param string $handle Asset handle.
     * @param array  $asset  Asset configuration.
     * @return void
     */
    protected function enqueue_registered_style(string $handle, array $asset): void
    {
        if (empty($asset["src"])) {
            return;
        }

        $dependencies = isset($asset["style_deps"]) ? $asset["style_deps"] : [];

        wp_enqueue_style(
            $handle,
            $this->resolve_uri($asset["src"]),
            $dependencies,
            $this->get_version($asset),
            isset($asset["media"]) ? $asset["media"] : "all"
        );
    }

    /**
     * Register and enqueue a script.
     *
     * @param string $handle Asset handle.
     * @param array  $asset  Asset configuration.
     * @return void
     */
    protected function enqueue_registered_script(string $handle, array $asset): void
    {
        if (empty($asset["src"])) {
            return;
        }

        $dependencies = isset($asset["script_deps"])
            ? $asset["script_deps"]
            : [];

        wp_enqueue_script(
            $handle,
            $this->resolve_uri($asset["src"]),
            $dependencies,
            $this->get_version($asset),
            isset($asset["in_footer"]) ? $asset["in_footer"] : true
        );
    }

    /**
     * Resolve an asset URI.
     *
     * @param string $src Relative asset path.
     * @return string
     */
    protected function resolve_uri(string $src): string
    {
        return trailingslashit($this->uri) . ltrim($src, "/");
    }

    /**
     * Get the asset version.
     *
     * Uses the configured version when available.
     * Otherwise, uses the asset file modification time.
     *
     * @param array $asset Asset configuration.
     * @return string|false
     */
    protected function get_version(array $asset): false|string
    {
        if (array_key_exists("version", $asset) && null !== $asset["version"]) {
            return $asset["version"];
        }

        if (empty($asset["src"])) {
            return false;
        }

        $file = $this->path . "/" . ltrim($asset["src"], "/");

        if (file_exists($file)) {
            return (string) filemtime($file);
        }

        return false;
    }
}
