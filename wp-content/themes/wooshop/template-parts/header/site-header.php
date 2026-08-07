<?php
/**
 * Site Header
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<header id="masthead"
        class="site-header"
        role="banner">

    <?php do_action('wooshop_before_header'); ?>

    <!-- Main Header -->
    <div class="ws-header-main">

        <div class="container">

            <div class="ws-header">

                <?php do_action( 'wooshop_header_branding' ); ?>

                <?php do_action( 'wooshop_header_categories' ); ?>

                <?php do_action( 'wooshop_header_search' ); ?>

                <?php do_action( 'wooshop_header_actions' ); ?>

                <?php get_template_part(
                        'template-parts/header/mobile-toggle'
                ); ?>

            </div>

        </div>

    </div>

    <!-- Navigation -->
    <div class="ws-header-navigation">

        <?php do_action('wooshop_header_navigation'); ?>

    </div>

    <?php do_action('wooshop_after_header'); ?>

    <?php
    get_template_part(
            'template-parts/header/mobile-menu'
    );
    ?>

</header>