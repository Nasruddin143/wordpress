<?php
/**
 * WooShop Excerpt Module
 *
 * Controls WordPress excerpt length, more text, and excerpt
 * rendering for the WooShop theme.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Class Excerpt
 *
 * Handles WooShop excerpt functionality.
 */
final class Excerpt extends Module {

    /**
     * Default excerpt word length.
     *
     * @var int
     */
    private const DEFAULT_LENGTH = 24;

    /**
     * Maximum excerpt word length.
     *
     * @var int
     */
    private const MAX_LENGTH = 100;

    /**
     * Register excerpt functionality.
     *
     * @return void
     */
    public function register(): void {

        add_filter(
            'excerpt_length',
            [$this, 'filter_excerpt_length'],
            10
        );

        add_filter(
            'excerpt_more',
            [$this, 'filter_excerpt_more'],
            10
        );

        add_filter(
            'get_the_excerpt',
            [$this, 'filter_excerpt'],
            10
        );

        add_filter(
            'wp_trim_excerpt',
            [$this, 'filter_trimmed_excerpt'],
            10
        );
    }

    /**
     * Set the default excerpt length.
     *
     * @param int $length Current excerpt length.
     *
     * @return int
     */
    public function filter_excerpt_length(
        int $length
    ): int {

        if (
            is_admin()
            && ! wp_doing_ajax()
        ) {
            return $length;
        }

        return self::DEFAULT_LENGTH;
    }

    /**
     * Set the excerpt continuation text.
     *
     * The generated link is intentionally lightweight and points
     * to the current post.
     *
     * @param string $more Existing continuation text.
     *
     * @return string
     */
    public function filter_excerpt_more(
        string $more
    ): string {

        if (is_admin()) {
            return $more;
        }

        return sprintf(
            '&hellip; <a class="more-link" href="%s">%s</a>',
            esc_url(
                get_permalink()
            ),
            esc_html__(
                'Read more',
                'wooshop'
            )
        );
    }

    /**
     * Filter generated excerpts.
     *
     * Prevents excessive whitespace and normalizes the final
     * excerpt output.
     *
     * @param string $excerpt Generated excerpt.
     *
     * @return string
     */
    public function filter_excerpt(
        string $excerpt
    ): string {

        if ('' === trim($excerpt)) {
            return '';
        }

        return trim(
            preg_replace(
                '/\s+/u',
                ' ',
                $excerpt
            ) ?? $excerpt
        );
    }

    /**
     * Filter automatically generated excerpts.
     *
     * Ensures automatically generated excerpts remain within the
     * configured maximum length.
     *
     * @param string $text Generated excerpt.
     *
     * @return string
     */
    public function filter_trimmed_excerpt(
        string $text
    ): string {

        if ('' === trim($text)) {
            return $text;
        }

        $words = preg_split(
            '/\s+/u',
            trim($text),
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        if (false === $words) {
            return trim($text);
        }

        if (count($words) <= self::MAX_LENGTH) {
            return trim($text);
        }

        $words = array_slice(
            $words,
            0,
            self::MAX_LENGTH
        );

        return implode(
            ' ',
            $words
        );
    }
}