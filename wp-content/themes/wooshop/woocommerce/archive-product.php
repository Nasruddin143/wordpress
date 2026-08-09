<?php
/**
 * Product Archive Template
 *
 * Displays WooCommerce product archives and the shop page.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

get_header('shop');
?>

    <main
            id="primary"
            class="site-main ws-shop-main">

        <div class="ws-container">

            <?php
            /**
             * WooCommerce archive header.
             */
            do_action('woocommerce_before_main_content');
            ?>

            <header class="ws-shop-header">

                <?php
                do_action('woocommerce_shop_loop_header');
                ?>

            </header>

            <?php
            if (woocommerce_product_loop()) :

                /**
                 * Before product loop.
                 */
                do_action('woocommerce_before_shop_loop');
                ?>

                <div class="ws-product-archive">

                    <?php
                    woocommerce_product_loop_start();

                    if (wc_get_loop_prop('total')) :

                        while (have_posts()) :

                            the_post();

                            do_action(
                                    'woocommerce_shop_loop'
                            );

                            wc_get_template_part(
                                    'content',
                                    'product'
                            );

                        endwhile;

                    endif;

                    woocommerce_product_loop_end();
                    ?>

                </div>

                <?php
                /**
                 * After product loop.
                 */
                do_action('woocommerce_after_shop_loop');

            else :

                /**
                 * No products found.
                 */
                do_action('woocommerce_no_products_found');

            endif;

            /**
             * WooCommerce archive footer.
             */
            do_action('woocommerce_after_main_content');
            ?>

        </div>

    </main>

<?php
get_footer('shop');