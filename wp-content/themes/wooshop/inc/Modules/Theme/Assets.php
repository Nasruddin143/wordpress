<?php
/**
 * Theme Asset Module.
 *
 * Handles registration and smart loading of theme styles and scripts.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined( 'ABSPATH' ) || exit;

/**
 * Theme asset manager.
 */
final class Asset extends Module {

    /**
     * Asset configuration.
     *
     * @var array<string, mixed>
     */
    private array $config;

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
    public function __construct( ModuleManager $manager ) {
        parent::__construct( $manager );

        $this->theme_path = get_template_directory();
        $this->theme_uri  = get_template_directory_uri();

        $config = require $this->theme_path . '/inc/Config/assets.php';

        $this->config = is_array( $config ) ? $config : array();
    }

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'wp_enqueue_scripts',
            array( $this, 'enqueue_frontend' ),
            20
        );

        add_action(
            'admin_enqueue_scripts',
            array( $this, 'enqueue_admin' ),
            20
        );

        add_action(
            'enqueue_block_editor_assets',
            array( $this, 'enqueue_editor' ),
            20
        );
    }

    /**
     * Enqueue frontend assets.
     *
     * @return void
     */
    public function enqueue_frontend(): void {

        $this->register_styles();
        $this->register_scripts();

        $this->enqueue_global_assets();
        $this->enqueue_page_assets();
        $this->enqueue_component_assets();
    }

    /**
     * Register configured styles.
     *
     * @return void
     */
    private function register_styles(): void {

        $styles = array();

        if ( isset( $this->config['styles'] ) ) {
            $styles = $this->config['styles'];
        }

        foreach ( $styles as $handle => $asset ) {
            $this->register_style(
                (string) $handle,
                $asset
            );
        }

        if ( isset( $this->config['components'] ) ) {

            foreach ( $this->config['components'] as $handle => $asset ) {
                $this->register_style(
                    (string) $handle,
                    $asset
                );
            }
        }

        if ( isset( $this->config['pages'] ) ) {

            foreach ( $this->config['pages'] as $handle => $asset ) {
                $this->register_style(
                    (string) $handle,
                    $asset
                );
            }
        }
    }

    /**
     * Register configured scripts.
     *
     * @return void
     */
    private function register_scripts(): void {

        if ( isset( $this->config['scripts'] ) ) {

            foreach ( $this->config['scripts'] as $handle => $asset ) {
                $this->register_script(
                    (string) $handle,
                    $asset
                );
            }
        }

        if ( isset( $this->config['component_scripts'] ) ) {

            foreach ( $this->config['component_scripts'] as $handle => $asset ) {
                $this->register_script(
                    (string) $handle,
                    $asset
                );
            }
        }

        if ( isset( $this->config['page_scripts'] ) ) {

            foreach ( $this->config['page_scripts'] as $handle => $asset ) {
                $this->register_script(
                    (string) $handle,
                    $asset
                );
            }
        }
    }

    /**
     * Register a stylesheet.
     *
     * @param string               $handle Asset handle.
     * @param array<string, mixed> $asset  Asset configuration.
     *
     * @return void
     */
    private function register_style(
        string $handle,
        array $asset
    ): void {

        if ( empty( $asset['src'] ) ) {
            return;
        }

        $src = $this->asset_uri( (string) $asset['src'] );

        wp_register_style(
            $handle,
            $src,
            $this->normalize_dependencies( $asset['deps'] ?? array() ),
            $this->asset_version( $asset ),
            (string) ( $asset['media'] ?? 'all' )
        );
    }

    /**
     * Register a script.
     *
     * @param string               $handle Asset handle.
     * @param array<string, mixed> $asset  Asset configuration.
     *
     * @return void
     */
    private function register_script(
        string $handle,
        array $asset
    ): void {

        if ( empty( $asset['src'] ) ) {
            return;
        }

        $src = $this->asset_uri( (string) $asset['src'] );

        wp_register_script(
            $handle,
            $src,
            $this->normalize_dependencies( $asset['deps'] ?? array() ),
            $this->asset_version( $asset ),
            (bool) ( $asset['in_footer'] ?? true )
        );
    }

    /**
     * Enqueue global assets.
     *
     * @return void
     */
    private function enqueue_global_assets(): void {

        if ( wp_style_is( 'bootstrap', 'registered' ) ) {
            wp_enqueue_style( 'bootstrap' );
        }

        if ( wp_style_is( 'app', 'registered' ) ) {
            wp_enqueue_style( 'app' );
        }

        if ( wp_script_is( 'app', 'registered' ) ) {
            wp_enqueue_script( 'app' );
        }
    }

    /**
     * Enqueue assets based on current page.
     *
     * @return void
     */
    private function enqueue_page_assets(): void {

        $page = $this->get_page_context();

        if ( '' === $page ) {
            return;
        }

        if (
            isset( $this->config['pages'][ $page ] )
            && wp_style_is( $page, 'registered' )
        ) {
            wp_enqueue_style( $page );
        }

        if (
            isset( $this->config['page_scripts'][ $page ] )
            && wp_script_is( $page, 'registered' )
        ) {
            wp_enqueue_script( $page );
        }
    }

    /**
     * Enqueue component assets.
     *
     * Components are intentionally opt-in.
     *
     * @return void
     */
    private function enqueue_component_assets(): void {

        /*
         * Header is used by the classic theme header.
         */
        $this->enqueue_component( 'header' );

        /*
         * Navigation is required when a primary navigation exists.
         */
        if ( has_nav_menu( 'primary' ) ) {
            $this->enqueue_component( 'navigation' );
        }
    }

    /**
     * Enqueue a component asset.
     *
     * @param string $component Component handle.
     *
     * @return void
     */
    public function enqueue_component( string $component ): void {

        if (
            isset( $this->config['components'][ $component ] )
            && wp_style_is( $component, 'registered' )
        ) {
            wp_enqueue_style( $component );
        }

        if (
            isset( $this->config['component_scripts'][ $component ] )
            && wp_script_is( $component, 'registered' )
        ) {
            wp_enqueue_script( $component );
        }
    }

    /**
     * Enqueue admin assets.
     *
     * @return void
     */
    public function enqueue_admin(): void {

        /*
         * Keep frontend assets out of wp-admin.
         *
         * Admin-specific assets can be added later without changing
         * the frontend asset pipeline.
         */
    }

    /**
     * Enqueue block editor assets.
     *
     * @return void
     */
    public function enqueue_editor(): void {

        if ( empty( $this->config['editor']['styles'] ) ) {
            return;
        }

        foreach ( $this->config['editor']['styles'] as $handle => $asset ) {

            $this->register_style(
                (string) $handle,
                $asset
            );

            wp_enqueue_style( (string) $handle );
        }
    }

    /**
     * Get current page context.
     *
     * @return string
     */
    private function get_page_context(): string {

        if ( is_front_page() ) {
            return 'front-page';
        }

        if ( is_home() ) {
            return 'home';
        }

        if ( is_search() ) {
            return 'search';
        }

        if ( is_404() ) {
            return '404';
        }

        if ( is_singular() ) {

            if ( is_page() ) {
                return 'page';
            }

            if ( is_single() ) {
                return 'single';
            }
        }

        if ( is_archive() ) {
            return 'archive';
        }

        return '';
    }

    /**
     * Build asset URI.
     *
     * @param string $path Relative asset path.
     *
     * @return string
     */
    private function asset_uri( string $path ): string {
        return trailingslashit( $this->theme_uri ) . ltrim( $path, '/' );
    }

    /**
     * Get asset version.
     *
     * Uses the configured version when provided; otherwise uses
     * the file modification time for cache busting during development.
     *
     * @param array<string, mixed> $asset Asset configuration.
     *
     * @return string
     */
    private function asset_version( array $asset ): string {

        if (
            isset( $asset['version'] )
            && null !== $asset['version']
            && '' !== $asset['version']
        ) {
            return (string) $asset['version'];
        }

        if ( empty( $asset['src'] ) ) {
            return wp_get_theme()->get( 'Version' );
        }

        $file = $this->theme_path . '/' . ltrim(
                (string) $asset['src'],
                '/'
            );

        if ( file_exists( $file ) ) {
            return (string) filemtime( $file );
        }

        return wp_get_theme()->get( 'Version' );
    }

    /**
     * Normalize dependency list.
     *
     * @param mixed $dependencies Dependencies.
     *
     * @return array<int, string>
     */
    private function normalize_dependencies( mixed $dependencies ): array {

        if ( ! is_array( $dependencies ) ) {
            return array();
        }

        return array_values(
            array_filter(
                $dependencies,
                static fn ( mixed $dependency ): bool =>
                    is_string( $dependency ) && '' !== $dependency
            )
        );
    }
}