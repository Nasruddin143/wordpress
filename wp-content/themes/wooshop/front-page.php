<?php
/**
 * Front Page Template
 *
 * Displays the site's static front page.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

get_header(); ?>


<?php get_template_part('template-parts/components/home-slider'); ?>

<?php get_template_part('template-parts/components/info-card'); ?>

<?php get_template_part('template-parts/woocommerce/feature-products'); ?>

<?php get_footer();