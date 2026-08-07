<?php
/**
 * Mobile Menu
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<div
        class="offcanvas offcanvas-start ws-mobile-menu"
        tabindex="-1"
        id="wsMobileMenu"
        aria-labelledby="wsMobileMenuLabel">

    <!-- Header -->
    <div class="offcanvas-header">

        <h5
                class="offcanvas-title"
                id="wsMobileMenuLabel">

            <?php bloginfo('name'); ?>

        </h5>

        <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="<?php esc_attr_e('Close', 'wooshop'); ?>">
        </button>

    </div>

    <!-- Body -->
    <div class="offcanvas-body">

        <!-- Search -->
        <div class="ws-mobile-search">

            <?php do_action('wooshop_header_search'); ?>

        </div>

        <!-- Navigation -->
        <nav
                class="ws-mobile-navigation"
                aria-label="<?php esc_attr_e('Mobile Menu', 'wooshop'); ?>">

            <?php
            wp_nav_menu(
                    [
                            'theme_location' => 'primary',
                            'container' => false,
                            'menu_class' => 'navbar-nav',
                            'fallback_cb' => false,
                            'depth' => 2,
                    ]
            );
            ?>

        </nav>

        <!-- Divider -->
        <hr>

        <!-- Account Links -->
        <div class="ws-mobile-account">

            <a href="<?php echo esc_url(wp_login_url()); ?>">

                <?php esc_html_e('My Account', 'wooshop'); ?>

            </a>

        </div>

    </div>

</div>