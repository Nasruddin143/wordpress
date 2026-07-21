<?php

defined('ABSPATH') || exit;

global $product;

if (!$product || !$product->is_visible()) {
    return;
} ?>

<div <?php wc_product_class('col-lg-3 col-md-4 col-sm-6'); ?>>

    <div class="border px-3 py-2 rounded-3">
        <div class="product-image">
            <a href="<?php the_permalink(); ?>" class="d-block link-offset-2 link-underline link-underline-opacity-0 link-dark">
                <?php echo woocommerce_get_product_thumbnail('woocommerce_thumbnail', ['class' => 'img-fluid']); ?>
                <?php if ($product->is_on_sale()): ?>
                    <span class="kt-badge-sale">Sale</span>
                <?php endif; ?>
            </a>
        </div>

        <div class="kt-product-body py-2">

            <h2 class="kt-product-title h6 lh-sm mb-1">

                <a href="<?php the_permalink(); ?>" class="d-block link-offset-2 link-underline link-underline-opacity-0 link-dark">

                    <?php the_title(); ?>

                </a>

            </h2>

            <!-- Rating -->
            <div class="kt-rating d-flex mb-2">

                <?php woocommerce_template_loop_rating(); ?>

            </div>

            <!-- Price -->
            <div class="kt-price d-flex gap-2 mb-2">

                <?php woocommerce_template_loop_price(); ?>

            </div>

            <!-- Button -->
            <div class="kt-cart">

                <?php woocommerce_template_loop_add_to_cart(); ?>

            </div>

        </div>

    </div>

</div>