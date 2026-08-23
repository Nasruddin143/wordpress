<?php
/**
 * WooShop Mini Cart.
 *
 * Bootstrap 5 offcanvas shopping cart UI.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
    return;
}

if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
    return;
}

$cart       = WC()->cart;
$cart_items = $cart->get_cart();
$cart_count = $cart->get_cart_contents_count();
$subtotal   = $cart->get_cart_subtotal();

$cart_url     = wc_get_cart_url();
$checkout_url = wc_get_checkout_url();
?>

<div
    class="offcanvas offcanvas-end wooshop-mini-cart"
    tabindex="-1"
    id="wooshop-mini-cart"
    aria-labelledby="wooshop-mini-cart-title"
>

    <!-- Header -->

    <div class="offcanvas-header wooshop-mini-cart-header">

        <div class="d-flex align-items-center gap-2">

            <h2
                class="offcanvas-title wooshop-mini-cart-title"
                id="wooshop-mini-cart-title"
            >
                <?php esc_html_e( 'Shopping Cart', 'wooshop' ); ?>
            </h2>

            <span class="wooshop-mini-cart-count badge rounded-pill">
				<?php echo esc_html( $cart_count ); ?>
			</span>

        </div>

        <div class="d-flex align-items-center gap-3">

            <?php if ( $cart_count > 0 ) : ?>

                <button
                    type="button"
                    class="btn btn-link wooshop-mini-cart-clear p-0"
                    data-wooshop-cart-clear
                >
                    <?php esc_html_e( 'Clear All', 'wooshop' ); ?>
                </button>

            <?php endif; ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="<?php esc_attr_e( 'Close cart', 'wooshop' ); ?>"
            ></button>

        </div>

    </div>


    <!-- Cart Content -->

    <div class="offcanvas-body wooshop-mini-cart-body p-0">

        <?php if ( $cart_count > 0 ) : ?>

            <div class="wooshop-mini-cart-items">

                <?php foreach ( $cart_items as $cart_item_key => $cart_item ) : ?>

                    <?php
                    $product = $cart_item['data'];

                    if ( ! $product || ! $product->exists() ) {
                        continue;
                    }

                    $product_id   = $product->get_id();
                    $product_name = $product->get_name();
                    $product_url  = $product->is_visible()
                        ? $product->get_permalink( $cart_item )
                        : '';

                    $product_image = $product->get_image(
                        'woocommerce_thumbnail',
                        [
                            'class' => 'img-fluid',
                        ]
                    );

                    $quantity = $cart_item['quantity'];

                    $item_subtotal = $cart->get_product_subtotal(
                        $product,
                        $quantity
                    );

                    $remove_url = wc_get_cart_remove_url( $cart_item_key );

                    $variation = '';

                    if ( ! empty( $cart_item['variation'] ) ) {
                        $variation = wc_get_formatted_cart_item_data(
                            $cart_item,
                            false
                        );
                    }
                    ?>

                    <article
                        class="wooshop-mini-cart-item"
                        data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>"
                    >

                        <!-- Product Image -->

                        <div class="wooshop-mini-cart-image">

                            <?php if ( $product_url ) : ?>

                                <a
                                    href="<?php echo esc_url( $product_url ); ?>"
                                    aria-label="<?php echo esc_attr( $product_name ); ?>"
                                >
                                    <?php echo wp_kses_post( $product_image ); ?>
                                </a>

                            <?php else : ?>

                                <?php echo wp_kses_post( $product_image ); ?>

                            <?php endif; ?>

                        </div>


                        <!-- Product Information -->

                        <div class="wooshop-mini-cart-content">

                            <?php if ( $product_url ) : ?>

                                <a
                                    class="wooshop-mini-cart-product-name"
                                    href="<?php echo esc_url( $product_url ); ?>"
                                >
                                    <?php echo esc_html( $product_name ); ?>
                                </a>

                            <?php else : ?>

                                <span class="wooshop-mini-cart-product-name">
									<?php echo esc_html( $product_name ); ?>
								</span>

                            <?php endif; ?>


                            <?php if ( $variation ) : ?>

                                <div class="wooshop-mini-cart-variation">
                                    <?php echo wp_kses_post( $variation ); ?>
                                </div>

                            <?php endif; ?>


                            <div class="wooshop-mini-cart-meta">

                                <div class="wooshop-mini-cart-quantity">

                                    <button
                                        type="button"
                                        class="btn btn-sm"
                                        data-wooshop-quantity="decrease"
                                        data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>"
                                        aria-label="<?php esc_attr_e( 'Decrease quantity', 'wooshop' ); ?>"
                                    >
                                        −
                                    </button>

                                    <span class="wooshop-mini-cart-quantity-value">
										<?php echo esc_html( $quantity ); ?>
									</span>

                                    <button
                                        type="button"
                                        class="btn btn-sm"
                                        data-wooshop-quantity="increase"
                                        data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>"
                                        aria-label="<?php esc_attr_e( 'Increase quantity', 'wooshop' ); ?>"
                                    >
                                        +
                                    </button>

                                </div>


                                <div class="wooshop-mini-cart-price">
                                    <?php echo wp_kses_post( $item_subtotal ); ?>
                                </div>

                            </div>

                        </div>


                        <!-- Remove -->

                        <a
                            class="wooshop-mini-cart-remove"
                            href="<?php echo esc_url( $remove_url ); ?>"
                            data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>"
                            aria-label="<?php echo esc_attr(
                                sprintf(
                                /* translators: %s: Product name. */
                                    __( 'Remove %s from cart', 'wooshop' ),
                                    $product_name
                                )
                            ); ?>"
                        >
                            <i
                                class="bi bi-trash3"
                                aria-hidden="true"
                            ></i>
                        </a>

                    </article>

                <?php endforeach; ?>

            </div>


            <!-- Coupon -->

            <div class="wooshop-mini-cart-coupon">

                <form
                    class="wooshop-mini-cart-coupon-form"
                    method="post"
                    action="<?php echo esc_url( $cart_url ); ?>"
                >

                    <label
                        class="visually-hidden"
                        for="wooshop-mini-cart-coupon"
                    >
                        <?php esc_html_e( 'Coupon code', 'wooshop' ); ?>
                    </label>

                    <input
                        type="text"
                        id="wooshop-mini-cart-coupon"
                        name="coupon_code"
                        class="form-control"
                        placeholder="<?php esc_attr_e( 'Enter coupon code', 'wooshop' ); ?>"
                    >

                    <button
                        type="submit"
                        class="btn btn-dark"
                        name="apply_coupon"
                        value="<?php esc_attr_e( 'Apply', 'wooshop' ); ?>"
                    >
                        <?php esc_html_e( 'Apply', 'wooshop' ); ?>
                    </button>

                </form>

            </div>


            <!-- Totals -->

            <div class="wooshop-mini-cart-totals">

                <div class="wooshop-mini-cart-total-row">

					<span>
						<?php esc_html_e( 'Subtotal', 'wooshop' ); ?>
					</span>

                    <strong>
                        <?php echo wp_kses_post( $subtotal ); ?>
                    </strong>

                </div>


                <div class="wooshop-mini-cart-total-row">

					<span>
						<?php esc_html_e( 'Shipping', 'wooshop' ); ?>
					</span>

                    <span>
						<?php
                        esc_html_e(
                            'Calculated at checkout',
                            'wooshop'
                        );
                        ?>
					</span>

                </div>


                <div class="wooshop-mini-cart-total-row wooshop-mini-cart-total">

					<span>
						<?php esc_html_e( 'Total', 'wooshop' ); ?>
					</span>

                    <strong>
                        <?php echo wp_kses_post( $cart->get_total() ); ?>
                    </strong>

                </div>

            </div>


            <!-- Actions -->

            <div class="wooshop-mini-cart-actions">

                <a
                    class="btn btn-outline-secondary"
                    href="<?php echo esc_url( $cart_url ); ?>"
                >
                    <?php esc_html_e( 'View Cart', 'wooshop' ); ?>
                </a>

                <a
                    class="btn btn-primary"
                    href="<?php echo esc_url( $checkout_url ); ?>"
                >
                    <?php esc_html_e( 'Checkout', 'wooshop' ); ?>
                </a>

            </div>


        <?php else : ?>

            <div class="wooshop-mini-cart-empty">

                <i
                    class="bi bi-cart-x"
                    aria-hidden="true"
                ></i>

                <h3>
                    <?php esc_html_e( 'Your cart is empty', 'wooshop' ); ?>
                </h3>

                <p>
                    <?php
                    esc_html_e(
                        'Add products to your cart and they will appear here.',
                        'wooshop'
                    );
                    ?>
                </p>

                <a
                    class="btn btn-primary"
                    href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
                >
                    <?php esc_html_e( 'Continue Shopping', 'wooshop' ); ?>
                </a>

            </div>

        <?php endif; ?>

    </div>

</div>