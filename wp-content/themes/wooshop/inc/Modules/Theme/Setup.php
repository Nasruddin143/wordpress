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

        /**
         * Let WordPress manage the document title.
         */
        add_theme_support(
            'title-tag'
        );

        /**
         * Enable featured images.
         */
        add_theme_support(
            'post-thumbnails'
        );

        /**
         * Enable responsive embedded content.
         */
        add_theme_support(
            'responsive-embeds'
        );

        /**
         * Enable HTML5 markup for WordPress components.
         */
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

        /**
         * Enable custom logo support.
         */
        add_theme_support( 'custom-logo' );

        /**
         * Enable selective refresh for widgets.
         */
        add_theme_support(
            'customize-selective-refresh-widgets'
        );

        /**
         * Enable Feed Links in WordPress.
         */
        add_theme_support( 'automatic-feed-links' );

        /**
         * Set the content width used by WordPress embeds and media.
         */
        $GLOBALS['content_width'] = 1200;

    }
}