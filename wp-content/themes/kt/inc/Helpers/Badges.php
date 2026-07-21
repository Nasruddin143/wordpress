<?php

namespace KT\Helpers;

defined('ABSPATH') || exit;

class Badges
{
    /**
     * Get badges HTML
     */
    public static function get($post_id = null)
    {
        $post_id = $post_id ?: get_the_ID();

        if (!$post_id) {
            return '';
        }

        $data = self::get_data($post_id);

        $badges = [];

        if (self::is_top_rated($data)) {
            $badges[] = self::badge('Top Rated', 'success', 'top-rated');
        }

        if (self::is_best_seller($post_id)) {
            $badges[] = self::badge('Best Seller', 'danger', 'best-seller');
        }

        if (self::is_trending($data)) {
            $badges[] = self::badge('Trending', 'warning', 'trending');
        }

        if (self::is_new($data)) {
            $badges[] = self::badge('New', 'info', 'new');
        }

        return implode(' ', $badges);
    }

    /**
     * Collect meta data (single DB hit logic grouping)
     */
    private static function get_data($post_id)
    {
        return [
            'rating' => (float) get_post_meta($post_id, '_kt_avg_rating', true),
            'count'  => (int) get_post_meta($post_id, '_kt_review_count', true),
            'views'  => (int) get_post_meta($post_id, '_kt_views', true),
            'date'   => get_the_date('U', $post_id),
        ];
    }

    /**
     * Badge HTML generator
     */
    private static function badge($text, $color, $class)
    {
        return sprintf(
            '<span class="badge bg-%1$s-subtle text-%1$s-emphasis rounded-pill %2$s">%3$s</span>',
            esc_attr($color),
            esc_attr($class),
            esc_html__($text, 'kt')
        );
    }

    /**
     * CONDITIONS
     */

    private static function is_top_rated($d)
    {
        return $d['rating'] >= 4.5 && $d['count'] >= 5;
    }

    private static function is_best_seller($post_id)
    {
        return (bool) get_post_meta($post_id, '_kt_best_seller', true);
    }

    private static function is_trending($d)
    {
        return $d['views'] > 50 && $d['rating'] >= 4;
    }

    private static function is_new($d)
    {
        return (time() - $d['date']) < (7 * DAY_IN_SECONDS);
    }
}