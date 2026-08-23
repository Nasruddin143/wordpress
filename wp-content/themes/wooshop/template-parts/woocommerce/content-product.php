<?php
/**
 * WooCommerce Product Card.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product instanceof WC_Product || ! $product->is_visible() ) {
    return;
}
?>

<li
    <?php
    wc_product_class(
        'col',
        $product
    );
    ?>
>

    <article class="product-card h-100">

        <div class="card h-100 border-0 shadow-sm">

            <div class="position-relative overflow-hidden">

                <?php
                do_action( 'woocommerce_before_shop_loop_item' );
                do_action( 'woocommerce_before_shop_loop_item_title' );
                ?>

                <div class="product-card__image">

                    <?php
                    do_action( 'woocommerce_shop_loop_item_thumbnail' );
                    ?>

                </div>

                <?php
                /*
                 * Feature modules such as Wishlist, Compare and Quick View
                 * can inject their controls here.
                 */
                do_action( 'wooshop_product_card_actions' );
                ?>

            </div>

            <div class="card-body d-flex flex-column">

                <?php
                do_action( 'woocommerce_shop_loop_item_title' );
                ?>

                <div class="product-card__meta mb-2">

                    <?php
                    do_action( 'woocommerce_after_shop_loop_item_title' );
                    ?>

                </div>

                <?php
                /*
                 * Variation swatches, product labels, stock information,
                 * countdowns and similar modules can use this location.
                 */
                do_action( 'wooshop_product_card_meta' );
                ?>

                <div class="mt-auto pt-3">

                    <?php
                    do_action( 'woocommerce_after_shop_loop_item' );
                    ?>

                </div>

            </div>

        </div>

    </article>

</li>