<?php
/**
 * WooShop Product Archive Template
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

get_header('shop');
?>

    <main id="primary" class="site-main">

        <div class="woocommerce-shop py-5">

            <div class="container">

                <?php get_template_part('template-parts/woocommerce/shop/breadcrumb'); ?>

                <?php get_template_part('template-parts/woocommerce/shop/header'); ?>

                <div class="row">

                    <div class="woocommerce-sidebar-wrapper col-md-3">
                        <?php get_template_part('template-parts/woocommerce/shop/sidebar'); ?>
                    </div>

                    <div class="woocommerce-products-wrapper col-md-9">
                        <?php if (woocommerce_product_loop()) : ?>

                            <?php do_action('woocommerce_before_shop_loop'); ?>

                            <?php woocommerce_product_loop_start(); ?>

                            <?php while (have_posts()) : ?>

                                <?php the_post();

                                do_action('woocommerce_shop_loop');

                                get_template_part('template-parts/woocommerce/content-product');
                                //wc_get_template_part('content', 'product'); ?>

                            <?php endwhile; ?>

                            <?php woocommerce_product_loop_end(); ?>

                            <?php do_action('woocommerce_after_shop_loop'); ?>

                        <?php else : ?>

                            <?php do_action('woocommerce_no_products_found'); ?>

                        <?php endif; ?>

                    </div>
                </div>

            </div>

        </div>

    </main>

<?php get_footer('shop');

