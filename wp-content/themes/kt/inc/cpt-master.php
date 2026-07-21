<?php
defined('ABSPATH') || exit;

/**
 * MASTER REGISTER FUNCTION
 * Registers all CPTs, Categories, and Tags
 * Fully Classic Theme Compatible
 */
function kt_register_all_content()
{

    /*
    ==========================================================
    CUSTOM POST TYPES
    ==========================================================
    */

    // Slider
    register_post_type('slider', [
        'labels' => [
            'name' => __('Sliders', 'kt'),
            'singular_name' => __('Slide', 'kt'),
        ],
        'public' => true,
        'menu_icon' => 'dashicons-admin-post',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'slider'],
        'show_in_rest' => true,
    ]);


    // Sewing Machines
    register_post_type('sewing_machine', [
        'labels' => [
            'name' => __('Sewing Machines', 'kt'),
            'singular_name' => __('Sewing Machine', 'kt'),
        ],
        'description' => 'Our shop offers a wide range of sewing machines suitable for home users, tailoring professionals, and small businesses. From domestic sewing machines for everyday stitching to heavy-duty industrial models for professional tailoring work, we provide reliable machines designed for performance and durability. Customers in Ozar and Nashik trust our shop for quality sewing machines, genuine accessories, and expert guidance when choosing the right model. Whether you are starting a tailoring business or upgrading your equipment, we help you find the perfect sewing machine for your needs.',
        'public' => true,
        'menu_icon' => 'dashicons-admin-post',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'sewing-machines'],
        'show_in_rest' => true,
    ]);


    // Air Coolers
    register_post_type('air_cooler', [
        'labels' => [
            'name' => __('Air Coolers', 'kt'),
            'singular_name' => __('Air Cooler', 'kt'),
        ],
        'description' => 'Our shop offers a wide range of air coolers designed to provide powerful and energy-efficient cooling for homes, shops, and offices. From compact personal coolers to large desert air coolers, we provide reliable models suitable for different room sizes and cooling needs. Customers in Ozar and Nashik trust our shop for quality air coolers, genuine accessories, and dependable guidance when choosing the right cooler. Whether you are looking for a cooler for home use or a larger unit for commercial spaces, we help you select the best cooling solution for the summer season.',
        'public' => true,
        'menu_icon' => 'dashicons-admin-post',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'air-coolers'],
        'show_in_rest' => true,
    ]);


    // Services
    register_post_type('service', [
        'labels' => [
            'name' => __('Services', 'kt'),
            'singular_name' => __('Service', 'kt'),
        ],
        'description' => 'Our shop provides professional tailoring services and sewing machine repair solutions for customers in Ozar and nearby areas of Nashik. With years of experience in the tailoring industry, we specialize in men’s custom stitching, clothing alterations, and precision garment finishing. In addition to tailoring services, we also offer reliable sewing machine repair and maintenance. Our experienced technicians diagnose and fix common issues to ensure your sewing machines work smoothly and efficiently. Customers trust our shop for quality workmanship, attention to detail, and dependable service. Whether you need custom men’s clothing stitched or professional repair for your sewing machine, we are committed to providing practical solutions with excellent customer care.',
        'public' => true,
        'menu_icon' => 'dashicons-admin-post',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'services'],
        'show_in_rest' => true,
    ]);


    // Accessories
    register_post_type('accessory', [
        'labels' => [
            'name' => __('Accessories', 'kt'),
            'singular_name' => __('Accessory', 'kt'),
        ],
        'description' => 'Our shop offers a wide range of accessories for sewing machines and air coolers to support smooth operation and long-lasting performance. From essential sewing machine parts to cooling system accessories, we provide reliable products suitable for both household users and professional tailoring shops. Customers in Ozar and nearby areas of Nashik trust our shop for genuine accessories, quality materials, and helpful guidance when selecting the right components. Whether you need replacement parts for your sewing machine or accessories to improve the performance of your air cooler, we provide dependable solutions at competitive prices.',
        'public' => true,
        'menu_icon' => 'dashicons-admin-post',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'accessories'],
        'show_in_rest' => true,
    ]);


    // Testimonials (Admin only)
    register_post_type('testimonial', [
        'labels' => [
            'name' => __('Testimonials', 'kt'),
            'singular_name' => __('Testimonial', 'kt'),
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-admin-post',
        'supports' => ['title', 'editor', 'thumbnail'],
    ]);



    /*
    ==========================================================
    CATEGORIES
    ==========================================================
    */

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
            ],

            'hierarchical' => true,
            'show_admin_column' => true,
            'rewrite' => [
                'slug' => $data['slug'],
                'hierarchical' => true,
            ],

            'show_in_rest' => true,

        ]);
    }



    /*
    ==========================================================
    TAGS
    ==========================================================
    */

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
            'show_admin_column' => true,
            'rewrite' => [
                'slug' => $data['slug'],
            ],

            'show_in_rest' => true,

        ]);
    }
}

add_action('init', 'kt_register_all_content');
