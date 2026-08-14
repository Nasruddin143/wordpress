<?php
/**
 * Product Card
 *
 * Main WooCommerce product card template.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product || ! $product->is_visible() ) {
    return;
}
?>

<article
    <?php wc_product_class( 'ws-product-card', $product ); ?>
>

    <div class="ws-product-card__inner">

        <?php
        /**
         * Product card media.
         */
        do_action( 'wooshop_product_card_media' );
        ?>

        <div class="ws-product-card__content">

            <?php
            /**
             * Product category.
             */
            do_action( 'wooshop_product_card_category' );
            ?>

            <?php
            /**
             * Product information.
             */
            do_action( 'wooshop_product_card_info' );
            ?>

        </div>

        <?php
        /**
         * Product actions.
         */
        do_action( 'wooshop_product_card_actions' );
        ?>

    </div>

</article>