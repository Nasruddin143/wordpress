<?php
/**
 * WooShop WooCommerce Setup Module
 *
 * Declares WooCommerce theme support, removes default
 * wrappers, and hooks custom wrapper template parts.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Setup
 */
final class Setup extends Module
{
    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_action('after_setup_theme',     [$this, 'declare_support']);
        add_action('wp',                    [$this, 'remove_default_wrappers']);
        add_action('wooshop_woo_before',    [$this, 'open_wrapper']);
        add_action('wooshop_woo_after',     [$this, 'close_wrapper']);
    }

    /**
     * Tell WooCommerce this theme supports it.
     *
     * @return void
     */
    public function declare_support(): void
    {
        add_theme_support('woocommerce', [
            'thumbnail_image_width' => 300,
            'single_image_width'    => 600,
            'product_grid'          => [
                'default_columns' => 3,
                'default_rows'    => 4,
                'min_columns'     => 1,
                'max_columns'     => 6,
            ],
        ]);

        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }

    /**
     * Remove WooCommerce's built-in before/after wrappers
     * so the theme can supply its own.
     *
     * @return void
     */
    public function remove_default_wrappers(): void
    {
        if (!function_exists('is_woocommerce') || !is_woocommerce()) {
            return;
        }

        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper');
        remove_action('woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end');
    }

    /**
     * Output opening wrapper for WooCommerce pages.
     *
     * @return void
     */
    public function open_wrapper(): void
    {
        echo '<main id="primary" class="site-main woo-main">';
    }

    /**
     * Output closing wrapper for WooCommerce pages.
     *
     * @return void
     */
    public function close_wrapper(): void
    {
        echo '</main>';
    }
}