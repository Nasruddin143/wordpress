<?php
/**
 * Pagination Helpers
 *
 * Handles WordPress pagination output.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render archive pagination.
 *
 * @return void
 */
function wooshop_pagination(): void
{
    $pagination = get_the_posts_pagination(
        [
            'mid_size'           => 2,
            'prev_text'          => __( 'Previous', 'wooshop' ),
            'next_text'          => __( 'Next', 'wooshop' ),
            'screen_reader_text' => __( 'Posts navigation', 'wooshop' ),
        ]
    );

    if ( empty( $pagination ) ) {
        return;
    }

    echo $pagination;
}