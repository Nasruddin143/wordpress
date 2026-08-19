<?php
/**
 * WooShop Assets Manager
 *
 * Centralized asset registration and conditional loading system
 * for WooShop frontend, components, pages, WooCommerce, admin,
 * and block editor assets.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Core;

defined('ABSPATH') || exit;

/**
 * Class AssetsManager
 *
 * Handles registration and conditional enqueueing of theme assets.
 */
final class AssetsManager {

    /**
     * Theme filesystem path.
     *
     * @var string
     */
    private readonly string $path;

    /**
     * Theme URI.
     *
     * @var string
     */
    private readonly string $uri;

    /**
     * Configuration manager.
     *
     * @var Config
     */
    private readonly Config $config;

    /**
     * Registered styles.
     *
     * @var array<string, array<string, mixed>>
     */
    private array $styles = array();

    /**
     * Registered scripts.
     *
     * @var array<string, array<string, mixed>>
     */
    private array $scripts = array();

    /**
     * Constructor.
     *
     * @param Config $config WooShop configuration manager.
     */
    public function __construct(Config $config) {

        $this->config = $config;

        $this->path = trailingslashit(
            get_stylesheet_directory()
        );

        $this->uri = trailingslashit(
            get_stylesheet_directory_uri()
        );
    }

    /**
     * Register the asset manager hooks.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'wp_enqueue_scripts',
            [$this, 'enqueue_frontend'],
            20
        );

        add_action(
            'admin_enqueue_scripts',
            [$this, 'enqueue_admin'],
            20
        );

        add_action(
            'enqueue_block_editor_assets',
            [$this, 'enqueue_editor'],
            20
        );
    }

    /**
     * Enqueue frontend assets.
     *
     * @return void
     */
    public function enqueue_frontend(): void {

        $assets = $this->config->get('assets');

        $conditions = $this->config->get('conditions');

        $this->load_group(
            $assets['frontend'] ?? array(),
            $conditions
        );

        $this->load_group(
            $assets['components'] ?? array(),
            $conditions
        );

        $this->load_group(
            $assets['pages'] ?? array(),
            $conditions
        );

        /*
         * WooCommerce assets are loaded only when WooCommerce
         * is active and the configured conditions pass.
         */
        if (class_exists('WooCommerce')) {

            $this->load_group(
                $assets['woocommerce'] ?? array(),
                $conditions
            );
        }
    }

    /**
     * Enqueue admin assets.
     *
     * @return void
     */
    public function enqueue_admin(): void {

        $assets = $this->config->get('assets');

        $conditions = $this->config->get('conditions');

        $this->load_group(
            $assets['admin'] ?? array(),
            $conditions
        );
    }

    /**
     * Enqueue block editor assets.
     *
     * @return void
     */
    public function enqueue_editor(): void {

        $assets = $this->config->get('assets');

        $conditions = $this->config->get('conditions');

        $this->load_group(
            $assets['editor'] ?? array(),
            $conditions
        );
    }

    /**
     * Load one configured asset group.
     *
     * @param mixed $group       Asset group configuration.
     * @param mixed $conditions Global condition configuration.
     *
     * @return void
     */
    private function load_group(
        mixed $group,
        mixed $conditions
    ): void {

        if (!is_array($group)) {
            return;
        }

        if (!is_array($conditions)) {
            $conditions = array();
        }

        $this->load_styles(
            $group['styles'] ?? array(),
            $conditions
        );

        $this->load_scripts(
            $group['scripts'] ?? array(),
            $conditions
        );
    }

    /**
     * Load configured styles.
     *
     * @param mixed                $styles     Style configuration.
     * @param array<string, mixed> $conditions Global conditions.
     *
     * @return void
     */
    private function load_styles(
        mixed $styles,
        array $conditions
    ): void {

        if (!is_array($styles)) {
            return;
        }

        foreach ($styles as $handle => $args) {

            if (!is_string($handle) || !is_array($args)) {
                continue;
            }

            if (!$this->passes_conditions($args, $conditions)) {
                continue;
            }

            $this->register_style(
                $handle,
                $args
            );

            $this->enqueue_style(
                $handle
            );
        }
    }

    /**
     * Load configured scripts.
     *
     * @param mixed                $scripts    Script configuration.
     * @param array<string, mixed> $conditions Global conditions.
     *
     * @return void
     */
    private function load_scripts(
        mixed $scripts,
        array $conditions
    ): void {

        if (!is_array($scripts)) {
            return;
        }

        foreach ($scripts as $handle => $args) {

            if (!is_string($handle) || !is_array($args)) {
                continue;
            }

            if (!$this->passes_conditions($args, $conditions)) {
                continue;
            }

            $this->register_script(
                $handle,
                $args
            );

            $this->enqueue_script(
                $handle
            );
        }
    }

    /**
     * Register a stylesheet.
     *
     * @param string               $handle Asset handle.
     * @param array<string, mixed> $args   Asset configuration.
     *
     * @return void
     */
    public function register_style(
        string $handle,
        array $args
    ): void {

        $this->styles[$handle] = $args;

        wp_register_style(
            $handle,
            $this->resolve_uri(
                (string) ($args['src'] ?? '')
            ),
            $this->normalize_dependencies(
                $args['deps'] ?? array()
            ),
            $this->resolve_version($args),
            (string) ($args['media'] ?? 'all')
        );
    }

    /**
     * Register a JavaScript file.
     *
     * @param string               $handle Asset handle.
     * @param array<string, mixed> $args   Asset configuration.
     *
     * @return void
     */
    public function register_script(
        string $handle,
        array $args
    ): void {

        $this->scripts[$handle] = $args;

        wp_register_script(
            $handle,
            $this->resolve_uri(
                (string) ($args['src'] ?? '')
            ),
            $this->normalize_dependencies(
                $args['deps'] ?? array()
            ),
            $this->resolve_version($args),
            (bool) ($args['in_footer'] ?? true)
        );
    }

    /**
     * Enqueue a registered stylesheet.
     *
     * @param string $handle Asset handle.
     *
     * @return void
     */
    public function enqueue_style(string $handle): void {

        if (!isset($this->styles[$handle])) {
            return;
        }

        wp_enqueue_style($handle);
    }

    /**
     * Enqueue a registered script.
     *
     * @param string $handle Asset handle.
     *
     * @return void
     */
    public function enqueue_script(string $handle): void {

        if (!isset($this->scripts[$handle])) {
            return;
        }

        $args = $this->scripts[$handle];

        wp_enqueue_script($handle);

        $this->apply_script_strategy(
            $handle,
            $args
        );
    }

    /**
     * Evaluate asset conditions.
     *
     * Supported format:
     *
     * 'conditions' => array(
     *     'is_shop'    => true,
     *     'is_product' => false
     * )
     *
     * @param array<string, mixed> $asset     Asset configuration.
     * @param array<string, mixed> $conditions Condition definitions.
     *
     * @return bool
     */
    private function passes_conditions(
        array $asset,
        array $conditions
    ): bool {

        $rules = $asset['conditions'] ?? array();

        if ([] === $rules) {
            return true;
        }

        if (!is_array($rules)) {
            return true;
        }

        foreach ($rules as $condition => $expected) {

            if (!is_string($condition)) {
                continue;
            }

            $callback = $conditions[$condition] ?? null;

            if (!is_callable($callback)) {
                continue;
            }

            $actual = $this->evaluate_condition(
                $callback
            );

            if ((bool) $actual !== (bool) $expected) {
                return false;
            }
        }

        return true;
    }

    /**
     * Evaluate an asset condition callback.
     *
     * Supports WordPress function names, closures, and
     * other valid PHP callables.
     *
     * @param callable|string $callback Condition callback.
     *
     * @return bool
     */
    private function evaluate_condition(
        mixed $callback
    ): bool {

        if (!is_callable($callback)) {
            return false;
        }

        return (bool) call_user_func($callback);
    }

    /**
     * Resolve an asset URI.
     *
     * @param string $src Relative path or absolute URL.
     *
     * @return string
     */
    private function resolve_uri(string $src): string {

        if ('' === $src) {
            return '';
        }

        if (
            str_starts_with($src, 'http://')
            || str_starts_with($src, 'https://')
            || str_starts_with($src, '//')
        ) {
            return $src;
        }

        return $this->uri . ltrim(
                $src,
                '/'
            );
    }

    /**
     * Resolve an asset version.
     *
     * Uses the explicitly configured version when available.
     * Otherwise, uses the asset file modification timestamp.
     *
     * @param array<string, mixed> $args Asset configuration.
     *
     * @return string|false
     */
    private function resolve_version(
        array $args
    ): string|false {

        if (
            isset($args['version'])
            && null !== $args['version']
        ) {
            return (string) $args['version'];
        }

        $src = (string) ($args['src'] ?? '');

        if ('' === $src) {
            return false;
        }

        if (
            str_starts_with($src, 'http://')
            || str_starts_with($src, 'https://')
            || str_starts_with($src, '//')
        ) {
            return false;
        }

        $file = $this->path . ltrim(
                $src,
                '/'
            );

        if (!is_file($file)) {
            return false;
        }

        return (string) filemtime($file);
    }

    /**
     * Normalize asset dependencies.
     *
     * @param mixed $dependencies Dependency list.
     *
     * @return array<int, string>
     */
    private function normalize_dependencies(
        mixed $dependencies
    ): array {

        if (is_string($dependencies)) {
            return '' === $dependencies
                ? array()
                : array($dependencies);
        }

        if (!is_array($dependencies)) {
            return array();
        }

        return array_values(
            array_filter(
                $dependencies,
                static fn (mixed $dependency): bool =>
                    is_string($dependency)
                    && '' !== $dependency
            )
        );
    }

    /**
     * Apply JavaScript loading strategy.
     *
     * @param string               $handle Script handle.
     * @param array<string, mixed> $args   Script configuration.
     *
     * @return void
     */
    private function apply_script_strategy(
        string $handle,
        array $args
    ): void {

        $strategy = $args['strategy'] ?? null;

        if (
            !is_string($strategy)
            || !in_array(
                $strategy,
                array('async', 'defer'),
                true
            )
        ) {
            return;
        }

        wp_script_add_data(
            $handle,
            'strategy',
            $strategy
        );
    }

    /**
     * Get registered styles.
     *
     * @return array<string, array<string, mixed>>
     */
    public function get_styles(): array {

        return $this->styles;
    }

    /**
     * Get registered scripts.
     *
     * @return array<string, array<string, mixed>>
     */
    public function get_scripts(): array {

        return $this->scripts;
    }
}