<?php
/**
 * Single Product Template.
 *
 * Displays a WooCommerce single product page.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

    <main
        id="primary"
        class="site-main ws-single-product-main">

        <div class="ws-container">

            <?php
            /**
             * WooCommerce content opening.
             *
             * Handles breadcrumbs and other registered
             * WooCommerce integrations.
             */
            do_action( 'woocommerce_before_main_content' );
            ?>

            <?php
            while ( have_posts() ) :

                the_post();

                global $product;
                ?>

                <article
                    id="product-<?php the_ID(); ?>"
                    <?php wc_product_class( 'ws-single-product', $product ); ?>
                >

                    <div class="ws-single-product__layout">

                        <section class="ws-single-product__gallery">

                            <?php
                            do_action(
                                'wooshop_single_product_gallery'
                            );
                            ?>

                        </section>

                        <section class="ws-single-product__summary">

                            <?php
                            do_action(
                                'wooshop_single_product_summary'
                            );
                            ?>

                        </section>

                    </div>

                    <section class="ws-single-product__details">

                        <?php
                        do_action(
                            'wooshop_single_product_details'
                        );
                        ?>

                    </section>

                </article>

            <?php

            endwhile;
            ?>

            <?php
            do_action( 'woocommerce_after_main_content' );
            ?>

        </div>

    </main>

<?php
get_footer( 'shop' );