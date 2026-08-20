<?php
/**
 * WooShop Metadata Module
 *
 * Handles theme-level metadata and document head metadata
 * that is not managed by WordPress core or SEO plugins.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class Metadata
{
    /**
     * Register the metadata module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
            'wp_head',
            [$this, 'render_metadata'],
            1
        );
    }

    /**
     * Render WooShop theme metadata.
     *
     * Only lightweight, theme-level metadata is rendered here.
     * Page titles, descriptions, canonical URLs, Open Graph
     * metadata, and structured data should remain under WordPress
     * core or a dedicated SEO/WooCommerce module.
     *
     * @return void
     */
    public function render_metadata(): void
    {
        if (is_admin()) {
            return;
        }

        $this->render_generator_metadata();
    }

    /**
     * Render the WooShop generator metadata.
     *
     * @return void
     */
    private function render_generator_metadata(): void
    {
        printf(
            '<meta name="generator" content="%s">' . "\n",
            esc_attr(
                sprintf(
                /* translators: %s: WooShop theme version. */
                    __('WooShop %s', 'wooshop'),
                    $this->get_theme_version()
                )
            )
        );
    }

    /**
     * Retrieve the current WooShop theme version.
     *
     * @return string
     */
    private function get_theme_version(): string
    {
        $theme = wp_get_theme();

        $version = $theme->get('Version');

        return is_string($version) && $version !== ''
            ? $version
            : '1.0.0';
    }
}