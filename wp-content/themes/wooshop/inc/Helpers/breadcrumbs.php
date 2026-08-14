<?php
/**
 * Breadcrumb Helpers
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wooshop_breadcrumbs' ) ) {

    function wooshop_breadcrumbs(): void {

        do_action( 'wooshop_breadcrumbs' );
    }
}