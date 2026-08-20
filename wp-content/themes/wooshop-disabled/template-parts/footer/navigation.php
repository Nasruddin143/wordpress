<?php
/**
 * Footer Navigation
 *
 * Displays the footer navigation menu.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<nav
    class="ws-footer-navigation"
    aria-label="<?php esc_attr_e( 'Footer navigation', 'wooshop' ); ?>"
>

    <?php
    wp_nav_menu(
        array(
            'theme_location' => 'footer',
            'container'      => false,
            'menu_class'     => 'list-unstyled mb-0',
            'fallback_cb'    => false,
        )
    );
    ?>

</nav>