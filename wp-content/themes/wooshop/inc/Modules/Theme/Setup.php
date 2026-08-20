<?php
/**
 * Theme Setup Module.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WooShop\Core\ModuleManager;

defined( 'ABSPATH' ) || exit;

/**
 * Theme setup.
 */
final class Setup extends Module {

    /**
     * Constructor.
     *
     * @param ModuleManager $manager Module manager.
     */
    public function __construct( ModuleManager $manager ) {
        parent::__construct( $manager );
    }

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void {
        add_action( 'after_setup_theme', array( $this, 'setup' ) );
    }

    /**
     * Setup theme features.
     *
     * @return void
     */
    public function setup(): void {

        load_theme_textdomain(
            'wooshop',
            get_template_directory() . '/languages'
        );

        add_theme_support( 'automatic-feed-links' );

        add_theme_support( 'title-tag' );

        add_theme_support( 'post-thumbnails' );

        add_theme_support(
            'html5',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            )
        );

        add_theme_support( 'custom-logo' );

        add_theme_support( 'customize-selective-refresh-widgets' );

        add_theme_support( 'responsive-embeds' );

        add_theme_support( 'wp-block-styles' );

        add_theme_support( 'align-wide' );

        $this->register_menus();
    }

    /**
     * Register navigation menus.
     *
     * @return void
     */
    private function register_menus(): void {

        register_nav_menus(
            array(
                'primary' => esc_html__( 'Primary Menu', 'wooshop' ),
                'footer'  => esc_html__( 'Footer Menu', 'wooshop' ),
            )
        );
    }
}