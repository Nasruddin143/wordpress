<?php
/**
 * WooShop Feeds Module
 *
 * Configures WordPress feed behavior for the WooShop theme,
 * including feed content, metadata, and unnecessary feed
 * header cleanup.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Feeds
 *
 * Handles WooShop feed functionality.
 */
final class Feeds extends Module {

    /**
     * Register feed functionality.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'after_setup_theme',
            [$this, 'register_feed_support'],
            20
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
            'feed_links_show_posts_feed',
            [$this, 'filter_posts_feed_link']
        );

        add_filter(
            'feed_links_show_comments_feed',
            [$this, 'filter_comments_feed_link']
        );

        add_action(
            'wp_head',
            [$this, 'remove_feed_generator'],
            1
        );
    }

    /**
     * Register feed support.
     *
     * WordPress already provides RSS and Atom feeds. This method
     * ensures the theme explicitly declares feed support.
     *
     * @return void
     */
    public function register_feed_support(): void {

        add_theme_support('automatic-feed-links');
    }

    /**
     * Filter RSS excerpt content.
     *
     * Keeps feed excerpts clean while preserving the post
     * permalink for readers and feed clients.
     *
     * @param string $excerpt RSS excerpt.
     *
     * @return string
     */
    public function filter_excerpt_rss(
        string $excerpt
    ): string {

        $excerpt = trim($excerpt);

        if ('' === $excerpt) {
            return $excerpt;
        }

        return wpautop($excerpt);
    }

    /**
     * Filter RSS full content.
     *
     * Adds the post permalink after the feed content so readers
     * can easily continue to the original WooShop page.
     *
     * @param string $content RSS post content.
     *
     * @return string
     */
    public function filter_content_feed(
        string $content
    ): string {

        $permalink = get_permalink();

        if (false === $permalink) {
            return $content;
        }

        $read_more = sprintf(
            '<p class="feed-read-more"><a href="%s">%s</a></p>',
            esc_url($permalink),
            esc_html__(
                'Read more',
                'wooshop'
            )
        );

        return $content . $read_more;
    }

    /**
     * Control the posts feed link.
     *
     * @param bool $show Whether the posts feed link should show.
     *
     * @return bool
     */
    public function filter_posts_feed_link(
        bool $show
    ): bool {

        return true;
    }

    /**
     * Control the comments feed link.
     *
     * Comments feeds are not required in the primary storefront
     * header and therefore remain disabled.
     *
     * @param bool $show Whether the comments feed link should show.
     *
     * @return bool
     */
    public function filter_comments_feed_link(
        bool $show
    ): bool {

        return false;
    }

    /**
     * Remove the WordPress generator meta tag.
     *
     * Reduces unnecessary metadata in the document head.
     *
     * @return void
     */
    public function remove_feed_generator(): void {

        remove_action(
            'wp_head',
            'wp_generator'
        );
    }
}