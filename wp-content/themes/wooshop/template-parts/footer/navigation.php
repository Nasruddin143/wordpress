<?php
/**
 * Footer Navigation
 *
 * Displays the footer navigation menu.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! has_nav_menu( 'footer' ) ) {
    return;
}
?>

<nav
        class="ws-footer-navigation"
        aria-label="<?php esc_attr_e( 'Footer Navigation', 'wooshop' ); ?>">

    <?php
    wp_nav_menu(
            [
                    'theme_location' => 'footer',
                    'container'      => false,
                    'menu_class'     => 'ws-footer-menu',
                    'fallback_cb'    => false,
            ]
    );
    ?>

</nav>