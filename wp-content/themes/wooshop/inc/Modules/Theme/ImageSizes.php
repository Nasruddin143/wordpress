<?php
/**
 * WordPress Image Sizes.
 *
 * Registers custom image sizes used by the WooShop theme.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WooShop image sizes.
 */
class ImageSizes extends Module {

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'after_setup_theme',
            [ $this, 'register_image_sizes' ]
        );

        add_filter(
            'image_size_names_choose',
            [ $this, 'add_image_size_names' ]
        );
    }

    /**
     * Register custom image sizes.
     *
     * @return void
     */
    public function register_image_sizes(): void {

        /**
         * Product card image.
         */
        add_image_size(
            'wooshop-product-card',
            600,
            600,
            true
        );

        /**
         * Product thumbnail image.
         */
        add_image_size(
            'wooshop-product-thumb',
            300,
            300,
            true
        );

        /**
         * Product gallery image.
         */
        add_image_size(
            'wooshop-product-gallery',
            1200,
            1200,
            true
        );

        /**
         * Blog/archive card image.
         */
        add_image_size(
            'wooshop-post-card',
            800,
            500,
            true
        );

        /**
         * Hero/banner image.
         */
        add_image_size(
            'wooshop-hero',
            1920,
            800,
            true
        );
    }

    /**
     * Add custom image sizes to the WordPress image selector.
     *
     * @param array $sizes Available image sizes.
     * @return array
     */
    public function add_image_size_names( array $sizes ): array {

        return array_merge(
            $sizes,
            [
                'wooshop-product-card'    => esc_html__( 'WooShop Product Card', 'wooshop' ),
                'wooshop-product-thumb'   => esc_html__( 'WooShop Product Thumbnail', 'wooshop' ),
                'wooshop-product-gallery' => esc_html__( 'WooShop Product Gallery', 'wooshop' ),
                'wooshop-post-card'       => esc_html__( 'WooShop Post Card', 'wooshop' ),
                'wooshop-hero'            => esc_html__( 'WooShop Hero', 'wooshop' ),
            ]
        );
    }
}