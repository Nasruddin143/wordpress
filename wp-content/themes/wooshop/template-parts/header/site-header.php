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

                <!-- Logo -->
                <?php do_action('wooshop_header_branding'); ?>

                <!-- Categories -->
                <?php do_action('wooshop_header_categories'); ?>

                <!-- Search -->
                <?php do_action('wooshop_header_search'); ?>

                <!-- Header Actions -->
                <?php do_action('wooshop_header_actions'); ?>

            </div>

        </div>

    </div>

    <!-- Navigation -->
    <div class="ws-header-navigation">

        <?php do_action('wooshop_header_navigation'); ?>

    </div>

    <?php do_action('wooshop_after_header'); ?>

</header>