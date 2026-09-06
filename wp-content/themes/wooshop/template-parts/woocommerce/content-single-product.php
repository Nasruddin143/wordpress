<?php
/**
 * WooShop Single Product Content
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

global $product;

if (!is_a($product, WC_Product::class)) {
    return;
}
?>

<article id="product-<?php the_ID(); ?>" <?php wc_product_class('product-single', $product); ?>>

    <?php do_action('woocommerce_before_single_product'); ?>

    <div class="row g-4">

        <div class="col-lg-6">

            <?php do_action('woocommerce_before_single_product_summary'); ?>

        </div>

        <div class="col-lg-6">

            <div class="product-single__summary">

                <?php do_action('woocommerce_single_product_summary'); ?>

            </div>

        </div>

    </div>

    <?php do_action('woocommerce_after_single_product_summary'); ?>

    <?php do_action('woocommerce_after_single_product'); ?>

</article>