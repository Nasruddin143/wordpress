<?php
/**
 * WordPress theme setup module.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WordPress theme setup functionality.
 */
class Setup extends Module {

    /**
     * Register the module.
     *
     * @return void
     */
    public function register(): void {
        add_action( 'after_setup_theme', [ $this, 'setup' ] );
    }

    /**
     * Configure WordPress theme supports and features.
     *
     * @return void
     */
    public function setup(): void {
        add_theme_support( 'title-tag' );

        add_theme_support( 'post-thumbnails' );

        add_theme_support(
            'html5',
            [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            ]
        );

        add_theme_support( 'custom-logo' );

        add_theme_support( 'automatic-feed-links' );

        add_theme_support( 'responsive-embeds' );
    }
}