<?php
/**
 * Theme Assets Module
 *
 * Registers WooShop global and conditional assets.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;


use WooShop\Core\AssetsManager;

defined( 'ABSPATH' ) || exit;

class Assets {

    /**
     * Asset manager.
     *
     * @var AssetsManager
     */
    protected AssetsManager $assets;

    /**
     * Constructor.
     *
     * @param AssetsManager $assets Asset manager instance.
     */
    public function __construct( AssetsManager $assets ) {

        $this->assets = $assets;
    }

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {

        add_action(
            'wp_enqueue_scripts',
            array( $this, 'enqueue' ),
            20
        );
    }

    /**
     * Enqueue theme assets.
     *
     * @return void
     */
    public function enqueue(): void
    {

        $this->assets->enqueue_global();

        $this->load_components();
    }

    /**
     * Load required component assets.
     *
     * @return void
     */
    protected function load_components(): void
    {

        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        /*
         * Product archive/shop assets.
         */
        if ( is_shop() || is_product_category() || is_product_tag() ) {

            $this->assets->load_component( 'product-filters' );
            $this->assets->load_component( 'variation-swatches' );
            $this->assets->load_component( 'product-brands' );
        }

        /*
         * Single product assets.
         */
        if ( is_product() ) {

            $this->assets->load_component( 'variation-swatches' );
            $this->assets->load_component( 'quick-view' );
            $this->assets->load_component( 'reviews' );
            $this->assets->load_component( 'size-guide' );
            $this->assets->load_component( 'stock-scarcity' );
            $this->assets->load_component( 'product-custom-tabs' );
            $this->assets->load_component( 'product-videos' );
            $this->assets->load_component( 'sale-countdown' );
            $this->assets->load_component( 'social-sharing' );
            $this->assets->load_component( 'payment-icons' );
        }

        /*
         * Cart-related assets.
         */
        if ( is_cart() || is_checkout() ) {

            $this->assets->load_component( 'free-shipping-bar' );
        }

        /*
         * Mini cart is required throughout the store.
         */
        $this->assets->load_component( 'mini-cart' );
    }

}