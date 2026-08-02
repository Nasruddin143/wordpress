<?php
/**
 * Sidebars Configuration
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

return [

    [
        'id'            => 'sidebar-1',
        'name'          => __( 'Primary Sidebar', 'wooshop' ),
        'description'   => __( 'Main sidebar widget area.', 'wooshop' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ],

    [
        'id'            => 'footer-1',
        'name'          => __( 'Footer 1', 'wooshop' ),
        'description'   => __( 'First footer widget area.', 'wooshop' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ],

    [
        'id'            => 'footer-2',
        'name'          => __( 'Footer 2', 'wooshop' ),
        'description'   => __( 'Second footer widget area.', 'wooshop' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ],

    [
        'id'            => 'footer-3',
        'name'          => __( 'Footer 3', 'wooshop' ),
        'description'   => __( 'Third footer widget area.', 'wooshop' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ],

];