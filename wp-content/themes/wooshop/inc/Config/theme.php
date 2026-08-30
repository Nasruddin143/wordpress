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
    'widget_areas' => array(

        'sidebar-1' => array(
            'name' => 'Sidebar',
            'description' => 'Add widgets here.',
        ),

        'footer-1' => array(
            'name' => 'Footer 1',
            'description' => 'Add widgets here.',
        ),

        'footer-2' => array(
            'name' => 'Footer 2',
            'description' => 'Add widgets here.',
        ),
    ),

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
);