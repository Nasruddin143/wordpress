<?php
/**
 * Theme Asset Module.
 *
 * Handles WooShop frontend, editor, and admin assets.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined('ABSPATH') || exit;

/**
 * Theme asset module.
 */
final class Asset extends Module
{

    /**
     * Asset configuration.
     *
     * @var array<string, mixed>
     */
    private array $config = array();

    /**
     * Theme directory path.
     *
     * @var string
     */
    private string $theme_path;

    /**
     * Theme directory URI.
     *
     * @var string
     */
    private string $theme_uri;

    /**
     * Constructor.
     *
     * @param ModuleManager $manager Module manager.
     */
    public function __construct(ModuleManager $manager)
    {
        parent::__construct($manager);

        $this->theme_path = get_template_directory();
        $this->theme_uri = get_template_directory_uri();

        $config = require $this->theme_path . '/inc/Config/assets.php';

        if (is_array($config)) {
            $this->config = $config;
        }
    }

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {

        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend'), 20);

        add_action('enqueue_block_editor_assets', array($this, 'enqueue_editor'), 20);
    }

    /**
     * Enqueue frontend assets.
     *
     * @return void
     */
    public function enqueue_frontend(): void
    {

        $this->register_styles();
        $this->register_scripts();

        /*
         * Core theme assets.
         */
        wp_enqueue_style('bootstrap');
        wp_enqueue_style('app');

        wp_enqueue_script('bootstrap');
        wp_enqueue_script('app');

        /*
         * Page-specific assets.
         */
        $this->enqueue_page_assets();

        /*
         * Component assets.
         */
        $this->enqueue_component_assets();

        /*
         * Native WordPress comment-reply.
         */
        $this->enqueue_comment_reply();
    }

    /**
     * Register all frontend styles.
     *
     * @return void
     */
    private function register_styles(): void
    {

        foreach ($this->config['styles'] ?? array() as $handle => $asset) {

            $this->register_style((string)$handle, (array)$asset);
        }

        foreach ($this->config['components'] ?? array() as $handle => $asset) {

            $this->register_style((string)$handle, (array)$asset);
        }

        foreach ($this->config['pages'] ?? array() as $handle => $asset) {

            $this->register_style((string)$handle, (array)$asset);
        }
    }

    /**
     * Register all frontend scripts.
     *
     * @return void
     */
    private function register_scripts(): void
    {

        foreach ($this->config['scripts'] ?? array() as $handle => $asset) {

            $this->register_script((string)$handle, (array)$asset);
        }

        foreach ($this->config['component_scripts'] ?? array() as $handle => $asset) {

            $this->register_script((string)$handle, (array)$asset);
        }

        foreach ($this->config['page_scripts'] ?? array() as $handle => $asset) {

            $this->register_script((string)$handle, (array)$asset);
        }
    }

    /**
     * Register a stylesheet.
     *
     * @param string $handle Asset handle.
     * @param array<string, mixed> $asset Asset configuration.
     *
     * @return void
     */
    private function register_style(string $handle, array $asset): void
    {

        $src = $this->get_asset_uri($asset);

        if ('' === $src) {
            return;
        }

        wp_register_style($handle, $src, $this->get_dependencies($asset), $this->get_version($asset), (string)($asset['media'] ?? 'all'));
    }

    /**
     * Register a script.
     *
     * @param string $handle Asset handle.
     * @param array<string, mixed> $asset Asset configuration.
     *
     * @return void
     */
    private function register_script(string $handle, array $asset): void
    {

        $src = $this->get_asset_uri($asset);

        if ('' === $src) {
            return;
        }

        wp_register_script($handle, $src, $this->get_dependencies($asset), $this->get_version($asset), (bool)($asset['in_footer'] ?? true));
    }

    /**
     * Enqueue page-specific assets.
     *
     * @return void
     */
    private function enqueue_page_assets(): void
    {

        $page = $this->get_page_context();

        if ('' === $page) {
            return;
        }

        if (isset($this->config['pages'][$page])) {
            wp_enqueue_style($page);
        }

        if (isset($this->config['page_scripts'][$page])) {
            wp_enqueue_script($page);
        }
    }

    /**
     * Enqueue component assets.
     *
     * @return void
     */
    private function enqueue_component_assets(): void
    {

        /*
         * Header.
         */
        $this->enqueue_component('header');

        /*
         * Branding.
         */
        $this->enqueue_component('branding');

        /*
         * Navigation.
         */
        if (has_nav_menu('primary')) {
            $this->enqueue_component('navigation');
        }
    }

    /**
     * Enqueue a component.
     *
     * @param string $component Component handle.
     *
     * @return void
     */
    private function enqueue_component(string $component): void
    {

        if (isset($this->config['components'][$component])) {
            wp_enqueue_style($component);
        }

        if (isset($this->config['component_scripts'][$component])) {
            wp_enqueue_script($component);
        }
    }

    /**
     * Enqueue native WordPress comment reply.
     *
     * @return void
     */
    private function enqueue_comment_reply(): void
    {

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * Enqueue block editor styles.
     *
     * @return void
     */
    public function enqueue_editor(): void
    {

        foreach ($this->config['editor']['styles'] ?? array() as $handle => $asset) {

            $this->register_style((string)$handle, (array)$asset);

            wp_enqueue_style((string)$handle);
        }
    }

    /**
     * Get page context.
     *
     * @return string
     */
    private function get_page_context(): string
    {

        if (is_front_page()) {
            return 'front-page';
        }

        if (is_home()) {
            return 'home';
        }

        if (is_search()) {
            return 'search';
        }

        if (is_404()) {
            return '404';
        }

        if (is_page()) {
            return 'page';
        }

        if (is_single()) {
            return 'single';
        }

        if (is_archive()) {
            return 'archive';
        }

        return '';
    }

    /**
     * Get asset URI.
     *
     * @param array<string, mixed> $asset Asset configuration.
     *
     * @return string
     */
    private function get_asset_uri(array $asset): string
    {

        if (empty($asset['src'])) {
            return '';
        }

        return trailingslashit($this->theme_uri)
            . ltrim((string)$asset['src'], '/');
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

        if (isset($asset['version']) && null !== $asset['version'] && '' !== $asset['version']) {
            return (string)$asset['version'];
        }

        if (empty($asset['src'])) {
            return wp_get_theme()->get('Version');
        }

        $file = $this->theme_path
            . '/'
            . ltrim((string)$asset['src'], '/');

        if (file_exists($file)) {
            return (string)filemtime($file);
        }

        return wp_get_theme()->get('Version');
    }

    /**
     * Get asset dependencies.
     *
     * @param array<string, mixed> $asset Asset configuration.
     *
     * @return array<int, string>
     */
    private function get_dependencies(array $asset): array
    {

        if (empty($asset['deps']) || !is_array($asset['deps'])) {
            return array();
        }

        return array_values(array_filter($asset['deps'], static fn(mixed $dependency): bool => is_string($dependency) && '' !== $dependency));
    }
}