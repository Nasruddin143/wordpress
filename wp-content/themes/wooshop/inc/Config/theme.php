<?php
/**
 * WooShop Theme Configuration.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

return array(

    /**
     * Theme supports.
     */
    'supports' => array(
        'title-tag',
        'post-thumbnails',
        'custom-logo',
        'custom-header',
        'custom-background',
        'html5' => array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ),
        'responsive-embeds',
        'align-wide',
        'wp-block-styles',
    ),

    /**
     * Navigation menus.
     */
    'menus' => array(
        'primary' => esc_html__( 'Primary Menu', 'wooshop' ),
        'footer'  => esc_html__( 'Footer Menu', 'wooshop' ),
    ),

    /**
     * Widget areas.
     */
    'widget_areas' => array(
        'sidebar-1' => array(
            'name'        => esc_html__( 'Sidebar', 'wooshop' ),
            'description' => esc_html__(
                'Add widgets here.',
                'wooshop'
            ),
        ),
        'footer-1' => array(
            'name'        => esc_html__( 'Footer 1', 'wooshop' ),
            'description' => esc_html__(
                'Add footer widgets here.',
                'wooshop'
            ),
        ),
        'footer-2' => array(
            'name'        => esc_html__( 'Footer 2', 'wooshop' ),
            'description' => esc_html__(
                'Add footer widgets here.',
                'wooshop'
            ),
        ),
    ),
);