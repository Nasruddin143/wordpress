<?php
/**
 * WooShop Product Card.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product || ! $product->is_visible() ) {
    return;
}
?>

<li <?php wc_product_class( 'ws-product-card', $product ); ?>>

    <article class="ws-product-card__inner">

        <?php
        do_action(
                'wooshop_product_card_media'
        );
        ?>

        <div class="ws-product-card__content">

            <?php
            do_action(
                    'wooshop_product_card_category'
            );
            ?>

            <?php
            do_action(
                    'woocommerce_shop_loop_item_title'
            );
            ?>

            <?php
            do_action(
                    'wooshop_product_card_info'
            );
            ?>

        </div>

        <?php
        do_action(
                'wooshop_product_card_actions'
        );
        ?>

    </article>

</li>