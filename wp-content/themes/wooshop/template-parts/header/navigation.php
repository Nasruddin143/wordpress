<?php
/**
 * Header Navigation
 *
 * Displays the primary navigation.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! has_nav_menu( 'primary' ) ) {
    return;
}
?>

<nav
        class="ws-navigation"
        aria-label="<?php esc_attr_e( 'Primary Navigation', 'wooshop' ); ?>">

    <?php
    wp_nav_menu(
            [
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'ws-primary-menu',
                    'fallback_cb'    => false,
            ]
    );
    ?>

</nav>