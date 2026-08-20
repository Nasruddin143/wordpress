<?php
/**
 * WooShop Image Sizes Module
 *
 * Registers WooShop-specific image sizes and configures
 * WordPress image size behavior used by the theme.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class ImageSizes
{
    /**
     * Register the image sizes module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
            'after_setup_theme',
            [$this, 'register_image_sizes']
        );

        add_filter(
            'image_size_names_choose',
            [$this, 'register_editor_image_sizes']
        );
    }

    /**
     * Register WooShop custom image sizes.
     *
     * @return void
     */
    public function register_image_sizes(): void
    {
        /*
         * General content image.
         */
        add_image_size(
            'wooshop-content',
            1200,
            800,
            false
        );

        /*
         * Product card image.
         */
        add_image_size(
            'wooshop-product',
            600,
            600,
            true
        );

        /*
         * Product thumbnail image.
         */
        add_image_size(
            'wooshop-product-thumb',
            300,
            300,
            true
        );

        /*
         * Product gallery image.
         */
        add_image_size(
            'wooshop-product-gallery',
            1000,
            1000,
            true
        );

        /*
         * Category image.
         */
        add_image_size(
            'wooshop-category',
            600,
            600,
            true
        );

        /*
         * Brand image.
         */
        add_image_size(
            'wooshop-brand',
            400,
            250,
            true
        );

        /*
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
     * Register WooShop image sizes in the editor.
     *
     * @param array<string, string> $sizes Available image sizes.
     *
     * @return array<string, string>
     */
    public function register_editor_image_sizes(
        array $sizes
    ): array {
        $sizes['wooshop-content'] = __('WooShop Content', 'wooshop');
        $sizes['wooshop-product'] = __('WooShop Product', 'wooshop');
        $sizes['wooshop-product-thumb'] = __(
            'WooShop Product Thumbnail',
            'wooshop'
        );
        $sizes['wooshop-product-gallery'] = __(
            'WooShop Product Gallery',
            'wooshop'
        );
        $sizes['wooshop-category'] = __('WooShop Category', 'wooshop');
        $sizes['wooshop-brand'] = __('WooShop Brand', 'wooshop');
        $sizes['wooshop-hero'] = __('WooShop Hero', 'wooshop');

        return $sizes;
    }
}