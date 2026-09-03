<?php
/**
 * WooShop Mini Cart Content
 *
 * Displays WooCommerce cart items inside the
 * WooShop mini cart.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

if (!function_exists('WC') || !WC()->cart) {
    return;
}

if (WC()->cart->is_empty()) {
    get_template_part('template-parts/woocommerce/mini-cart-empty');
    return;
}
?>

<div class="mini-cart-content">

    <div class="mini-cart-items">

        <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {

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


            <div class="p-2 border rounded mb-4">
                <div class="mini-cart-item d-flex align-items-center"
                     data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                    <div class="flex-shrink-0">
                        <?php if ($product_permalink) : ?>

                            <a href="<?php echo esc_url($product_permalink); ?>">
                                <?php echo wp_kses_post($thumbnail); ?>
                            </a>

                        <?php else : ?>

                            <?php echo wp_kses_post($thumbnail); ?>

                        <?php endif; ?>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mini-cart-item__title h6">

                            <?php if ($product_permalink) : ?>

                                <a href="<?php echo esc_url($product_permalink); ?>">
                                    <?php echo esc_html($product_name); ?>
                                </a>

                            <?php else : ?>

                                <?php echo esc_html($product_name); ?>

                            <?php endif; ?>

                        </h3>

                        <div class="mini-cart-item__variations">
                            <?php
                            $variation = wc_get_formatted_cart_item_data($cart_item);

                            if ($variation) {
                                echo wp_kses_post($variation);
                            }
                            ?>
                        </div>

                        <div class="d-flex align-items-center justify-content-between">

                            <span class="text-body-tertiary">
                                <?php echo esc_html($quantity); ?> &times; <?php echo wp_kses_post($product_price); ?>
                            </span>

                            <a href="<?php echo esc_url($remove_url); ?>"
                               class="mini-cart-item__remove me-3"
                               aria-label="<?php echo __('Remove %s from cart', 'wooshop')
                                           |> (fn($x) => sprintf($x, $product_name))
                                           |> esc_attr(...); ?>">
                                <span aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"
                                         class="main-grid-item-icon me-1"
                                         fill="none" stroke="currentColor" stroke-linecap="round"
                                         stroke-linejoin="round"
                                         stroke-width="2">
                                      <polyline points="3 6 5 6 21 6"/>
                                      <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                      <line x1="10" x2="10" y1="11" y2="17"/>
                                      <line x1="14" x2="14" y1="11" y2="17"/>
                                    </svg>
                                </span>
                                <span>
                                <?php echo __('Remove', 'wooshop'); ?>
                            </span>

                            </a>

                        </div>


                    </div>
                </div>
            </div>

        <?php } ?>

    </div>

    <div class="mini-cart-summary py-3 border-top border-bottom mb-4">

        <div class="d-flex align-items-center justify-content-between">

            <span class="mini-cart-summary__label fw-bold">

                <?php esc_html_e('Subtotal', 'wooshop'); ?>

            </span>

            <span class="mini-cart-summary__amount text-dark fs-5 fw-bold">

                <?php echo wp_kses_post(WC()->cart->get_cart_subtotal()); ?>

            </span>

        </div>

    </div>

    <div class="mini-cart-actions d-flex align-items-center gap-2">

        <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="btn btn-primary btn-cart-actions w-50">

            <?php esc_html_e('Checkout', 'wooshop'); ?>

        </a>

        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="btn btn-outline-dark btn-cart-actions w-50">

            <?php esc_html_e('View Cart', 'wooshop'); ?>

        </a>

    </div>

</div>