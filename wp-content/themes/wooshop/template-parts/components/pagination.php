<?php
/**
 * Pagination Component
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

the_posts_pagination(
    [
        'mid_size'  => 2,
        'prev_text' => __( 'Previous', 'wooshop' ),
        'next_text' => __( 'Next', 'wooshop' ),
    ]
);