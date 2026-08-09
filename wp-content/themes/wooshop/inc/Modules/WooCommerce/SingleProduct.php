<?php
/**
 * WooCommerce Single Product Module
 *
 * Handles the single product template.
 *
 * @package WooShop
 */

namespace WooShop\Modules\WooCommerce;

use WooShop\Core\Module;
use WooShop\Core\View;

defined('ABSPATH') || exit;

class SingleProduct extends Module
{

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {
        if (!class_exists('WooCommerce')) {
            return;
        }

        add_action(
            'after_setup_theme',
            [$this, 'setup_hooks'],
            30
        );

        /*
         * Single product structure.
         */
        add_action(
            'wooshop_single_product_gallery',
            [$this, 'gallery']
        );

        add_action(
            'wooshop_single_product_summary',
            [$this, 'summary']
        );

        add_action(
            'wooshop_single_product_details',
            [$this, 'details']
        );

        /*
         * Product gallery.
         */
        add_action(
            'wooshop_product_gallery_content',
            [$this, 'render_gallery']
        );

        /*
         * Product summary.
         */
        add_action(
            'wooshop_product_summary_title',
            [$this, 'title']
        );

        add_action(
            'wooshop_product_summary_rating',
            [$this, 'rating']
        );

        add_action(
            'wooshop_product_summary_price',
            [$this, 'price']
        );

        add_action(
            'wooshop_product_summary_excerpt',
            [$this, 'excerpt']
        );

        add_action(
            'wooshop_product_summary_stock',
            [$this, 'stock']
        );

        add_action(
            'wooshop_product_summary_cart',
            [$this, 'cart']
        );

        add_action(
            'wooshop_product_summary_meta',
            [$this, 'meta']
        );

        add_action(
            'wooshop_product_summary_sharing',
            [$this, 'sharing']
        );

        /*
         * Product details.
         */
        add_action(
            'wooshop_single_product_tabs',
            [$this, 'tabs']
        );

        add_action(
            'wooshop_single_product_upsells',
            [$this, 'upsells']
        );

        add_action(
            'wooshop_single_product_related',
            [$this, 'related']
        );
    }

    /**
     * Configure WooCommerce single-product hooks.
     *
     * @return void
     */
    public function setup_hooks(): void
    {
        /*
         * Gallery.
         */
        remove_action(
            'woocommerce_before_single_product_summary',
            'woocommerce_show_product_images',
            20
        );

        /*
         * Product summary.
         */
        remove_action(
            'woocommerce_single_product_summary',
            'woocommerce_template_single_title',
            5
        );

        remove_action(
            'woocommerce_single_product_summary',
            'woocommerce_template_single_rating',
            10
        );

        remove_action(
            'woocommerce_single_product_summary',
            'woocommerce_template_single_price',
            10
        );

        remove_action(
            'woocommerce_single_product_summary',
            'woocommerce_template_single_excerpt',
            20
        );

        remove_action(
            'woocommerce_single_product_summary',
            'woocommerce_template_single_add_to_cart',
            30
        );

        remove_action(
            'woocommerce_single_product_summary',
            'woocommerce_template_single_meta',
            40
        );

        remove_action(
            'woocommerce_single_product_summary',
            'woocommerce_template_single_sharing',
            50
        );

        /*
         * Product details.
         */
        remove_action(
            'woocommerce_after_single_product_summary',
            'woocommerce_output_product_data_tabs',
            10
        );

        remove_action(
            'woocommerce_after_single_product_summary',
            'woocommerce_upsell_display',
            15
        );

        remove_action(
            'woocommerce_after_single_product_summary',
            'woocommerce_output_related_products',
            20
        );
    }

    /**
     * Render product gallery.
     *
     * @return void
     */
    public function gallery(): void
    {
        /** @var View $view */
        $view = $this->container->get(View::class);

        $view->render(
            'woocommerce/single-product/gallery'
        );
    }

    /**
     * Render product summary.
     *
     * @return void
     */
    public function summary(): void
    {
        /** @var View $view */
        $view = $this->container->get(View::class);

        $view->render(
            'woocommerce/single-product/summary'
        );
    }

    /**
     * Render product details.
     *
     * @return void
     */
    public function details(): void
    {
        /** @var View $view */
        $view = $this->container->get(View::class);

        $view->render(
            'woocommerce/single-product/details'
        );
    }

    /**
     * Render product title.
     *
     * @return void
     */
    public function title(): void
    {
        woocommerce_template_single_title();
    }

    /**
     * Render product rating.
     *
     * @return void
     */
    public function rating(): void
    {
        woocommerce_template_single_rating();
    }

    /**
     * Render product price.
     *
     * @return void
     */
    public function price(): void
    {
        woocommerce_template_single_price();
    }

    /**
     * Render product excerpt.
     *
     * @return void
     */
    public function excerpt(): void
    {
        woocommerce_template_single_excerpt();
    }

    /**
     * Render product stock status.
     *
     * @return void
     */
    public function stock(): void
    {
        woocommerce_template_single_stock();
    }

    /**
     * Render product add-to-cart.
     *
     * @return void
     */
    public function cart(): void
    {
        woocommerce_template_single_add_to_cart();
    }

    /**
     * Render product meta.
     *
     * @return void
     */
    public function meta(): void
    {
        woocommerce_template_single_meta();
    }

    /**
     * Render product sharing.
     *
     * @return void
     */
    public function sharing(): void
    {
        woocommerce_template_single_sharing();
    }

    /**
     * Render product tabs.
     *
     * @return void
     */
    public function tabs(): void
    {
        woocommerce_output_product_data_tabs();
    }

    /**
     * Render product upsells.
     *
     * @return void
     */
    public function upsells(): void
    {
        woocommerce_upsell_display();
    }

    /**
     * Render related products.
     *
     * @return void
     */
    public function related(): void
    {
        woocommerce_output_related_products();
    }

    /**
     * Render WooCommerce product gallery.
     *
     * @return void
     */
    public function render_gallery(): void
    {
        woocommerce_show_product_images();
    }
}