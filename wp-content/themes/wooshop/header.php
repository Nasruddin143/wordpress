<?php
/**
 * Theme Header
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

    <!doctype html>
<html <?php language_attributes(); ?>>

    <head>

        <meta charset="<?php bloginfo( 'charset' ); ?>">

        <meta
                name="viewport"
                content="width=device-width, initial-scale=1"
        >

        <?php wp_head(); ?>

    </head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

    <a
            class="skip-link screen-reader-text"
            href="#primary">

        <?php esc_html_e( 'Skip to content', 'wooshop' ); ?>

    </a>

<?php do_action( 'wooshop_before_header' ); ?>

<?php do_action( 'wooshop_header' ); ?>

<?php do_action( 'wooshop_after_header' ); ?>