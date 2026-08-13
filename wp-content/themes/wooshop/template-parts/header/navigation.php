<?php
/**
 * Primary Navigation
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<nav
        class="ws-navigation navbar navbar-expand-lg p-0"
        aria-label="<?php esc_attr_e( 'Primary navigation', 'wooshop' ); ?>">

    <?php
    wp_nav_menu(
            [
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'navbar-nav flex-row flex-wrap gap-1',
                    'fallback_cb'    => false,
                    'depth'          => 3,
            ]
    );
    ?>

</nav>