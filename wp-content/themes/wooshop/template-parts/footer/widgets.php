<?php
/**
 * Footer Widgets
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_active_sidebar( 'footer-1' )
    && ! is_active_sidebar( 'footer-2' )
    && ! is_active_sidebar( 'footer-3' )
    && ! is_active_sidebar( 'footer-4' ) ) {
    return;
}
?>

<div class="footer-widgets">

    <?php dynamic_sidebar( 'footer-1' ); ?>

    <?php dynamic_sidebar( 'footer-2' ); ?>

    <?php dynamic_sidebar( 'footer-3' ); ?>

    <?php dynamic_sidebar( 'footer-4' ); ?>

</div>