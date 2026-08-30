<?php
/**
 * WooCommerce Archive Product.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

get_header();

do_action('woocommerce_before_main_content');
?>

<div class="container py-4 py-lg-5">

    <div class="row g-4">

        <main id="primary" class="col-12" aria-label="<?php esc_attr_e('Products', 'wooshop'); ?>">

            <header class="woocommerce-products-header mb-4">

                <?php if (apply_filters('woocommerce_show_page_title', true)): ?>

                    <h1 class="woocommerce-products-header__title h2 mb-3">
                        <?php woocommerce_page_title(); ?>
                    </h1>

                <?php endif; ?>

                <?php do_action('woocommerce_archive_description'); ?>

            </header>

            <?php if (woocommerce_product_loop()): ?>

                <div class="shop-toolbar mb-4">

                    <div class="row align-items-center g-3">

                        <div class="col-12 col-md">

                            <?php do_action('woocommerce_before_shop_loop'); ?>

                        </div>

                        <div class="col-12 col-md-auto">

                            <?php
                            /*
                             * Product filters, sorting, view switchers, etc.
                             * should be inserted by their respective
                             * registered modules.
                             */
                            do_action('wooshop_shop_toolbar');
                            ?>

                        </div>

                    </div>

                </div>

                <?php
                woocommerce_product_loop_start();

                if (wc_get_loop_prop('total')):

                    while (have_posts()):
                        the_post();

                        do_action('woocommerce_shop_loop');

                        wc_get_template_part('content', 'product');

                    endwhile;

                endif;

                woocommerce_product_loop_end();

                do_action('woocommerce_after_shop_loop');

                ?>

            <?php else: ?>

                <?php do_action('woocommerce_no_products_found'); ?>

            <?php endif; ?>

        </main>

    </div>

</div>

<?php

do_action('woocommerce_after_main_content');

get_footer();