<?php
/**
 * Front Page Template
 *
 * WooShop homepage.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part(
    'template-parts/home/home'
);

get_footer();