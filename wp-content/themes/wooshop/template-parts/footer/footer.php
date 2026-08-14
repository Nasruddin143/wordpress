<?php
/**
 * Footer Template
 *
 * Displays the main site footer.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<footer
        id="colophon"
        class="site-footer"
        role="contentinfo">

    <?php do_action( 'wooshop_footer_top' ); ?>

    <?php do_action( 'wooshop_footer_newsletter' ); ?>

    <div class="ws-footer">

        <div class="ws-container">

            <?php do_action( 'wooshop_footer_widgets' ); ?>

        </div>

    </div>

    <div class="ws-footer-bottom">

        <div class="ws-container">

            <?php do_action( 'wooshop_footer_navigation' ); ?>

            <?php do_action( 'wooshop_footer_payment_icons' ); ?>

            <?php do_action( 'wooshop_footer_social' ); ?>

            <?php do_action( 'wooshop_footer_copyright' ); ?>

        </div>

    </div>

    <?php do_action( 'wooshop_footer_bottom' ); ?>

</footer>