<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WooShop
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<div id="page" class="site">

    <?php get_template_part('template-parts/global/skip-links'); ?>

    <header id="masthead" class="site-header">

        <?php
        /**
         * Fires inside the site header.
         *
         * Hooked by Header::render_header() → loads
         * template-parts/header/header.php.
         *
         * @hooked WooShop\Modules\Theme\Header::render_header — 10
         */
        do_action('wooshop_header');
        ?>

    </header><!-- #masthead -->