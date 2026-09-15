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

    <?php //woocommerce_template_loop_add_to_cart(); ?>

    <?php if ($product->is_in_stock()) : ?>
        <a href="<?php echo esc_url($product->add_to_cart_url()); ?>"
           data-quantity="1"
           class="btn btn-primary w-100  py-2 px-3 d-inline-flex align-items-center justify-content-center gap-2 shadow-sm product-card__btn ajax_add_to_cart add_to_cart_button"
           data-product_id="<?php echo esc_attr($product->get_id()); ?>"
           data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
           aria-label="<?php echo esc_attr($product->add_to_cart_description()); ?>"
           rel="nofollow">
            <i class="bi bi-cart-plus fs-5 lead lh-1"></i>
            <span class="fw-semibold text-uppercase small tracking-wide"><?php echo esc_html($product->add_to_cart_text()); ?></span>
        </a>
    <?php else : ?>
        <button class="btn btn-secondary w-100 rounded-pill py-2 px-3 d-inline-flex align-items-center justify-content-center gap-2 disabled opacity-75" disabled>
            <i class="bi bi-x-circle fs-5 lead lh-1"></i>
            <span class="fw-semibold text-uppercase small tracking-wide"><?php esc_html_e('Out of Stock', 'wooshop'); ?></span>
        </button>
    <?php endif; ?>

</div>