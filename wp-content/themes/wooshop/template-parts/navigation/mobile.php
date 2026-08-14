<?php
/**
 * Mobile Navigation
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

wp_nav_menu(
    [
        'theme_location' => 'mobile',
        'container'      => 'nav',
        'container_id'   => 'mobile-navigation',
        'container_class'=> 'mobile-navigation',
        'menu_class'     => 'mobile-menu',
        'fallback_cb'    => false,
    ]
);