<?php
/**
 * Template Tags
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wooshop_site_logo' ) ) {

    function wooshop_site_logo(): void {

        if ( has_custom_logo() ) {

            the_custom_logo();

            return;
        }

        printf(
            '<a class="site-title" href="%s">%s</a>',
            esc_url( home_url( '/' ) ),
            esc_html( get_bloginfo( 'name' ) )
        );
    }
}