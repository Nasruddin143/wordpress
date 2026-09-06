<?php
/**
 * WooShop Product Card Actions
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

$product = $args['product'] ?? null;

if (!is_a($product, WC_Product::class)) {
    return;
}
?>

<div class="product-card__actions">

    <?php woocommerce_template_loop_add_to_cart(); ?>

</div>