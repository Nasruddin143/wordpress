<?php
/**
 * WooCommerce Single Product Template
 *
 * Provides the main WooShop single-product structure.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
    the_post();

    get_template_part(
        'template-parts/woocommerce/single-product/product-layout'
    );

endwhile;

get_footer();