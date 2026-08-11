<?php
/**
 * WooShop Asset Manager.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined( 'ABSPATH' ) || exit;

class Assets {

    /**
     * Asset configuration.
     *
     * @var array
     */
    protected array $config = [];

    /**
     * Build directory URI.
     *
     * @var string
     */
    protected string $uri;

    /**
     * Build directory path.
     *
     * @var string
     */
    protected string $path;

    /**
     * Constructor.
     */
    public function __construct() {

        $this->config = require get_template_directory()
            . '/inc/Config/assets.php';

        $this->uri = get_template_directory_uri()
            . '/assets/build';

        $this->path = get_template_directory()
            . '/assets/build';
    }

    /**
     * Register asset hooks.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'wp_enqueue_scripts',
            [ $this, 'enqueue_frontend' ],
            20
        );
    }

    /**
     * Enqueue frontend assets.
     *
     * @return void
     */
    public function enqueue_frontend(): void {

        /*
         * Always load the global WooShop bundle.
         */
        $this->enqueue_context( 'global' );

        /*
         * Load the page-specific bundle.
         */
        $context = Condition::asset_context();

        if ( 'global' !== $context ) {
            $this->enqueue_context( $context );
        }
    }

    /**
     * Enqueue a configured asset context.
     *
     * @param string $context Context name.
     * @return void
     */
    protected function enqueue_context( string $context ): void {

        if ( empty( $this->config[ $context ] ) ) {
            return;
        }

        foreach (
            $this->config[ $context ]['css'] ?? []
            as $handle
        ) {
            $this->enqueue_css( $handle );
        }

        foreach (
            $this->config[ $context ]['js'] ?? []
            as $handle
        ) {
            $this->enqueue_js( $handle );
        }
    }

    /**
     * Enqueue CSS.
     *
     * @param string $handle Asset name.
     * @return void
     */
    protected function enqueue_css( string $handle ): void {

        $file = $this->path
            . '/css/'
            . $handle
            . '.min.css';

        if ( ! file_exists( $file ) ) {
            return;
        }

        wp_enqueue_style(
            'wooshop-' . $handle,
            $this->uri . '/css/' . $handle . '.min.css',
            [],
            filemtime( $file )
        );
    }

    /**
     * Enqueue JavaScript.
     *
     * @param string $handle Asset name.
     * @return void
     */
    protected function enqueue_js( string $handle ): void {

        $file = $this->path
            . '/js/'
            . $handle
            . '.min.js';

        if ( ! file_exists( $file ) ) {
            return;
        }

        wp_enqueue_script(
            'wooshop-' . $handle,
            $this->uri . '/js/' . $handle . '.min.js',
            [],
            filemtime( $file ),
            [
                'in_footer' => true,
                'strategy'   => 'defer',
            ]
        );
    }
}