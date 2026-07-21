<?php

namespace KT\Helpers;

defined('ABSPATH') || exit;

class Ratings
{
    /**
     * ----------------------------------------
     * GET RATING STARS ★★★☆☆
     * ----------------------------------------
     */
    public static function rating_stars($post_id)
    {
        $reviews = apply_filters('glsr_get_reviews', null, [
            'assigned_posts' => $post_id,
            'status' => 'approved',
            //'post_type' => $post_type,
        ]);

        $total_reviews = $reviews->total ?? 0;
        $reviews_list  = $reviews->reviews ?? [];

        $total_rating = 0;

        if (!empty($reviews_list)) {
            foreach ($reviews_list as $review) {
                $total_rating += (int) $review->rating;
            }
        }

        $average = $total_reviews > 0 ? round($total_rating / $total_reviews, 1) : 0;

        // Generate stars
        //$stars_html = apply_filters('glsr_star_rating', '', $average);
        $full_stars = floor($average);
        $stars_html = str_repeat('★', $full_stars);
        $stars_html .= str_repeat('☆', 5 - $full_stars);


        return '<div class="kt-rating-stars"><span class="stars">' . $stars_html . '</span></div>';
    }


    /**
     * ----------------------------------------
     * GET FULL RATING STARS FULL ★★★☆☆
     * ----------------------------------------
     */
    public static function ratings_full($post_id)
    {
        $reviews = apply_filters('glsr_get_reviews', null, [
            'assigned_posts' => $post_id,
            'status' => 'approved',
            //'post_type' => $post_type,
        ]);

        $total_reviews = $reviews->total ?? 0;
        $reviews_list  = $reviews->reviews ?? [];

        $total_rating = 0;

        if (!empty($reviews_list)) {
            foreach ($reviews_list as $review) {
                $total_rating += (int) $review->rating;
            }
        }

        $average = $total_reviews > 0 ? round($total_rating / $total_reviews, 1) : 0;

        // Generate stars
        //$stars_html = apply_filters('glsr_star_rating', '', $average);
        $full_stars = floor($average);
        $stars_html = str_repeat('★', $full_stars);
        $stars_html .= str_repeat('☆', 5 - $full_stars);

        return '<div class="custom-review-summary"><div class="rating"><strong>' . $total_reviews . '</strong><span class="stars mx-2">' . $stars_html . '</span>' . $average . ' out of 5 stars (based on ' . $total_reviews . ' reviews)</div></div>';
    }
}
