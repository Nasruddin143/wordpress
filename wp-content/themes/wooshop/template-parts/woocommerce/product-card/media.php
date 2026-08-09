<?php
/**
 * Product Card Media.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product ) {
    return;
}
?>

<div class="ws-product-card__media">

    <div class="ws-product-card__badges">

        <?php
        /**
         * WooCommerce sale badge.
         */
        do_action(
            'woocommerce_before_shop_loop_item_title'
        );
        ?>

    </div>

    <a
        class="ws-product-card__image-link"
        href="<?php echo esc_url( $product->get_permalink() ); ?>"
        aria-label="<?php echo esc_attr( $product->get_name() ); ?>"
    >

        <?php
        echo wp_kses_post(
            woocommerce_get_product_thumbnail()
        );
        ?>

    </a>

</div>