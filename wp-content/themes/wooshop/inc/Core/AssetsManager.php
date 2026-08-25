<?php
/**
 * WooShop Assets Manager
 *
 * Condition-based smart asset loading.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined('ABSPATH') || exit;

/**
 * Class AssetsManager
 */
final class AssetsManager
{
    /**
     * Service container.
     *
     * @var Container
     */
    private Container $container;

    /**
     * Asset configuration.
     *
     * @var array<string, mixed>
     */
    private array $config;

    /**
     * Condition configuration.
     *
     * @var array<string, mixed>
     */
    private array $conditions;

    /**
     * Theme URI.
     *
     * @var string
     */
    private string $theme_uri;

    /**
     * Theme directory.
     *
     * @var string
     */
    private string $theme_directory;

    /**
     * Constructor.
     *
     * @param Container $container Service container.
     * @param array<string, mixed> $config Asset configuration.
     * @param array<string, mixed> $conditions Condition configuration.
     */
    public function __construct(Container $container, array $config, array $conditions)
    {
        $this->container = $container;
        $this->config = $config;
        $this->conditions = $conditions;
        $this->theme_uri = get_template_directory_uri();
        $this->theme_directory = get_template_directory();
    }

    /**
     * Register asset hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue'], 20);
    }

    /**
     * Enqueue matching assets.
     *
     * @return void
     */
    public function enqueue(): void
    {
        if (is_admin()) {
            return;
        }

        $styles = $this->config['styles'] ?? [];
        $scripts = $this->config['scripts'] ?? [];

        if (is_array($styles)) {
            foreach ($styles as $handle => $asset) {
                $this->process_style((string)$handle, $asset);
            }
        }

        if (is_array($scripts)) {
            foreach ($scripts as $handle => $asset) {
                $this->process_script((string)$handle, $asset);
            }
        }
    }

    /**
     * Process a stylesheet.
     *
     * @param string $handle Asset handle.
     * @param array<string, mixed> $asset Asset configuration.
     *
     * @return void
     */
    private function process_style(string $handle, array $asset): void
    {
        if (!$this->should_load($asset)) {
            return;
        }

        $src = $this->resolve_asset_url($asset);

        if ($src === '') {
            return;
        }

        wp_enqueue_style($handle, $src, $this->normalize_dependencies($asset['deps'] ?? []), $this->get_version($asset), $asset['media'] ?? 'all');
    }

    /**
     * Process a JavaScript asset.
     *
     * @param string $handle Asset handle.
     * @param array<string, mixed> $asset Asset configuration.
     *
     * @return void
     */
    private function process_script(string $handle, array $asset): void
    {
        if (!$this->should_load($asset)) {
            return;
        }

        $src = $this->resolve_asset_url($asset);

        if ($src === '') {
            return;
        }

        wp_enqueue_script($handle, $src, $this->normalize_dependencies($asset['deps'] ?? []), $this->get_version($asset),
            [
                'in_footer' => $asset['in_footer'] ?? true,
                'strategy' => $asset['strategy'] ?? 'defer',
            ]
        );
    }

    /**
     * Determine whether an asset should load.
     *
     * @param array<string, mixed> $asset Asset configuration.
     *
     * @return bool
     */
    private function should_load(array $asset): bool
    {
        if (($asset['enabled'] ?? true) !== true) {
            return false;
        }

        $condition = $asset['condition'] ?? null;

        if ($condition === null) {
            return true;
        }

        if (is_array($condition)) {
            foreach ($condition as $condition_name) {
                if (is_string($condition_name) && $this->check_condition($condition_name)) {
                    return true;
                }
            }

            return false;
        }

        if (is_string($condition)) {
            return $this->check_condition($condition);
        }

        return false;
    }

    /**
     * Check a named condition.
     *
     * @param string $name Condition name.
     *
     * @return bool
     */
    private function check_condition(string $name): bool
    {
        $condition = $this->conditions[$name] ?? null;

        if ($condition === null) {
            return false;
        }

        if (is_callable($condition)) {
            return (bool)call_user_func($condition);
        }

        return false;
    }

    /**
     * Resolve asset URL.
     *
     * @param array<string, mixed> $asset Asset configuration.
     *
     * @return string
     */
    private function resolve_asset_url(array $asset): string
    {
        if (!empty($asset['src'])) {
            return esc_url_raw((string)$asset['src']);
        }

        $path = $asset['path'] ?? '';

        if (!is_string($path) || $path === '') {
            return '';
        }

        $file = $this->theme_directory . '/' . ltrim($path, '/');

        if (!file_exists($file)) {
            return '';
        }

        return $this->theme_uri . '/' . ltrim($path, '/');
    }

    /**
     * Get asset version.
     *
     * @param array<string, mixed> $asset Asset configuration.
     *
     * @return string
     */
    private function get_version(array $asset): string
    {
        if (isset($asset['version']) && is_scalar($asset['version'])) {
            return (string)$asset['version'];
        }

        $path = $asset['path'] ?? '';

        if (is_string($path) && $path !== '') {
            $file = $this->theme_directory . '/' . ltrim($path, '/');

            if (file_exists($file)) {
                return (string)filemtime($file);
            }
        }

        return wp_get_theme()->get('Version');
    }

    /**
     * Normalize dependencies.
     *
     * @param mixed $dependencies Dependencies.
     *
     * @return array<int, string>
     */
    private function normalize_dependencies(mixed $dependencies): array
    {
        if (!is_array($dependencies)) {
            return [];
        }

        return array_values(array_filter($dependencies, static fn(mixed $dependency): bool => is_string($dependency) && $dependency !== ''));
    }
}