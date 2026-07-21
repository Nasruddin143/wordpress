<?php

namespace KT\Features;

class Taxonomies
{
    public function __construct()
    {
        add_action('init', [$this, 'register']);
    }

    public function register()
    {

        // =========================
        // CATEGORY TAXONOMIES
        // =========================
        $category_taxonomies = [

            'sewing_machine_category' => [
                'post_type' => 'sewing_machine',
                'slug' => 'sewing-machines-category',
                'name' => 'Sewing Machine Categories'
            ],

            'air_cooler_category' => [
                'post_type' => 'air_cooler',
                'slug' => 'air-coolers-category',
                'name' => 'Air Cooler Categories'
            ],

            'accessory_category' => [
                'post_type' => 'accessory',
                'slug' => 'accessories-category',
                'name' => 'Accessory Categories'
            ],

            'service_category' => [
                'post_type' => 'service',
                'slug' => 'services-category',
                'name' => 'Service Categories'
            ],
        ];

        foreach ($category_taxonomies as $taxonomy => $data) {

            register_taxonomy($taxonomy, [$data['post_type']], [

                'labels' => [
                    'name' => __($data['name'], 'kt'),
                    'singular_name' => __($data['name'], 'kt'),
                    'search_items' => __('Search ' . $data['name'], 'kt'),
                    'all_items' => __('All ' . $data['name'], 'kt'),
                    'edit_item' => __('Edit ' . $data['name'], 'kt'),
                    'update_item' => __('Update ' . $data['name'], 'kt'),
                    'add_new_item' => __('Add New ' . $data['name'], 'kt'),
                    'new_item_name' => __('New ' . $data['name'], 'kt'),
                ],

                'hierarchical' => true,
                'public' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,

                'rewrite' => [
                    'slug' => $data['slug'],
                    'hierarchical' => true,
                ],

                'show_in_rest' => true,
            ]);
        }

        // =========================
        // TAG TAXONOMIES
        // =========================
        $tag_taxonomies = [

            'sewing_machine_tag' => [
                'post_type' => 'sewing_machine',
                'slug' => 'sewing-machines-tag',
                'name' => 'Sewing Machine Tags'
            ],

            'air_cooler_tag' => [
                'post_type' => 'air_cooler',
                'slug' => 'air-coolers-tag',
                'name' => 'Air Cooler Tags'
            ],

            'accessory_tag' => [
                'post_type' => 'accessory',
                'slug' => 'accessories-tag',
                'name' => 'Accessory Tags'
            ],

            'service_tag' => [
                'post_type' => 'service',
                'slug' => 'services-tag',
                'name' => 'Service Tags'
            ],
        ];

        foreach ($tag_taxonomies as $taxonomy => $data) {

            register_taxonomy($taxonomy, [$data['post_type']], [

                'labels' => [
                    'name' => __($data['name'], 'kt'),
                    'singular_name' => __($data['name'], 'kt'),
                ],

                'hierarchical' => false,
                'public' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,

                'rewrite' => [
                    'slug' => $data['slug'],
                ],

                'show_in_rest' => true,
            ]);
        }
    }
}