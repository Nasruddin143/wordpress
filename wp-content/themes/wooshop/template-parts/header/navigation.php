<?php
/**
 * Header Navigation
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

wp_nav_menu(

    [
        'theme_location' => 'primary',

        'container'      => 'nav',

        'container_class'=> 'primary-navigation',

        'menu_class'     => 'navbar-nav',

        'fallback_cb'    => false,
    ]

);