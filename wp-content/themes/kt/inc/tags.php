<?php
defined('ABSPATH') || exit;

/**
 * Register All Custom Taxonomies (Classic Theme Compatible)
 */
function kt_register_all_taxonomies() {

    /*
    |--------------------------------------------------------------------------
    | Sewing Machine Tags
    |--------------------------------------------------------------------------
    */
    register_taxonomy('sewing_machine_tag', ['sewing_machine'], [
        'labels' => [
            'name'          => __('Sewing Machine Tags', 'kt'),
            'singular_name' => __('Sewing Machine Tag', 'kt'),
        ],
        'hierarchical'      => false,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'sewing-machines-tag'],
        'show_in_rest'      => false, // Classic theme
    ]);


    /*
    |--------------------------------------------------------------------------
    | Air Cooler Tags
    |--------------------------------------------------------------------------
    */
    register_taxonomy('air_cooler_tag', ['air_cooler'], [
        'labels' => [
            'name'          => __('Air Cooler Tags', 'kt'),
            'singular_name' => __('Air Cooler Tag', 'kt'),
        ],
        'hierarchical'      => false,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'air-coolers-tag'],
        'show_in_rest'      => false,
    ]);


    /*
    |--------------------------------------------------------------------------
    | Accessory Tags
    |--------------------------------------------------------------------------
    */
    register_taxonomy('accessory_tag', ['accessory'], [
        'labels' => [
            'name'          => __('Accessory Tags', 'kt'),
            'singular_name' => __('Accessory Tag', 'kt'),
        ],
        'hierarchical'      => false,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'accessories-tag'],
        'show_in_rest'      => false,
    ]);


    /*
    |--------------------------------------------------------------------------
    | Service Tags
    |--------------------------------------------------------------------------
    */
    register_taxonomy('service_tag', ['service'], [
        'labels' => [
            'name'          => __('Service Tags', 'kt'),
            'singular_name' => __('Service Tag', 'kt'),
        ],
        'hierarchical'      => false,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'services-tag'],
        'show_in_rest'      => false,
    ]);

}

add_action('init', 'kt_register_all_taxonomies');