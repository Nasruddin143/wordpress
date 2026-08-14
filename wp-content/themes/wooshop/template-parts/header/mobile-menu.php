<?php
/**
 * Mobile Menu
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div
        id="ws-mobile-menu"
        class="ws-mobile-menu d-lg-none"
        hidden>

    <div class="container py-3">

        <div class="d-flex align-items-center justify-content-between mb-3">

            <h2 class="h5 mb-0">
                <?php esc_html_e( 'Menu', 'wooshop' ); ?>
            </h2>

            <button
                    type="button"
                    class="btn btn-sm btn-outline-secondary ws-mobile-menu-close"
                    data-ws-toggle="mobile-menu"
                    aria-controls="ws-mobile-menu">

                <span aria-hidden="true">×</span>

                <span class="visually-hidden">
                    <?php esc_html_e( 'Close menu', 'wooshop' ); ?>
                </span>

            </button>

        </div>

        <nav
                aria-label="<?php esc_attr_e( 'Mobile navigation', 'wooshop' ); ?>">

            <?php
            wp_nav_menu(
                    [
                            'theme_location' => 'primary',
                            'container'      => false,
                            'menu_class'     => 'navbar-nav list-unstyled mb-0',
                            'fallback_cb'    => false,
                            'depth'          => 3,
                    ]
            );
            ?>

        </nav>

    </div>

</div>