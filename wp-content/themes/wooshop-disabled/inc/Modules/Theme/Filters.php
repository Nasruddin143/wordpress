<?php
/**
 * WooShop Filters Module
 *
 * Registers centralized WordPress filters used by the WooShop
 * theme. This module provides lightweight content, title,
 * class, and output normalization.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Filters
 *
 * Handles general WordPress filter registrations for WooShop.
 */
final class Filters extends Module {

    /**
     * Register WooShop filters.
     *
     * @return void
     */
    public function register(): void {

        add_filter(
            'the_content',
            [$this, 'filter_content'],
            20
        );

        add_filter(
            'the_title',
            [$this, 'filter_title'],
            10,
            2
        );

        add_filter(
            'body_class',
            [$this, 'filter_body_class']
        );

        add_filter(
            'post_class',
            [$this, 'filter_post_class'],
            10,
            3
        );

        add_filter(
            'wp_nav_menu_css_class',
            [$this, 'filter_nav_menu_classes'],
            10,
            4
        );

        add_filter(
            'wp_nav_menu_link_attributes',
            [$this, 'filter_nav_menu_link_attributes'],
            10,
            4
        );
    }

    /**
     * Filter post content.
     *
     * Adds a WooShop content class wrapper to normal singular
     * post content while avoiding admin and feed contexts.
     *
     * @param string $content Post content.
     *
     * @return string
     */
    public function filter_content(
        string $content
    ): string {

        if (
            is_admin()
            || is_feed()
            || '' === trim($content)
        ) {
            return $content;
        }

        if (!is_singular()) {
            return $content;
        }

        return sprintf(
            '<div class="ws-entry-content">%s</div>',
            $content
        );
    }

    /**
     * Filter post titles.
     *
     * Prevents accidental empty title output when WordPress
     * passes a title containing only whitespace.
     *
     * @param string $title   Post title.
     * @param int    $post_id Post ID.
     *
     * @return string
     */
    public function filter_title(
        string $title,
        int $post_id
    ): string {

        if ('' === trim($title)) {
            return '';
        }

        return trim($title);
    }

    /**
     * Add WooShop classes to the document body.
     *
     * @param array<int, string> $classes Existing body classes.
     *
     * @return array<int, string>
     */
    public function filter_body_class(
        array $classes
    ): array {

        $classes[] = 'wooshop';

        if (is_front_page()) {
            $classes[] = 'wooshop-front-page';
        }

        if (function_exists( 'is_woocommerce_active' ) && is_woocommerce_active()) {
            $classes[] = 'wooshop-woocommerce';
        }

        return array_values(
            array_unique($classes)
        );
    }

    /**
     * Add WooShop classes to post elements.
     *
     * @param array<int, string> $classes    Existing post classes.
     * @param array<int, string> $class      Additional classes.
     * @param int                $post_id   Post ID.
     *
     * @return array<int, string>
     */
    public function filter_post_class(
        array $classes,
        array $class,
        int $post_id
    ): array {

        $classes[] = 'ws-entry';

        if (is_sticky($post_id)) {
            $classes[] = 'ws-entry-sticky';
        }

        return array_values(
            array_unique($classes)
        );
    }

    /**
     * Add WooShop navigation classes.
     *
     * @param array<int, string> $classes Existing menu classes.
     * @param WP_Post             $item    Menu item.
     * @param stdClass             $args    Menu arguments.
     * @param int                 $depth   Menu depth.
     *
     * @return array<int, string>
     */
    public function filter_nav_menu_classes(
        array $classes,
        \WP_Post $item,
        \stdClass $args,
        int $depth
    ): array {

        $classes[] = 'ws-menu-item';

        if (
            isset($item->current)
            && true === (bool) $item->current
        ) {
            $classes[] = 'ws-menu-item-current';
        }

        return array_values(
            array_unique($classes)
        );
    }

    /**
     * Add accessibility attributes to menu links.
     *
     * @param array<string, mixed> $atts  Link attributes.
     * @param WP_Post              $item  Menu item.
     * @param stdClass             $args  Menu arguments.
     * @param int                  $depth Menu depth.
     *
     * @return array<string, mixed>
     */
    public function filter_nav_menu_link_attributes(
        array $atts,
        \WP_Post $item,
        \stdClass $args,
        int $depth
    ): array {

        if (
            isset($item->current)
            && true === (bool) $item->current
        ) {
            $atts['aria-current'] = 'page';
        }

        return $atts;
    }

    /**
     * Determine whether WooCommerce is active.
     *
     * This helper is kept private to the module so the filter
     * layer does not produce a fatal error when WooCommerce
     * is unavailable.
     *
     * @return bool
     */
    private function is_woocommerce_active(): bool {

        return class_exists(
            '\WooCommerce'
        );
    }
}