<?php
/**
 * Site Header
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<header id="masthead" class="site-header">

    <?php do_action('wooshop_header_before'); ?>

    <div class="container">

        <div class="ws-header">

            <?php do_action('wooshop_header_branding'); ?>

            <?php do_action('wooshop_header_categories'); ?>

            <?php do_action('wooshop_header_search'); ?>

            <?php do_action('wooshop_header_actions'); ?>

        </div>

    </div>

    <?php do_action('wooshop_header_navigation'); ?>

    <?php do_action('wooshop_header_after'); ?>

</header>