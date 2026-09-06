<?php
/**
 * WooShop Product Image.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

$product = $args['product'] ?? null;

if (!$product instanceof WC_Product) {
    return;
}

$product_link = $product->get_permalink();

$image = $product->get_image('woocommerce_thumbnail', ['class' => 'card-img-top img-fluid',]); ?>

<a href="<?php echo esc_url($product_link); ?>" class="product-card__image-link d-block">
    <?php echo wp_kses_post($image); ?>
</a>