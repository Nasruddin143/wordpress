<?php
/**
 * WordPress Sidebars.
 *
 * Registers widget areas for the WooShop theme.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WordPress widget areas.
 */
class Sidebars extends Module {

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'widgets_init',
            [ $this, 'register_sidebars' ]
        );
    }

    /**
     * Register theme widget areas.
     *
     * @return void
     */
    public function register_sidebars(): void {

        register_sidebar(
            [
                'name'          => esc_html__( 'Primary Sidebar', 'wooshop' ),
                'id'            => 'sidebar-primary',
                'description'   => esc_html__(
                    'Main sidebar displayed throughout the site.',
                    'wooshop'
                ),
                'before_widget' => '<section id="%1$s" class="widget %2$s mb-4">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title h5 mb-3">',
                'after_title'   => '</h2>',
            ]
        );

        register_sidebar(
            [
                'name'          => esc_html__( 'Shop Sidebar', 'wooshop' ),
                'id'            => 'sidebar-shop',
                'description'   => esc_html__(
                    'Sidebar used on WooCommerce shop and product archive pages.',
                    'wooshop'
                ),
                'before_widget' => '<section id="%1$s" class="widget %2$s mb-4">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title h5 mb-3">',
                'after_title'   => '</h2>',
            ]
        );

        register_sidebar(
            [
                'name'          => esc_html__( 'Footer 1', 'wooshop' ),
                'id'            => 'footer-1',
                'description'   => esc_html__(
                    'First footer widget area.',
                    'wooshop'
                ),
                'before_widget' => '<section id="%1$s" class="widget %2$s mb-4">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title h5 mb-3">',
                'after_title'   => '</h2>',
            ]
        );

        register_sidebar(
            [
                'name'          => esc_html__( 'Footer 2', 'wooshop' ),
                'id'            => 'footer-2',
                'description'   => esc_html__(
                    'Second footer widget area.',
                    'wooshop'
                ),
                'before_widget' => '<section id="%1$s" class="widget %2$s mb-4">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title h5 mb-3">',
                'after_title'   => '</h2>',
            ]
        );

        register_sidebar(
            [
                'name'          => esc_html__( 'Footer 3', 'wooshop' ),
                'id'            => 'footer-3',
                'description'   => esc_html__(
                    'Third footer widget area.',
                    'wooshop'
                ),
                'before_widget' => '<section id="%1$s" class="widget %2$s mb-4">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title h5 mb-3">',
                'after_title'   => '</h2>',
            ]
        );

        register_sidebar(
            [
                'name'          => esc_html__( 'Footer 4', 'wooshop' ),
                'id'            => 'footer-4',
                'description'   => esc_html__(
                    'Fourth footer widget area.',
                    'wooshop'
                ),
                'before_widget' => '<section id="%1$s" class="widget %2$s mb-4">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title h5 mb-3">',
                'after_title'   => '</h2>',
            ]
        );
    }
}