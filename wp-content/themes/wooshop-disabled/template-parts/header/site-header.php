<?php
/**
 * Site Header Template
 *
 * Provides the main responsive WooShop header structure.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<header
    id="masthead"
    class="site-header ws-header border-bottom bg-white"
>

    <div class="container">

        <div class="row align-items-center g-3 py-3">

            <!-- Mobile Menu Toggle -->
            <div class="col-auto d-lg-none">

                <button
                    type="button"
                    class="btn btn-outline-secondary ws-menu-toggle"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#ws-mobile-navigation"
                    aria-controls="ws-mobile-navigation"
                    aria-label="<?php esc_attr_e( 'Open navigation', 'wooshop' ); ?>"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>

            </div>

            <!-- Branding -->
            <div class="col">

                <?php
                get_template_part(
                    'template-parts/header/branding'
                );
                ?>

            </div>

            <!-- Desktop Navigation -->
            <div class="col-12 col-lg-auto order-last order-lg-0">

                <?php
                get_template_part(
                    'template-parts/header/navigation'
                );
                ?>

            </div>

            <!-- Search -->
            <div class="col-12 col-lg">

                <?php
                get_template_part(
                    'template-parts/header/search'
                );
                ?>

            </div>

            <!-- Account / Cart -->
            <div class="col-auto">

                <?php
                get_template_part(
                    'template-parts/header/account-cart'
                );
                ?>

            </div>

        </div>

    </div>

</header>