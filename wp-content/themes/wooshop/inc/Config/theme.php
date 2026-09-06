<?php
/**
 * WooShop Theme Configuration.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

return array(

    /*
     * Theme information.
     */
    'version' => '1.0.0',

    /*
     * Content width.
     */
    'content_width' => 640,

    /*
     * Text domain.
     */
    'text_domain' => 'wooshop',

    /*
     * Translation directory.
     */
    'translation_path' => 'languages',

    /*
     * Navigation menus.
     */
    'menus' => array(

        'primary' => array(
            'label' => esc_html__('Primary Menu', 'wooshop'),
        ),

        'footer' => array(
            'label' => esc_html__('Footer Menu', 'wooshop'),
        ),
    ),

    /*
     * Theme supports.
     */
    'supports' => array(

        'automatic-feed-links' => true,

        'title-tag' => true,

        'post-thumbnails' => true,

        'html5' => array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ),

        'customize-selective-refresh-widgets' => true,

        'custom-logo' => array(
            'height' => 50,
            'width' => 201,
            'flex-width' => false,
            'flex-height' => false,
        ),

        'responsive-embeds' => true,

        'wp-block-styles' => true,

        'align-wide' => true,
    ),

    /*
     * Widget areas.
     */
    'widget_areas' => [

        'sidebar-1' => [
            'name' => 'Sidebar',
            'description' => 'Add widgets here.',
        ],

        'woocommerce-sidebar' => [
            'name' => 'WooCommerce Sidebar',
            'description' => 'Add WooCommerce widgets here.',
        ],

        'footer-1' => [
            'name' => 'Footer 1',
            'description' => 'Add widgets here.',
        ],

        'footer-2' => [
            'name' => 'Footer 2',
            'description' => 'Add widgets here.',
        ],

        'footer-3' => [
            'name' => 'Footer 3',
            'description' => 'Add widgets here.',
        ],

        'footer-4' => [
            'name' => 'Footer 4',
            'description' => 'Add widgets here.',
        ],
    ],

    /*
     * Custom header.
     */
    'custom_header' => array(
        'default-image' => '',
        'default-text-color' => '000000',
        'width' => 1920,
        'height' => 400,
        'flex-height' => true,
        'flex-width' => true,
    ),

    /*
     * Custom background.
     */
    'custom_background' => array(
        'default-color' => 'ffffff',
        'default-image' => '',
    ),

    /*
    * WooCommerce loop settings.
    * Read by WooCommerce\Templates module.
    */
    'woo_loop_columns' => 4,
    'woo_products_per_page' => 12,
    'woo_related_products' => 4,
    'woo_show_category_grid' => false,

    /*
     * ─────────────────────────────────────────────────────────
     * Shop page configuration.
     * Read by WooCommerce\Shop module.
     * ─────────────────────────────────────────────────────────
     */
    'shop' => [

        /*
         * Page layout.
         * sidebar    — content + sidebar (uses sidebar-shop widget area)
         * full-width — no sidebar, full content width
         */
        'layout' => 'sidebar',

        /*
         * Sidebar position when layout is 'sidebar'.
         * left | right
         */
        'sidebar_position' => 'left',

        /*
         * Default product view.
         * grid | list
         */
        'view' => 'grid',

        /*
         * Show orderby select in the toolbar.
         */
        'orderby_options' => true,

        /*
         * Show per-page select in the toolbar.
         */
        'per_page_options' => true,

        /*
         * Show grid/list view toggle in the toolbar.
         */
        'view_toggle' => true,
    ],
);