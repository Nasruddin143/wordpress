<?php
/**
 * WooShop Product Card Content
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

$product = $args['product'] ?? null;

if (!is_a($product, WC_Product::class)) {
    return;
}

$product_link = $product->get_permalink();
$product_name = $product->get_name();
?>

<h2 class="product-card__title h6 mb-2 text-center fw-bold">

    <a href="<?php echo esc_url($product_link); ?>" class="text-decoration-none">

        <?php echo esc_html($product_name); ?>

    </a>

</h2>

<?php if (wc_review_ratings_enabled()) : ?>

    <div class="product-card__rating mb-2 ">

        <?php echo wp_kses_post(wc_get_rating_html($product->get_average_rating(), $product->get_rating_count())); ?>

    </div>

<?php endif; ?>

<div class="product-card__price text-center">

    <?php echo wp_kses_post($product->get_price_html()); ?>

</div>