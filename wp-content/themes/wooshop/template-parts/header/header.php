<?php
/**
 * Main Header Template
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<!doctype html>

<html <?php language_attributes(); ?>>

<head>

    <meta charset="<?php bloginfo('charset'); ?>">

    <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
    >

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<div id="page" class="site">

    <?php get_template_part('template-parts/header/topbar'); ?>

    <header
            id="masthead"
            class="site-header"
            role="banner"
    >

        <div class="container">

            <?php get_template_part('template-parts/header/branding'); ?>

            <?php get_template_part('template-parts/header/navigation'); ?>

            <?php get_template_part('template-parts/header/search'); ?>

            <?php get_template_part('template-parts/header/actions'); ?>

        </div>

    </header>

    <?php get_template_part('template-parts/header/mobile'); ?>

    <div id="content" class="site-content">