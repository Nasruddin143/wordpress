<?php
/**
 * The header for our theme
 *
 * This is the template that displays all the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<!--Topbar-->
<div class="topbar py-2 bg-primary bg-opacity-10">
    <?php get_template_part('template-parts/header/topbar'); ?>
</div>

<!-- Main Header -->
<div class="header-container">

    <div class="container-fluid container-xl">

        <div class="d-flex align-items-center justify-content-between py-4">

            <?php get_template_part('template-parts/header/branding'); ?>

            <?php get_template_part('template-parts/header/search'); ?>

            <?php do_action('wooshop_header_actions'); ?>

        </div>

    </div>

    <?php get_template_part('template-parts/header/navigation'); ?>

</div>