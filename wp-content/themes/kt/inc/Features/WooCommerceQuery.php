<?php

namespace KT\Features;

defined('ABSPATH') || exit;

class WooCommerceQuery
{
    /**
     * Base Query Args
     */
    private static function base($limit = 10)
    {
        return [
            'post_type' => 'product',
            'posts_per_page' => $limit,
            'post_status' => 'publish',
        ];
    }

    /**
     * Featured Products
     */
    public static function featured($limit = 10)
    {
        $args = self::base($limit);

        $args['tax_query'] = [
            [
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms' => 'featured',
            ]
        ];

        return new \WP_Query($args);
    }

    /**
     * New Arrivals
     */
    public static function latest($limit = 10)
    {
        $args = self::base($limit);

        $args['orderby'] = 'date';
        $args['order'] = 'DESC';

        return new \WP_Query($args);
    }

    /**
     * Best Sellers
     */
    public static function best_sellers($limit = 10)
    {
        $args = self::base($limit);

        $args['meta_key'] = 'total_sales';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';

        return new \WP_Query($args);
    }

    /**
     * Trending (based on your custom meta)
     */
    public static function trending($limit = 10)
    {
        $args = self::base($limit);

        $args['meta_key'] = '_kt_views';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';

        return new \WP_Query($args);
    }

    /**
     * Top Rated
     */
    public static function top_rated($limit = 10)
    {
        $args = self::base($limit);

        $args['meta_key'] = '_wc_average_rating';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';

        return new \WP_Query($args);
    }


    /**
     * Get Brands (Taxonomy Terms)
     */
    public static function brands($limit = 12, $taxonomy = 'product_brand')
    {
        $args = [
            'taxonomy'   => $taxonomy,
            'hide_empty' => true,
            'number'     => $limit,
        ];

        return get_terms($args);
    }


    /**
     * Top Brands (by product count)
     */
    public static function top_brands($limit = 12, $taxonomy = 'product_brand')
    {
        return get_terms([
            'taxonomy'   => $taxonomy,
            'hide_empty' => true,
            'number'     => $limit,
            'orderby'    => 'count',
            'order'      => 'DESC',
        ]);
    }


    /**
     * Get Brands (Taxonomy Terms)
     */
    public static function product_categories($limit = 12, $taxonomy = 'product_cat')
    {
        $args = [
            'taxonomy'   => $taxonomy,
            'hide_empty' => true,
            'number'     => $limit,
            // 'parent' => 0,
        ];

        return get_terms($args);
    }
}
