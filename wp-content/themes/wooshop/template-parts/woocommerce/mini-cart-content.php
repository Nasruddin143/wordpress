<?php
/**
 * WooCommerce Mini Cart Content.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<div class="mini-cart-content p-3">

    <?php
    if (function_exists('woocommerce_mini_cart')) : ?>

        <div class="mini-cart-content">

            <div class="mini-cart-items">

                <?php
                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {

                    $_product = $cart_item['data'];

                    if (!$_product || !$_product->exists() || $cart_item['quantity'] <= 0) {
                        continue;
                    }

                    $product_id = $cart_item['product_id'];

                    $product_name = $_product->get_name();

                    $product_permalink = $_product->is_visible() ? $_product->get_permalink($cart_item) : '';

                    $thumbnail = $_product->get_image('woocommerce_thumbnail', ['class' => 'img-fluid',]);

                    $quantity = $cart_item['quantity'];

                    $product_price = WC()->cart->get_product_price($_product);

                    $remove_url = wc_get_cart_remove_url($cart_item_key); ?>

                    <div class="mini-cart-item" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">

                        <div class="row g-3 align-items-start">

                            <div class="col-auto">

                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 bg-light rounded">
                                        <?php if ($product_permalink) : ?>

                                            <a href="<?php echo esc_url($product_permalink); ?>">
                                                <?php echo wp_kses_post($thumbnail); ?>
                                            </a>

                                        <?php else : ?>

                                            <?php echo wp_kses_post($thumbnail); ?>

                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h3 class="h6 mini-cart-item__title">

                                            <?php if ($product_permalink) : ?>

                                                <a href="<?php echo esc_url($product_permalink); ?>">
                                                    <?php echo esc_html($product_name); ?>
                                                </a>

                                            <?php else : ?>

                                                <?php echo esc_html($product_name); ?>

                                            <?php endif; ?>

                                        </h3>
                                    </div>
                                </div>

                            </div>

                            <div class="col">

                                <div class="mini-cart-item__content">

                                    <?php $variation = wc_get_formatted_cart_item_data($cart_item);

                                    if ($variation) {
                                        echo wp_kses_post($variation);
                                    }
                                    ?>

                                    <div class="mini-cart-item__price">

                                        <?php echo wp_kses_post($product_price); ?>

                                    </div>

                                    <div class="mini-cart-item__quantity">

                                <span class="mini-cart-item__quantity-label">

                                    <?php esc_html_e('Quantity:', 'wooshop'); ?>

                                </span>

                                        <span class="mini-cart-item__quantity-value">

                                    <?php echo esc_html($quantity); ?>

                                </span>

                                    </div>

                                </div>

                            </div>

                            <div class="col-auto">

                                <a href="<?php echo esc_url($remove_url); ?>" class="mini-cart-item__remove"
                                   aria-label="<?php echo __('Remove %s from cart', 'wooshop')
                                               |> (fn($x) => sprintf($x, $product_name))
                                               |> esc_attr(...); ?>">

                            <span aria-hidden="true">

                                &times;

                            </span>

                                </a>

                            </div>

                        </div>

                    </div>

                <?php } ?>

            </div>

            <div class="mini-cart-summary">

                <div class="mini-cart-summary__subtotal">

            <span class="mini-cart-summary__label">

                <?php esc_html_e('Subtotal', 'wooshop'); ?>

            </span>

                    <span class="mini-cart-summary__amount">

                <?php echo wp_kses_post(WC()->cart->get_cart_subtotal()); ?>

            </span>

                </div>

            </div>

            <div class="mini-cart-actions d-flex gap-2 mx-auto">

                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="btn btn-outline-dark w-50">

                    <?php esc_html_e('View Cart', 'wooshop'); ?>

                </a>

                <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="btn btn-primary w-50">

                    <?php esc_html_e('Checkout', 'wooshop'); ?>

                </a>

            </div>

        </div>

    <?php else:

        esc_html_e('Your cart is currently empty.', 'wooshop');

    endif; ?>

</div>