<?php
/**
 * Theme Setup
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;
use WooShop\Theme\Design\CSS;

defined('ABSPATH') || exit;

class Setup extends Module
{

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {

        add_action(
            'after_setup_theme',
            [$this, 'setup']
        );

        add_action(
            'after_setup_theme',
            [$this, 'content_width'],
            0
        );

        add_filter(
            'body_class',
            [$this, 'body_classes']
        );

        add_action(
            'wp_head',
            [CSS::class, 'output'], 1
        );
    }

    /**
     * Theme setup.
     *
     * @return void
     */
    public function setup(): void
    {

        /*
         * Translation.
         */
        load_theme_textdomain(
            'wooshop',
            get_template_directory() . '/languages'
        );

        /*
         * RSS feed links.
         */
        add_theme_support('automatic-feed-links');

        /*
         * Document title.
         */
        add_theme_support('title-tag');

        /*
         * Featured images.
         */
        add_theme_support('post-thumbnails');

        /*
         * HTML5.
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

        /*
         * Custom logo.
         */
        add_theme_support(
            'custom-logo',
            [
                'height' => 120,
                'width' => 320,
                'flex-height' => true,
                'flex-width' => true,
            ]
        );

        /*
         * Custom background.
         */
        add_theme_support('custom-background');

        /*
         * Selective refresh.
         */
        add_theme_support('customize-selective-refresh-widgets');

        /*
         * Responsive embeds.
         */
        add_theme_support('responsive-embeds');

        /*
         * Wide & Full alignment.
         */
        add_theme_support('align-wide');

        /*
         * Editor styles.
         */
        add_editor_style('assets/css/editor.css');
    }

    /**
     * Set content width.
     *
     * @return void
     */
    public function content_width(): void
    {

        $GLOBALS['content_width'] = apply_filters(
            'wooshop_content_width',
            1200
        );
    }

    /**
     * Add body classes.
     *
     * @param array $classes Existing classes.
     *
     * @return array
     */
    public function body_classes(array $classes): array
    {

        if (!is_singular()) {
            $classes[] = 'hfeed';
        }

        if (!is_active_sidebar('sidebar-1')) {
            $classes[] = 'no-sidebar';
        }

        return $classes;
    }
}