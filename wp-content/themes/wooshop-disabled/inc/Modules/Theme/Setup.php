<?php
/**
 * WooShop Theme Setup Module
 *
 * Registers the core WordPress theme supports, menus, image
 * handling, editor support, and other foundational theme setup.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class Setup
{
    /**
     * Register the theme setup module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
            'after_setup_theme',
            [$this, 'setup_theme'],
            10
        );
    }

    /**
     * Configure WooShop theme support.
     *
     * @return void
     */
    public function setup_theme(): void
    {
        /*
         * Make the theme available for translation.
         *
         * The Localization module handles the actual
         * translation loading.
         */
        add_theme_support('title-tag');

        add_theme_support('post-thumbnails');

        add_theme_support('automatic-feed-links');

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

        add_theme_support(
            'custom-logo',
            [
                'height'      => 100,
                'width'       => 400,
                'flex-width'  => true,
                'flex-height' => true,
            ]
        );

        add_theme_support(
            'custom-background',
            [
                'default-color' => 'ffffff',
            ]
        );

        add_theme_support(
            'custom-header',
            [
                'width'       => 1920,
                'height'      => 800,
                'flex-width'  => true,
                'flex-height' => true,
            ]
        );

        add_theme_support('responsive-embeds');

        add_theme_support('align-wide');

        add_theme_support('wp-block-styles');

        add_theme_support('editor-styles');
    }
}