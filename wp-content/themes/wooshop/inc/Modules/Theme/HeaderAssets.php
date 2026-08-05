<?php
/**
 * Smart Asset Manager
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Config;
use WooShop\Core\Module;

defined('ABSPATH') || exit;

class Assets extends Module
{

    /**
     * Registered styles.
     *
     * @var array<string,array>
     */
    protected array $styles = [];

    /**
     * Registered scripts.
     *
     * @var array<string,array>
     */
    protected array $scripts = [];

    /**
     * Theme directory.
     *
     * @var string
     */
    protected string $theme_path;

    /**
     * Theme URI.
     *
     * @var string
     */
    protected string $theme_uri;

    /**
     * Register module.
     */
    public function register(): void
    {

        $this->theme_path = get_template_directory();

        $this->theme_uri = get_template_directory_uri();

        $this->register_configured_assets();

        add_action(
            'wp_enqueue_scripts',
            [$this, 'enqueue']
        );
    }

    /**
     * Register assets from configuration.
     */
    protected function register_configured_assets(): void
    {

        $config = $this->container->get(Config::class);

        if (!$config instanceof Config) {
            return;
        }

        $assets = $config->get('assets');

        if (empty($assets) || !is_array($assets)) {
            return;
        }

        /*
         * Global styles.
         */
        $this->register_group(
            $assets['global'] ?? []
        );

        /*
         * Front page.
         */
        $this->register_group(
            $assets['front_page'] ?? [],
            'front_page'
        );

        /*
         * Singular.
         */
        $this->register_group(
            $assets['singular'] ?? [],
            'singular'
        );

        /*
         * Archive.
         */
        $this->register_group(
            $assets['archive'] ?? [],
            'archive'
        );

        /*
         * Search.
         */
        $this->register_group(
            $assets['search'] ?? [],
            'search'
        );
    }

    /**
     * Register asset group.
     *
     * @param array $group Asset group.
     * @param string $context Optional context.
     */
    protected function register_group(
        array  $group,
        string $context = 'global'
    ): void
    {

        if (!empty($group['styles'])) {

            foreach ($group['styles'] as $handle => $asset) {

                $asset['context'] = $context;

                $this->add_style(
                    $handle,
                    $asset
                );
            }
        }

        if (!empty($group['scripts'])) {

            foreach ($group['scripts'] as $handle => $asset) {

                $asset['context'] = $context;

                $this->add_script(
                    $handle,
                    $asset
                );
            }
        }
    }

    /**
     * Register a stylesheet.
     *
     * @param string $handle Asset handle.
     * @param array $args Asset arguments.
     */
    public function add_style(
        string $handle,
        array  $args = []
    ): void
    {

        if (empty($args['file'])) {
            return;
        }

        $this->styles[$handle] = wp_parse_args(
            $args,
            [
                'file' => '',
                'dependencies' => [],
                'media' => 'all',
                'condition' => null,
                'context' => 'global',
            ]
        );
    }

    /**
     * Register a JavaScript file.
     *
     * @param string $handle Asset handle.
     * @param array $args Asset arguments.
     */
    public function add_script(
        string $handle,
        array  $args = []
    ): void
    {

        if (empty($args['file'])) {
            return;
        }

        $this->scripts[$handle] = wp_parse_args(
            $args,
            [
                'file' => '',
                'dependencies' => [],
                'strategy' => 'defer',
                'in_footer' => true,
                'condition' => null,
                'context' => 'global',
            ]
        );
    }

    /**
     * Enqueue registered assets.
     */
    public function enqueue(): void
    {

        foreach ($this->styles as $handle => $asset) {

            if (!$this->should_load($asset)) {
                continue;
            }

            $this->enqueue_style(
                $handle,
                $asset
            );
        }

        foreach ($this->scripts as $handle => $asset) {

            if (!$this->should_load($asset)) {
                continue;
            }

            $this->enqueue_script(
                $handle,
                $asset
            );
        }
    }

    /**
     * Enqueue stylesheet.
     *
     * @param string $handle Asset handle.
     * @param array $asset Asset configuration.
     */
    protected function enqueue_style(
        string $handle,
        array  $asset
    ): void
    {

        $file = $this->get_asset_file(
            $asset['file']
        );

        if (!$file || !file_exists($file)) {
            return;
        }

        wp_enqueue_style(
            $handle,
            $this->get_asset_url($asset['file']),
            $asset['dependencies'],
            $this->get_asset_version($file),
            $asset['media']
        );
    }

    /**
     * Enqueue JavaScript.
     *
     * @param string $handle Asset handle.
     * @param array $asset Asset configuration.
     */
    protected function enqueue_script(
        string $handle,
        array  $asset
    ): void
    {

        $file = $this->get_asset_file(
            $asset['file']
        );

        if (!$file || !file_exists($file)) {
            return;
        }

        $args = [
            'in_footer' => (bool)$asset['in_footer'],
        ];

        if (!empty($asset['strategy'])) {

            $args['strategy'] = $asset['strategy'];
        }

        wp_enqueue_script(
            $handle,
            $this->get_asset_url($asset['file']),
            $asset['dependencies'],
            $this->get_asset_version($file),
            $args
        );
    }

    /**
     * Determine whether an asset should load.
     *
     * @param array $asset Asset configuration.
     *
     * @return bool
     */
    protected function should_load(array $asset): bool
    {

        if (empty($asset['condition'])) {
            return true;
        }

        if (is_callable($asset['condition'])) {

            return (bool)call_user_func(
                $asset['condition']
            );
        }

        return true;
    }

    /**
     * Get absolute asset path.
     *
     * @param string $file Relative asset path.
     *
     * @return string
     */
    protected function get_asset_file(string $file): string
    {

        return trailingslashit($this->theme_path) .
            ltrim($file, '/');
    }

    /**
     * Get asset URL.
     *
     * @param string $file Relative asset path.
     *
     * @return string
     */
    protected function get_asset_url(string $file): string
    {

        return trailingslashit($this->theme_uri) .
            ltrim($file, '/');
    }

    /**
     * Get asset version.
     *
     * @param string $file Absolute file path.
     *
     * @return string
     */
    protected function get_asset_version(string $file): string
    {

        $modified = filemtime($file);

        if (false === $modified) {

            return wp_get_theme()->get('Version');
        }

        return (string)$modified;
    }

    /**
     * Get registered style.
     *
     * @param string $handle Asset handle.
     *
     * @return array|null
     */
    public function get_style(string $handle): ?array
    {

        return $this->styles[$handle] ?? null;
    }

    /**
     * Get registered script.
     *
     * @param string $handle Asset handle.
     *
     * @return array|null
     */
    public function get_script(string $handle): ?array
    {

        return $this->scripts[$handle] ?? null;
    }

    /**
     * Check whether style exists.
     *
     * @param string $handle Asset handle.
     */
    public function has_style(string $handle): bool
    {

        return isset($this->styles[$handle]);
    }

    /**
     * Check whether script exists.
     *
     * @param string $handle Asset handle.
     */
    public function has_script(string $handle): bool
    {

        return isset($this->scripts[$handle]);
    }
}