<?php
/**
 * WooShop Theme Setup Module
 *
 * Registers the basic WordPress theme supports,
 * menus, HTML5 support, editor styles, and related
 * theme configuration.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Setup
 */
final class Setup extends Module
{
    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_action('after_setup_theme', [$this, 'setup']);
    }

    /**
     * Configure the WordPress theme.
     *
     * @return void
     */
    public function setup(): void
    {
        /*
         * ---------------------------------------------------------
         * Translation
         * ---------------------------------------------------------
         */

        load_theme_textdomain('wooshop', get_template_directory() . '/languages');

        /*
         * ---------------------------------------------------------
         * Document title
         * ---------------------------------------------------------
         */

        add_theme_support('title-tag');

        /*
         * ---------------------------------------------------------
         * Post thumbnails
         * ---------------------------------------------------------
         */

        add_theme_support('post-thumbnails');

        /*
         * ---------------------------------------------------------
         * HTML5 markup
         * ---------------------------------------------------------
         */

        add_theme_support('html5',
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

        /*
         * ---------------------------------------------------------
         * Custom logo
         * ---------------------------------------------------------
         */

        add_theme_support('custom-logo',
            [
                'height' => 100,
                'width' => 300,
                'flex-height' => false,
                'flex-width' => false,
            ]
        );

        /*
         * ---------------------------------------------------------
         * Automatic feed links
         * ---------------------------------------------------------
         */

        add_theme_support('automatic-feed-links');

        /*
         * ---------------------------------------------------------
         * Selective refresh widgets
         * ---------------------------------------------------------
         */

        add_theme_support('customize-selective-refresh-widgets');

        /*
         * ---------------------------------------------------------
         * Navigation menus
         * ---------------------------------------------------------
         */

        register_nav_menus(
            [
                'primary' => __('Primary Menu', 'wooshop'),
                'footer' => __('Footer Menu', 'wooshop'),
            ]
        );

        /*
         * ---------------------------------------------------------
         * Editor styles
         * ---------------------------------------------------------
         */

        add_theme_support('editor-styles');

        /*
         * ---------------------------------------------------------
         * Responsive embeds
         * ---------------------------------------------------------
         */

        add_theme_support('responsive-embeds');

        /*
         * ---------------------------------------------------------
         * Wide alignment
         * ---------------------------------------------------------
         */

        add_theme_support('align-wide');
    }
}