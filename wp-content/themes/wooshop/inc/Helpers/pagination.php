<?php
/**
 * Pagination Helpers
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wooshop_pagination' ) ) {

    function wooshop_pagination(): void {

        the_posts_pagination(
            [
                'mid_size'  => 2,
                'prev_text' => __( 'Previous', 'wooshop' ),
                'next_text' => __( 'Next', 'wooshop' ),
            ]
        );
    }
}