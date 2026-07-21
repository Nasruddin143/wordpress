<?php
/**
 * The template for displaying Front-Page
 *
 * This is the template that displays Front-Page by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WooShop
 */

get_header();
?>

<main id="primary" class="site-main front-page woocommerce">

    <?php echo do_shortcode('[bootstrap_slider]'); ?>

    <?php get_template_part('template-parts/home', 'category'); ?>

    <?php get_template_part('template-parts/home', 'featured'); ?>

    <?php get_template_part('template-parts/home', 'branding'); ?>

</main><!-- #main -->

<?php get_footer();