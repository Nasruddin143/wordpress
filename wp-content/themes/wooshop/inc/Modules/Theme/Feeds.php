<?php
/**
 * WooShop Feeds Module
 *
 * Configures WordPress feed behavior for the WooShop theme.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

final class Feeds
{
    /**
     * Register the feeds module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
            'after_setup_theme',
            [$this, 'register_feed_support']
        );

        add_filter(
            'the_excerpt_rss',
            [$this, 'filter_excerpt_rss']
        );

        add_filter(
            'the_content_feed',
            [$this, 'filter_content_feed']
        );

        add_filter(
            'the_title_rss',
            [$this, 'filter_title_rss']
        );
    }

    /**
     * Register feed support.
     *
     * WordPress feeds are supported by default, so this method
     * provides a dedicated extension point for WooShop feed setup.
     *
     * @return void
     */
    public function register_feed_support(): void
    {
        /*
         * WordPress feed support is enabled by default.
         * Keep this method available for future feed configuration.
         */
    }

    /**
     * Filter the RSS excerpt.
     *
     * @param string $excerpt RSS excerpt.
     *
     * @return string
     */
    public function filter_excerpt_rss(string $excerpt): string
    {
        return wp_strip_all_tags($excerpt);
    }

    /**
     * Filter the RSS post content.
     *
     * @param string $content RSS content.
     *
     * @return string
     */
    public function filter_content_feed(string $content): string
    {
        return wp_kses_post($content);
    }

    /**
     * Filter the RSS post title.
     *
     * @param string $title RSS title.
     *
     * @return string
     */
    public function filter_title_rss(string $title): string
    {
        return wp_strip_all_tags($title);
    }
}