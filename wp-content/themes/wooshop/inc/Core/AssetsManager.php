<?php
/**
 * Asset Manager
 *
 * Handles registration and conditional loading of WooShop assets.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined( 'ABSPATH' ) || exit;

class AssetsManager {

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
     * Asset configuration.
     *
     * @var array
     */
    protected array $config = array();

    /**
     * Registered component assets.
     *
     * @var array
     */
    protected mixed $components = array();

    /**
     * Assets that have already been enqueued.
     *
     * @var array
     */
    protected array $loaded = array();

    /**
     * Constructor.
     *
     * @param array $config Asset configuration.
     */
    public function __construct( array $config = array() ) {

        $this->path = get_stylesheet_directory();
        $this->uri  = get_stylesheet_directory_uri();

        $this->config = $config;

        $this->components = $config['components'] ?? array();
    }

    /**
     * Enqueue global theme assets.
     *
     * @return void
     */
    public function enqueue_global(): void
    {

        if ( ! empty( $this->config['styles'] ) ) {

            foreach ( $this->config['styles'] as $handle => $asset ) {

                $this->enqueue_style(
                    $handle,
                    $asset
                );
            }
        }

        if ( ! empty( $this->config['scripts'] ) ) {

            foreach ( $this->config['scripts'] as $handle => $asset ) {

                $this->enqueue_script(
                    $handle,
                    $asset
                );
            }
        }
    }

    /**
     * Load a component asset group.
     *
     * @param string $component Component identifier.
     *
     * @return void
     */
    public function load_component(string $component ): void
    {

        if ( empty( $component ) ) {
            return;
        }

        if ( ! isset( $this->components[ $component ] ) ) {
            return;
        }

        if ( isset( $this->loaded[ $component ] ) ) {
            return;
        }

        $asset = $this->components[ $component ];

        if ( ! empty( $asset['css'] ) ) {

            $this->enqueue_style(
                $component,
                array(
                    'src'  => $asset['css'],
                    'deps' => array( 'wooshop-app' ),
                )
            );
        }

        if ( ! empty( $asset['js'] ) ) {

            $this->enqueue_script(
                $component,
                array(
                    'src'       => $asset['js'],
                    'deps'      => array( 'wooshop-app' ),
                    'in_footer' => true,
                )
            );
        }

        $this->loaded[ $component ] = true;
    }

    /**
     * Enqueue a WooCommerce asset group.
     *
     * @param string $key WooCommerce asset key (e.g. 'base', 'shop', 'product').
     *
     * @return void
     */
    public function enqueue_woocommerce( string $key ): void
    {

        if ( empty( $key ) ) {
            return;
        }

        $woocommerce = $this->config['woocommerce'] ?? array();

        if ( ! isset( $woocommerce[ $key ] ) ) {
            return;
        }

        if ( isset( $this->loaded[ 'woocommerce-' . $key ] ) ) {
            return;
        }

        $asset = $woocommerce[ $key ];

        if ( ! empty( $asset['css'] ) ) {

            $this->enqueue_style(
                'woocommerce-' . $key,
                array(
                    'src'  => $asset['css'],
                    'deps' => array( 'wooshop-app' ),
                )
            );
        }

        if ( ! empty( $asset['js'] ) ) {

            $this->enqueue_script(
                'woocommerce-' . $key,
                array(
                    'src'       => $asset['js'],
                    'deps'      => array( 'wooshop-app' ),
                    'in_footer' => true,
                )
            );
        }

        $this->loaded[ 'woocommerce-' . $key ] = true;
    }

    /**
     * Enqueue a stylesheet.
     *
     * @param string $handle Asset handle.
     * @param array  $asset  Asset configuration.
     *
     * @return void
     */
    protected function enqueue_style(string $handle, array $asset ): void
    {

        if ( empty( $asset['src'] ) ) {
            return;
        }

        $path = trailingslashit( $this->path ) . ltrim(
                $asset['src'],
                '/'
            );

        $uri = trailingslashit( $this->uri ) . ltrim(
                $asset['src'],
                '/'
            );

        if ( ! file_exists( $path ) ) {
            return;
        }

        $version = $asset['version'] ?? filemtime($path);

        $deps = array_map(
            static function ( $dep ) {
                return str_starts_with( $dep, 'wooshop-' ) ? $dep : 'wooshop-' . $dep;
            },
            $asset['deps'] ?? array()
        );

        $media = $asset['media'] ?? 'all';

        wp_enqueue_style(
            'wooshop-' . $handle,
            $uri,
            $deps,
            $version,
            $media
        );
    }

    /**
     * Enqueue a JavaScript file.
     *
     * @param string $handle Asset handle.
     * @param array  $asset  Asset configuration.
     *
     * @return void
     */
    protected function enqueue_script(string $handle, array $asset ): void
    {

        if ( empty( $asset['src'] ) ) {
            return;
        }

        $path = trailingslashit( $this->path ) . ltrim(
                $asset['src'],
                '/'
            );

        $uri = trailingslashit( $this->uri ) . ltrim(
                $asset['src'],
                '/'
            );

        if ( ! file_exists( $path ) ) {
            return;
        }

        $version = $asset['version'] ?? filemtime($path);

        $deps = array_map(
            static function ( $dep ) {
                return str_starts_with( $dep, 'wooshop-' ) ? $dep : 'wooshop-' . $dep;
            },
            $asset['deps'] ?? array()
        );

        $in_footer = !isset($asset['in_footer']) || $asset['in_footer'];

        wp_enqueue_script(
            'wooshop-' . $handle,
            $uri,
            $deps,
            $version,
            $in_footer
        );
    }

}