<?php
/**
 * Page Template
 *
 * Displays a standard WordPress page using the shared layout.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<?php
get_template_part(
        'template-parts/layout/page-layout'
);
?>

<?php
get_footer();