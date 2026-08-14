<?php
/**
 * Header Hook Registration
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme\Header;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

class HeaderHooks extends Module
{

    /**
     * Register hooks.
     *
     * @return void
     */
    public function register(): void
    {

        /**
         * Top section.
         */
        add_action(
            'wooshop_header_top',
            'wooshop_render_top_bar',
            10
        );

        /**
         * Main section.
         */
        add_action(
            'wooshop_header_main',
            'wooshop_render_branding',
            10
        );

        add_action(
            'wooshop_header_main',
            'wooshop_render_navigation',
            20
        );

        add_action(
            'wooshop_header_main',
            'wooshop_render_actions',
            30
        );

        /**
         * Bottom section.
         */
        add_action(
            'wooshop_header_bottom',
            'wooshop_render_categories',
            10
        );
    }
}