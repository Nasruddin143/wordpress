<?php
/**
 * Site Header
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<header
        id="masthead"
        class="ws-header site-header border-bottom bg-white">

    <div class="container">

        <div class="row align-items-center g-3 py-3">

            <div class="col-auto d-lg-none">

                <button
                        type="button"
                        class="btn btn-outline-secondary ws-mobile-menu-toggle"
                        data-ws-toggle="mobile-menu"
                        aria-controls="ws-mobile-menu"
                        aria-expanded="false">

                    <span class="visually-hidden">
                        <?php esc_html_e( 'Open menu', 'wooshop' ); ?>
                    </span>

                    <span class="ws-icon ws-icon-menu" aria-hidden="true">
                        ☰
                    </span>

                </button>

            </div>

            <div class="col-auto col-lg-3">

                <?php do_action( 'wooshop_header_branding' ); ?>

            </div>

            <div class="col-12 col-lg-6 order-3 order-lg-2">

                <?php do_action( 'wooshop_header_search' ); ?>

            </div>

            <div class="col-auto ms-auto order-2 order-lg-3">

                <?php do_action( 'wooshop_header_actions' ); ?>

            </div>

        </div>

        <div class="row">

            <div class="col">

                <?php do_action( 'wooshop_header_navigation' ); ?>

            </div>

        </div>

        <div class="row">

            <div class="col">

                <?php do_action( 'wooshop_header_categories' ); ?>

            </div>

        </div>

    </div>

    <?php get_template_part( 'template-parts/header/mobile-menu' ); ?>

</header>