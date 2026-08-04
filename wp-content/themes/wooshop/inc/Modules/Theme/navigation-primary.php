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

        'container'      => false,

        'menu_class'     => 'navbar-nav ws-navbar-nav',

        'fallback_cb'    => false,

        'depth'          => 3,

    ]

);