<?php
/**
 * Main Site Header
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<header
        id="masthead"
        class="site-header"
        role="banner"
>

    <?php
    /**
     * Header Top Bar.
     */
    do_action('wooshop_header_topbar');
    ?>

    <div class="header-main">

        <div class="container">

            <div class="row align-items-center g-3">

                <!-- Logo -->
                <div class="col-auto">

                    <?php
                    do_action('wooshop_header_branding');
                    ?>

                </div>

                <!-- Category Menu -->
                <div class="col-auto d-none d-lg-block">

                    <?php
                    do_action('wooshop_header_categories');
                    ?>

                </div>

                <!-- Search -->
                <div class="col">

                    <?php
                    do_action('wooshop_header_search');
                    ?>

                </div>

                <!-- Header Actions -->
                <div class="col-auto">

                    <?php
                    do_action('wooshop_header_actions');
                    ?>

                </div>

            </div>

        </div>

    </div>

    <nav
            class="header-navigation"
            aria-label="<?php esc_attr_e('Primary Navigation', 'wooshop'); ?>"
    >

        <div class="container">

            <?php
            do_action('wooshop_header_navigation');
            ?>

        </div>

    </nav>

    <?php
    /**
     * Announcement / Promotion Bar.
     */
    do_action('wooshop_header_announcement');
    ?>

    <?php
    /**
     * Mobile Menu.
     */
    do_action('wooshop_header_mobile');
    ?>

</header>