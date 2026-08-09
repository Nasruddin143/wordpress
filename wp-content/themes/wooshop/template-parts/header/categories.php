<?php
/**
 * Header Categories
 *
 * Displays the category navigation.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! has_nav_menu( 'categories' ) ) {
    return;
}
?>

<nav
        class="ws-categories"
        aria-label="<?php esc_attr_e( 'Product Categories', 'wooshop' ); ?>">

    <?php
    wp_nav_menu(
            [
                    'theme_location' => 'categories',
                    'container'      => false,
                    'menu_class'     => 'ws-category-menu',
                    'fallback_cb'    => false,
            ]
    );
    ?>

</nav>