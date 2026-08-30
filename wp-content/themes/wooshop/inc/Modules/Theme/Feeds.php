<?php
/**
 * WooShop Feeds Module
 *
 * Removes unnecessary feed links from the <head> that
 * typical WooCommerce stores do not need, keeping the
 * HTML output clean and reducing HTTP overhead.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Feeds
 */
final class Feeds extends Module
{
    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void
    {
        add_action('init',    [$this, 'remove_feed_links']);
        add_action('wp_head', [$this, 'remove_rsd_link'], 1);
    }

    /**
     * Remove comment and extra feed links injected by WordPress.
     *
     * The main RSS feed link is kept; comment feeds and the
     * Windows Live Writer manifest are removed as they add no
     * value for a typical e-commerce store.
     *
     * @return void
     */
    public function remove_feed_links(): void
    {
        // Comment feed.
        remove_action('wp_head', 'feed_links_extra', 3);

        // Windows Live Writer manifest link.
        remove_action('wp_head', 'wlwmanifest_link');

        // EditURI/RSD link (XML-RPC endpoint).
        remove_action('wp_head', 'rsd_link');

        // Shortlink.
        remove_action('wp_head', 'wp_shortlink_wp_head');

        // REST API link (output from wp_head; API stays active).
        remove_action('wp_head', 'rest_output_link_wp_head');

        // oEmbed discovery links.
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
    }

    /**
     * Remove the RSD link via wp_head hook as a safety net
     * in case a plugin re-adds it after 'init'.
     *
     * @return void
     */
    public function remove_rsd_link(): void
    {
        remove_action('wp_head', 'rsd_link');
    }
}