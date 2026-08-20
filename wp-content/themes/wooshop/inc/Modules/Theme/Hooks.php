<?php
/**
 * WooShop Theme Hooks Module
 *
 * Registers the core WordPress action and filter hooks used
 * by the WooShop theme templates and theme modules.
 *
 * This module provides centralized hook registration without
 * placing procedural callbacks in functions.php.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class Hooks
{
    /**
     * Register the theme hooks module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
            'after_setup_theme',
            [$this, 'setup_theme_hooks']
        );

        add_action(
            'wp_head',
            [$this, 'render_head_hooks'],
            1
        );

        add_action(
            'wp_body_open',
            [$this, 'render_body_open_hooks']
        );

        add_action(
            'wp_footer',
            [$this, 'render_footer_hooks'],
            99
        );
    }

    /**
     * Register hooks used during theme setup.
     *
     * @return void
     */
    public function setup_theme_hooks(): void
    {
        /*
         * Reserved for theme-level setup hooks.
         *
         * Feature-specific setup should remain inside
         * its corresponding WooShop module.
         */
    }

    /**
     * Execute the WooShop head hook.
     *
     * Provides a dedicated extension point before the standard
     * WordPress wp_head callback output.
     *
     * @return void
     */
    public function render_head_hooks(): void
    {
        do_action('wooshop_head');
    }

    /**
     * Execute the WooShop body-open hook.
     *
     * Provides a dedicated extension point immediately after
     * the opening body element.
     *
     * @return void
     */
    public function render_body_open_hooks(): void
    {
        do_action('wooshop_body_open');
    }

    /**
     * Execute the WooShop footer hook.
     *
     * Provides a dedicated extension point before the standard
     * WordPress footer processing is completed.
     *
     * @return void
     */
    public function render_footer_hooks(): void
    {
        do_action('wooshop_footer');
    }
}