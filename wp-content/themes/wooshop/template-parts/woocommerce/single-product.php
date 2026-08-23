<?php
/**
 * Single Product Template.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();

do_action( 'woocommerce_before_main_content' );
?>

    <div class="container py-4 py-lg-5">

        <?php while ( have_posts() ) : ?>

            <?php the_post(); ?>

            <?php
            global $product;

            if ( ! $product instanceof WC_Product ) {
                continue;
            }
            ?>

            <article
                id="product-<?php the_ID(); ?>"
                <?php wc_product_class( 'single-product', $product ); ?>
            >

                <div class="row g-4 g-lg-5">

                    <div class="col-12 col-lg-6">

                        <?php
                        do_action(
                            'woocommerce_before_single_product_summary'
                        );
                        ?>

                    </div>

                    <div class="col-12 col-lg-6">

                        <div class="product-summary">

                            <?php
                            do_action(
                                'woocommerce_single_product_summary'
                            );
                            ?>

                        </div>

                    </div>

                </div>

                <div class="row mt-5">

                    <div class="col-12">

                        <?php
                        do_action(
                            'woocommerce_after_single_product_summary'
                        );
                        ?>

                    </div>

                </div>

            </article>

        <?php endwhile; ?>

    </div>

<?php

do_action( 'woocommerce_after_main_content' );

get_footer();