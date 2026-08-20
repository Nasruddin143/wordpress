<?php
/**
 * WooShop Embeds Module
 *
 * Configures WordPress embed functionality and optimizes
 * embed-related frontend behavior for the WooShop theme.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class Embeds
{
    /**
     * Register the embeds module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
            'wp_enqueue_scripts',
            [$this, 'dequeue_embed_script'],
            100
        );

        add_action(
            'wp_head',
            [$this, 'disable_embeds_rewrites'],
            1
        );

        add_filter(
            'embed_oembed_discover',
            [$this, 'disable_oembed_discovery']
        );

        add_filter(
            'tiny_mce_plugins',
            [$this, 'disable_embed_tiny_mce_plugin']
        );
    }

    /**
     * Dequeue the WordPress embed script.
     *
     * The script is unnecessary when the theme does not require
     * WordPress's frontend embed JavaScript functionality.
     *
     * @return void
     */
    public function dequeue_embed_script(): void
    {
        if (!wp_script_is('wp-embed', 'enqueued')) {
            return;
        }

        wp_dequeue_script('wp-embed');
    }

    /**
     * Disable the frontend embed rewrite endpoint.
     *
     * @return void
     */
    public function disable_embeds_rewrites(): void
    {
        remove_action(
            'rest_api_init',
            'wp_oembed_register_route'
        );

        remove_filter(
            'oembed_dataparse',
            'wp_filter_oembed_result',
            10
        );

        remove_action(
            'wp_head',
            'wp_oembed_add_discovery_links'
        );

        remove_action(
            'wp_head',
            'wp_oembed_add_host_js'
        );

        remove_action(
            'template_redirect',
            'wp_oembed_add_proxy_discovery_links'
        );
    }

    /**
     * Disable automatic oEmbed discovery.
     *
     * @param bool $discover Whether oEmbed discovery is enabled.
     *
     * @return bool
     */
    public function disable_oembed_discovery(bool $discover): bool
    {
        return false;
    }

    /**
     * Remove the embed TinyMCE plugin.
     *
     * @param array<int, string> $plugins TinyMCE plugins.
     *
     * @return array<int, string>
     */
    public function disable_embed_tiny_mce_plugin(array $plugins): array
    {
        return array_values(
            array_diff(
                $plugins,
                ['wpembed']
            )
        );
    }
}