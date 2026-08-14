<?php
/**
 * Primary Navigation
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

wp_nav_menu(
    [
        'theme_location' => 'primary',
        'container'      => 'nav',
        'container_id'   => 'site-navigation',
        'container_class'=> 'main-navigation',
        'menu_class'     => 'primary-menu',
        'fallback_cb'    => false,
    ]
);