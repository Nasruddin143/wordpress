<?php
/**
 * WooCommerce Product Card
 *
 * Individual product loop item.
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

    <?php
    /**
     * Product loop content.
     *
     * WooCommerce hooks render:
     *
     * - Product link
     * - Thumbnail
     * - Title
     * - Rating
     * - Price
     * - Add to cart
     */
    do_action( 'woocommerce_before_shop_loop_item' );

    do_action( 'woocommerce_before_shop_loop_item_title' );

    do_action( 'woocommerce_shop_loop_item_title' );

    do_action( 'woocommerce_after_shop_loop_item_title' );

    do_action( 'woocommerce_after_shop_loop_item' );
    ?>

</li>