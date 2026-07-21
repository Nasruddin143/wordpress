<?php

namespace KT\Helpers;

class Terms
{
    public static function get_primary_term($post_id, $post_type = null)
    {
        $post_id = (int) $post_id;
        if (!$post_id) return null;

        $post_type = $post_type ?: get_post_type($post_id);

        $map = [
            'post' => 'category',
            'sewing_machine' => 'sewing_machine_category',
            'air_cooler' => 'air_cooler_category',
            'service' => 'service_category',
            'accessory' => 'accessory_category',
        ];

        if (empty($map[$post_type])) return null;

        $terms = get_the_terms($post_id, $map[$post_type]);

        if (empty($terms) || is_wp_error($terms)) return null;

        foreach ($terms as $term) {
            if ((int) $term->parent === 0) {
                return $term;
            }
        }

        return reset($terms);
    }

    public static function get_post_tags($post_id, $post_type = null)
    {
        $post_type = $post_type ?: get_post_type($post_id);

        $map = [
            'post' => 'post_tag',
            'sewing_machine' => 'sewing_machine_tag',
            'air_cooler' => 'air_cooler_tag',
            'service' => 'service_tag',
            'accessory' => 'accessory_tag',
        ];

        if (empty($map[$post_type])) return [];

        $terms = get_the_terms($post_id, $map[$post_type]);

        return (!empty($terms) && !is_wp_error($terms)) ? $terms : [];
    }
}
