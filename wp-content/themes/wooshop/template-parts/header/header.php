<?php
/**
 * Site Header
 *
 * Displays the main site header.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<header
        id="masthead"
        class="site-header"
        role="banner">

    <div class="ws-container">

        <?php do_action( 'wooshop_header_branding' ); ?>

        <?php do_action( 'wooshop_header_navigation' ); ?>

        <?php do_action( 'wooshop_header_categories' ); ?>

        <?php do_action( 'wooshop_header_search' ); ?>

        <?php do_action( 'wooshop_header_actions' ); ?>

    </div>

</header>