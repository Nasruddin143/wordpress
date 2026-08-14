<?php
/**
 * Entry Helpers
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wooshop_entry_title' ) ) {

    function wooshop_entry_title(): void {

        if ( is_singular() ) {

            the_title(
                '<h1 class="entry-title">',
                '</h1>'
            );

            return;
        }

        the_title(
            sprintf(
                '<h2 class="entry-title"><a href="%s" rel="bookmark">',
                esc_url( get_permalink() )
            ),
            '</a></h2>'
        );
    }
}