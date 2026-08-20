<?php
/**
 * Primary Navigation
 *
 * Displays the primary WordPress navigation menu.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<nav
    class="ws-navigation primary-navigation"
    aria-label="<?php esc_attr_e( 'Primary navigation', 'wooshop' ); ?>"
>

    <?php
    wp_nav_menu(
        array(
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'nav justify-content-center gap-3',
            'fallback_cb'    => false,
        )
    );
    ?>

</nav>