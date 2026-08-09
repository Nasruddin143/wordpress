<?php
/**
 * WooCommerce Integration Module
 *
 * Handles WooCommerce theme integration.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

class Integration extends Module
{
    /**
     * Register WooCommerce integration.
     *
     * @return void
     */
    public function register(): void
    {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        add_action(
            'after_setup_theme',
            [ $this, 'setup' ],
            20
        );

        add_filter(
            'body_class',
            [ $this, 'body_classes' ]
        );
    }

    /**
     * Configure WooCommerce support.
     *
     * @return void
     */
    public function setup(): void
    {
        add_theme_support(
            'woocommerce'
        );

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
     * Add WooCommerce body classes.
     *
     * @param array $classes Existing classes.
     *
     * @return array
     */
    public function body_classes( array $classes ): array
    {
        if ( ! function_exists( 'is_woocommerce' ) ) {
            return $classes;
        }

        if ( is_woocommerce() ) {
            $classes[] = 'ws-woocommerce';
        }

        if ( function_exists( 'is_shop' ) && is_shop() ) {
            $classes[] = 'ws-shop';
        }

        if (
            function_exists( 'is_product' )
            && is_product()
        ) {
            $classes[] = 'ws-product';
        }

        if (
            function_exists( 'is_product_category' )
            && is_product_category()
        ) {
            $classes[] = 'ws-product-category';
        }

        if (
            function_exists( 'is_product_tag' )
            && is_product_tag()
        ) {
            $classes[] = 'ws-product-tag';
        }

        return $classes;
    }
}