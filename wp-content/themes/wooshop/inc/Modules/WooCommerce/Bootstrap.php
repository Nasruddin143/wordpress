<?php
/**
 * WooCommerce Bootstrap
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

class Bootstrap extends Module {

    /**
     * Register WooCommerce.
     *
     * @return void
     */
    public function register(): void {

        if ( ! class_exists( '\WooCommerce' ) ) {
            return;
        }

        add_action(
            'after_setup_theme',
            [ $this, 'setup' ]
        );

        add_action(
            'init',
            [ $this, 'boot' ],
            1
        );
    }

    /**
     * Theme support.
     *
     * @return void
     */
    public function setup(): void {

        add_theme_support( 'woocommerce' );

        add_theme_support(
            'wc-product-gallery-zoom'
        );

        add_theme_support(
            'wc-product-gallery-lightbox'
        );

        add_theme_support(
            'wc-product-gallery-slider'
        );
    }

    /**
     * Boot WooCommerce layer.
     *
     * @return void
     */
    public function boot(): void {

        $this->load_helpers();

        $this->load_hooks();
    }

    /**
     * Load helper library.
     *
     * @return void
     */
    private function load_helpers(): void {

        require_once __DIR__ . '/Helpers/Loader.php';
    }

    /**
     * Load WooCommerce hooks.
     *
     * @return void
     */
    private function load_hooks(): void {

        require_once __DIR__ . '/Hooks.php';
    }
}