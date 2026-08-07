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
        load_theme_textdomain('wooshop', get_template_directory() . '/languages');

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
                'height' => 80,
                'width' => 260,
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
         * Block styles.
         */
        add_theme_support('wp-block-styles');

        /*
         * Editor styles.
         */
        add_theme_support('editor-styles');

        /*
         * Editor styles CSS.
         */
        add_editor_style(
            'assets/build/css/editor.min.css'
        );

        /*
         * Custom Spacing.
         */
        add_theme_support(
            'custom-spacing'
        );

        /*
         * Custom Line Height.
         */
        add_theme_support(
            'custom-line-height'
        );

        /*
         * Appearance Tools.
         */
        add_theme_support(
            'appearance-tools'
        );

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