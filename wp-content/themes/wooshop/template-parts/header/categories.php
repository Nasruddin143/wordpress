<?php
/**
 * Category Navigation
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<nav
        class="ws-categories navbar navbar-expand-lg border-top py-2"
        aria-label="<?php esc_attr_e( 'Category navigation', 'wooshop' ); ?>">

    <div class="container-fluid px-0">

        <?php
        wp_nav_menu(
                [
                        'theme_location' => 'categories',
                        'container'      => false,
                        'menu_class'     => 'navbar-nav flex-row flex-wrap gap-2',
                        'fallback_cb'    => false,
                        'depth'          => 3,
                ]
        );
        ?>

    </div>

</nav>