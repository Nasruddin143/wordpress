<?php
defined('ABSPATH') || exit;

/**
 * Register All Custom Categories (Classic Theme Compatible)
 */
function kt_register_all_categories() {

    /*
    |--------------------------------------------------------------------------
    | Sewing Machine Categories
    |--------------------------------------------------------------------------
    */
    register_taxonomy('sewing_machine_category', ['sewing_machine'], [

        'labels' => [
            'name'          => __('Sewing Machine Categories', 'kt'),
            'singular_name' => __('Sewing Machine Category', 'kt'),
        ],

        'hierarchical'      => true,
        'show_admin_column' => true,
        'show_in_rest'      => false,

        'rewrite' => [
            'slug'         => 'sewing-machines-category',
            'hierarchical' => true,
        ],

    ]);


    /*
    |--------------------------------------------------------------------------
    | Air Cooler Categories
    |--------------------------------------------------------------------------
    */
    register_taxonomy('air_cooler_category', ['air_cooler'], [

        'labels' => [
            'name'          => __('Air Cooler Categories', 'kt'),
            'singular_name' => __('Air Cooler Category', 'kt'),
        ],

        'hierarchical'      => true,
        'show_admin_column' => true,
        'show_in_rest'      => false,

        'rewrite' => [
            'slug'         => 'air-coolers-category',
            'hierarchical' => true,
        ],

    ]);


    /*
    |--------------------------------------------------------------------------
    | Accessory Categories
    |--------------------------------------------------------------------------
    */
    register_taxonomy('accessory_category', ['accessory'], [

        'labels' => [
            'name'          => __('Accessory Categories', 'kt'),
            'singular_name' => __('Accessory Category', 'kt'),
        ],

        'hierarchical'      => true,
        'show_admin_column' => true,
        'show_in_rest'      => false,

        'rewrite' => [
            'slug'         => 'accessories-category',
            'hierarchical' => true,
        ],

    ]);


    /*
    |--------------------------------------------------------------------------
    | Service Categories
    |--------------------------------------------------------------------------
    */
    register_taxonomy('service_category', ['service'], [

        'labels' => [
            'name'          => __('Service Categories', 'kt'),
            'singular_name' => __('Service Category', 'kt'),
            'search_items'  => __('Search Categories', 'kt'),
            'all_items'     => __('All Categories', 'kt'),
            'edit_item'     => __('Edit Category', 'kt'),
            'add_new_item'  => __('Add New Category', 'kt'),
            'menu_name'     => __('Categories', 'kt'),
        ],

        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => false,

        'rewrite' => [
            'slug'         => 'services-category',
            'hierarchical' => true,
        ],

    ]);

}

add_action('init', 'kt_register_all_categories');