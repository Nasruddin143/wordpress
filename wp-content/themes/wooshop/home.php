<?php
/**
 * Blog Home / Posts Index Template
 *
 * Displays the site's posts index.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part(
        'template-parts/blog/blog'
);

get_footer();