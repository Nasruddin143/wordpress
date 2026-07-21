<?php

namespace KT\Features;

defined('ABSPATH') || exit;

class PostTypes
{
    public function __construct()
    {
        add_action('init', [$this, 'register']);
    }

    /**
     * ----------------------------------------
     * REGISTER ALL CPTs
     * ----------------------------------------
     */
    public function register()
    {
        // Slider
        $this->create_cpt('slider', 'Slide', 'Sliders', [
            'description' => 'Homepage sliders and banners',
            'rewrite' => ['slug' => 'slider'],
        ]);

        // Sewing Machines
        $this->create_cpt('sewing_machine', 'Sewing Machine', 'Sewing Machines', [
            'description' => 'Domestic and industrial sewing machines for tailoring professionals and home users in Nashik and Ozar.',
            'rewrite' => ['slug' => 'sewing-machines'],
        ]);

        // Air Coolers
        $this->create_cpt('air_cooler', 'Air Cooler', 'Air Coolers', [
            'description' => 'Energy-efficient air coolers for home, shop, and commercial use in Nashik and nearby areas.',
            'rewrite' => ['slug' => 'air-coolers'],
        ]);

        // Services
        $this->create_cpt('service', 'Service', 'Services', [
            'description' => 'Tailoring services, clothing alterations, and sewing machine repair services.',
            'rewrite' => ['slug' => 'services'],
        ]);

        // Accessories
        $this->create_cpt('accessory', 'Accessory', 'Accessories', [
            'description' => 'Sewing machine parts and air cooler accessories for better performance and durability.',
            'rewrite' => ['slug' => 'accessories'],
        ]);

        // Testimonials (Admin only)
        $this->create_cpt('testimonial', 'Testimonial', 'Testimonials', [
            'public' => false,
            'show_ui' => true,
            'rewrite' => false,
        ]);
    }

    /**
     * ----------------------------------------
     * GENERIC CPT CREATOR (Reusable)
     * ----------------------------------------
     */
    private function create_cpt($slug, $singular, $plural, $args = [])
    {
        $defaults = [
            'labels' => $this->get_labels($singular, $plural),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true, // ✅ Required for Rank Math + Gutenberg
            'menu_icon' => 'dashicons-admin-post',
            'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'],
            'has_archive' => true,
            'rewrite' => ['slug' => sanitize_title($plural)],
        ];

        $args = wp_parse_args($args, $defaults);

        register_post_type($slug, $args);
    }

    /**
     * ----------------------------------------
     * REUSABLE LABELS GENERATOR
     * ----------------------------------------
     */
    private function get_labels($singular, $plural)
    {
        return [
            'name'               => __($plural, 'kt'),
            'singular_name'      => __($singular, 'kt'),
            'menu_name'          => __($plural, 'kt'),
            'name_admin_bar'     => __($singular, 'kt'),
            'add_new'            => __('Add New', 'kt'),
            'add_new_item'       => sprintf(__('Add New %s', 'kt'), $singular),
            'edit_item'          => sprintf(__('Edit %s', 'kt'), $singular),
            'new_item'           => sprintf(__('New %s', 'kt'), $singular),
            'view_item'          => sprintf(__('View %s', 'kt'), $singular),
            'all_items'          => sprintf(__('All %s', 'kt'), $plural),
            'search_items'       => sprintf(__('Search %s', 'kt'), $plural),
            'not_found'          => sprintf(__('No %s found', 'kt'), strtolower($plural)),
            'not_found_in_trash' => sprintf(__('No %s found in Trash', 'kt'), strtolower($plural)),
        ];
    }
}