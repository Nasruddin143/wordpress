<?php
/**
 * Single Product Content.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>

<div
    id="product-<?php the_ID(); ?>"
    <?php wc_product_class( 'product', $product ); ?>
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

            <div class="summary entry-summary">

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

</div>