<?php
/**
 * WooShop Performance Module
 *
 * Provides lightweight frontend performance optimizations
 * for the WooShop theme without introducing additional
 * database queries or unnecessary asset requests.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class Performance
{
    /**
     * Register the performance module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
            'wp_enqueue_scripts',
            [$this, 'optimize_frontend_assets'],
            100
        );

        add_filter(
            'wp_resource_hints',
            [$this, 'filter_resource_hints'],
            10,
            2
        );

        add_filter(
            'script_loader_tag',
            [$this, 'filter_script_loader_tag'],
            10,
            3
        );

        add_filter(
            'style_loader_tag',
            [$this, 'filter_style_loader_tag'],
            10,
            4
        );
    }

    /**
     * Optimize frontend assets.
     *
     * This method intentionally does not dequeue theme or
     * WooCommerce assets. Asset loading is controlled by the
     * WooShop Smart Asset Loading system.
     *
     * @return void
     */
    public function optimize_frontend_assets(): void
    {
        if (is_admin()) {
            return;
        }

        /*
         * Frontend asset optimization is handled by the
         * Smart Asset Loading module.
         *
         * This method remains as the central performance
         * extension point for future optimizations.
         */
    }

    /**
     * Filter WordPress resource hints.
     *
     * Prevents unnecessary automatic DNS prefetching from
     * being introduced by the theme.
     *
     * @param array<int|string, mixed> $urls  Resource hint URLs.
     * @param string                   $relation Resource hint relation.
     *
     * @return array<int|string, mixed>
     */
    public function filter_resource_hints(
        array $urls,
        string $relation
    ): array {
        if ($relation !== 'dns-prefetch') {
            return $urls;
        }

        return array_values($urls);
    }

    /**
     * Add performance attributes to selected scripts.
     *
     * WordPress and WooShop remain responsible for determining
     * dependencies. Scripts are not blindly marked async because
     * doing so could break dependency execution order.
     *
     * @param string $tag    Script HTML.
     * @param string $handle Script handle.
     * @param string $src    Script source.
     *
     * @return string
     */
    public function filter_script_loader_tag(
        string $tag,
        string $handle,
        string $src
    ): string {
        if (
            str_starts_with($handle, 'wooshop-')
            && !str_contains($tag, ' defer')
        ) {
            $tag = str_replace(
                '<script ',
                '<script defer ',
                $tag
            );
        }

        return $tag;
    }

    /**
     * Add loading optimization attributes to selected styles.
     *
     * Theme styles are kept as normal blocking styles because
     * converting CSS to preload requires an additional loading
     * strategy and can increase requests or cause FOUC.
     *
     * @param string $html    Style HTML.
     * @param string $handle  Style handle.
     * @param string $href    Stylesheet URL.
     * @param string $media   Media attribute.
     *
     * @return string
     */
    public function filter_style_loader_tag(
        string $html,
        string $handle,
        string $href,
        string $media
    ): string {
        return $html;
    }
}