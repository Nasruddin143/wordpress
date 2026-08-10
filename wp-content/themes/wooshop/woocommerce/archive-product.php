<?php
/**
 * WooCommerce Product Archive
 *
 * Shop, product category and product tag archives.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

    <main
            id="primary"
            class="site-main ws-shop-main">

        <div class="ws-container">

            <?php
            /**
             * Before shop content.
             */
            do_action( 'woocommerce_before_main_content' );
            ?>

            <?php
            get_template_part(
                    'template-parts/woocommerce/shop/archive-header'
            );
            ?>

            <?php
            if ( woocommerce_product_loop() ) :
                ?>

                <?php
                do_action( 'woocommerce_before_shop_loop' );
                ?>

                <?php
                get_template_part(
                        'template-parts/woocommerce/shop/loop-start'
                );
                ?>

                <?php
                while ( have_posts() ) :
                    the_post();

                    do_action( 'woocommerce_shop_loop' );

                    wc_get_template_part(
                            'content',
                            'product'
                    );

                endwhile;
                ?>

                <?php
                get_template_part(
                        'template-parts/woocommerce/shop/loop-end'
                );
                ?>

                <?php
                do_action( 'woocommerce_after_shop_loop' );
                ?>

            <?php else : ?>

                <?php
                do_action( 'woocommerce_no_products_found' );
                ?>

            <?php endif; ?>

            <?php
            do_action( 'woocommerce_after_main_content' );
            ?>

        </div>

    </main>

<?php
get_footer( 'shop' );