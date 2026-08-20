<?php
/**
 * WooShop Mobile Commerce Module
 *
 * Provides theme-level mobile commerce support, including
 * mobile viewport configuration, touch-friendly behavior,
 * and mobile commerce body classes.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class MobileCommerce
{
    /**
     * Register the mobile commerce module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
                'wp_head',
                [$this, 'render_viewport_meta'],
                1
        );

        add_filter(
                'body_class',
                [$this, 'add_mobile_commerce_body_class']
        );

        add_filter(
                'wp_nav_menu_args',
                [$this, 'filter_mobile_navigation_args']
        );
    }

    /**
     * Render the mobile viewport metadata.
     *
     * WordPress themes should provide a responsive viewport
     * declaration so WooShop layouts render correctly on
     * mobile commerce devices.
     *
     * @return void
     */
    public function render_viewport_meta(): void
    {
        if (is_admin()) {
            return;
        }

        echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
    }

    /**
     * Add the mobile commerce body class.
     *
     * @param array<int, string> $classes Existing body classes.
     *
     * @return array<int, string>
     */
    public function add_mobile_commerce_body_class(
            array $classes
    ): array {
        $classes[] = 'ws-mobile-commerce';

        if (function_exists('is_woocommerce') && is_woocommerce()) {
            $classes[] = 'ws-mobile-commerce-shop';
        }

        if (
                function_exists('is_cart')
                && is_cart()
        ) {
            $classes[] = 'ws-mobile-commerce-cart';
        }

        if (
                function_exists('is_checkout')
                && is_checkout()
        ) {
            $classes[] = 'ws-mobile-commerce-checkout';
        }

        return array_values(
                array_unique($classes)
        );
    }

    /**
     * Add mobile navigation attributes.
     *
     * Adds a dedicated CSS class to WordPress navigation
     * menus so the mobile navigation component can target
     * them without modifying global menu markup elsewhere.
     *
     * @param array<string, mixed> $args Navigation arguments.
     *
     * @return array<string, mixed>
     */
    public function filter_mobile_navigation_args(
            array $args
    ): array {
        $menu_class = $args['menu_class'] ?? '';

        if (!is_string($menu_class)) {
            $menu_class = '';
        }

        $classes = preg_split(
                '/\s+/',
                trim($menu_class)
        );

        if (!is_array($classes)) {
            $classes = [];
        }

        $classes[] = 'ws-mobile-commerce-menu';

        $args['menu_class'] = implode(
                ' ',
                array_values(
                        array_unique(
                                array_filter($classes)
                        )
                )
        );

        return $args;
    }
}