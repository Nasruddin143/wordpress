<?php
/**
 * WooShop Product Card
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

$product = $args['product'] ?? null;

if (!is_a($product, WC_Product::class)) {
    return;
}

$product_id = $product->get_id(); ?>

<div <?php wc_product_class('col-6 col-md-4 col-lg-3 product', $product); ?>>

    <article class="product-card card h-100" id="product-<?php echo esc_attr($product_id); ?>">

        <?php get_template_part('template-parts/woocommerce/product/badges', null, ['product' => $product,]); ?>

        <?php get_template_part('template-parts/woocommerce/product/image', null, ['product' => $product,]); ?>

        <div class="card-body">

            <?php get_template_part('template-parts/woocommerce/product/content', null, ['product' => $product,]); ?>
            <?php get_template_part('template-parts/woocommerce/product/actions', null, ['product' => $product,]); ?>

        </div>

    </article>

</div>