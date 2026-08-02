<?php
/**
 * Footer Navigation
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

wp_nav_menu(
    [
        'theme_location' => 'footer',
        'container'      => 'nav',
        'container_class'=> 'footer-navigation',
        'menu_class'     => 'footer-menu',
        'fallback_cb'    => false,
    ]
);