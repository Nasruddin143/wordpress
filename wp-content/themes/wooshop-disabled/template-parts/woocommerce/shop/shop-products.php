<?php
/**
 * Shop Products
 *
 * Displays the WooShop WooCommerce product loop.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-shop-products py-4">

    <?php if ( woocommerce_product_loop() ) : ?>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-lg-4">

            <?php while ( have_posts() ) : ?>

                <?php the_post(); ?>

                <div class="col">

                    <?php
                    get_template_part(
                            'template-parts/woocommerce/product/product-card'
                    );
                    ?>

                </div>

            <?php endwhile; ?>

        </div>

    <?php else : ?>

        <?php
        do_action( 'woocommerce_no_products_found' );
        ?>

    <?php endif; ?>

</div>