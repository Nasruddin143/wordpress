<?php
/**
 * WooCommerce Cart Template
 *
 * Provides the WooShop cart page structure.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

    <div class="ws-cart-page">

        <div class="container py-4 py-lg-5">

            <?php
            get_template_part(
                'template-parts/components/breadcrumbs'
            );
            ?>

            <header class="ws-page-header mb-4">

                <h1 class="h2 mb-0">
                    <?php esc_html_e( 'Shopping Cart', 'wooshop' ); ?>
                </h1>

            </header>

            <div class="ws-cart-content">

                <?php
                do_action( 'woocommerce_before_cart' );
                ?>

                <form
                    class="woocommerce-cart-form"
                    action="<?php echo esc_url( wc_get_cart_url() ); ?>"
                    method="post"
                >

                    <?php do_action( 'woocommerce_before_cart_table' ); ?>

                    <table
                        class="shop_table shop_table_responsive cart woocommerce-cart-form__contents table align-middle"
                        cellspacing="0"
                    >

                        <thead>

                        <tr>

                            <th class="product-remove">
								<span class="screen-reader-text">
									<?php esc_html_e( 'Remove item', 'wooshop' ); ?>
								</span>
                            </th>

                            <th class="product-thumbnail">
								<span class="screen-reader-text">
									<?php esc_html_e( 'Thumbnail', 'wooshop' ); ?>
								</span>
                            </th>

                            <th class="product-name">
                                <?php esc_html_e( 'Product', 'wooshop' ); ?>
                            </th>

                            <th class="product-price">
                                <?php esc_html_e( 'Price', 'wooshop' ); ?>
                            </th>

                            <th class="product-quantity">
                                <?php esc_html_e( 'Quantity', 'wooshop' ); ?>
                            </th>

                            <th class="product-subtotal">
                                <?php esc_html_e( 'Subtotal', 'wooshop' ); ?>
                            </th>

                        </tr>

                        </thead>

                        <tbody>

                        <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                        <?php
                        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :

                            $_product = apply_filters(
                                'woocommerce_cart_item_product',
                                $cart_item['data'],
                                $cart_item,
                                $cart_item_key
                            );

                            $product_id = apply_filters(
                                'woocommerce_cart_item_product_id',
                                $cart_item['product_id'],
                                $cart_item,
                                $cart_item_key
                            );

                            if (
                                $_product &&
                                $_product->exists() &&
                                $cart_item['quantity'] > 0 &&
                                apply_filters(
                                    'woocommerce_cart_item_visible',
                                    true,
                                    $cart_item,
                                    $cart_item_key
                                )
                            ) :
                                ?>

                                <tr
                                    class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>"
                                >

                                    <td class="product-remove">

                                        <?php
                                        echo wp_kses_post(
                                            apply_filters(
                                                'woocommerce_cart_item_remove_link',
                                                sprintf(
                                                    '<a role="button" href="%s" class="remove text-decoration-none" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                                    esc_url(
                                                        wc_get_cart_remove_url(
                                                            $cart_item_key
                                                        )
                                                    ),
                                                    esc_attr(
                                                        sprintf(
                                                            __( 'Remove %s from cart', 'wooshop' ),
                                                            $product_name = $_product->get_name()
                                                        )
                                                    ),
                                                    esc_attr( $product_id ),
                                                    esc_attr( $_product->get_sku() )
                                                ),
                                                $cart_item_key
                                            )
                                        );
                                        ?>

                                    </td>

                                    <td class="product-thumbnail">

                                        <?php
                                        $thumbnail = $_product->get_image(
                                            'woocommerce_thumbnail'
                                        );

                                        echo wp_kses_post(
                                            apply_filters(
                                                'woocommerce_cart_item_thumbnail',
                                                $thumbnail,
                                                $cart_item,
                                                $cart_item_key
                                            )
                                        );
                                        ?>

                                    </td>

                                    <td class="product-name">

                                        <?php
                                        echo wp_kses_post(
                                            apply_filters(
                                                'woocommerce_cart_item_name',
                                                sprintf(
                                                    '<a href="%s" class="text-decoration-none">%s</a>',
                                                    esc_url(
                                                        $_product->get_permalink(
                                                            $cart_item
                                                        )
                                                    ),
                                                    esc_html(
                                                        $_product->get_name()
                                                    )
                                                ),
                                                $cart_item,
                                                $cart_item_key
                                            )
                                        );
                                        ?>

                                        <?php
                                        echo wc_get_formatted_cart_item_data(
                                            $cart_item
                                        );
                                        ?>

                                    </td>

                                    <td class="product-price">

                                        <?php
                                        echo wp_kses_post(
                                            apply_filters(
                                                'woocommerce_cart_item_price',
                                                WC()->cart->get_product_price(
                                                    $_product
                                                ),
                                                $cart_item,
                                                $cart_item_key
                                            )
                                        );
                                        ?>

                                    </td>

                                    <td class="product-quantity">

                                        <?php
                                        echo woocommerce_quantity_input(
                                            array(
                                                'input_name'  => "cart[{$cart_item_key}][qty]",
                                                'input_value' => $cart_item['quantity'],
                                                'max_value'   => $_product->get_max_purchase_quantity(),
                                                'min_value'   => 0,
                                                'product_name' => $_product->get_name(),
                                            ),
                                            $_product,
                                            false
                                        );
                                        ?>

                                    </td>

                                    <td class="product-subtotal">

                                        <?php
                                        echo wp_kses_post(
                                            apply_filters(
                                                'woocommerce_cart_item_subtotal',
                                                WC()->cart->get_product_subtotal(
                                                    $_product,
                                                    $cart_item['quantity']
                                                ),
                                                $cart_item,
                                                $cart_item_key
                                            )
                                        );
                                        ?>

                                    </td>

                                </tr>

                            <?php
                            endif;

                        endforeach;
                        ?>

                        <?php do_action( 'woocommerce_cart_contents' ); ?>

                        <tr>

                            <td colspan="6" class="actions">

                                <?php if ( wc_coupons_enabled() ) : ?>

                                    <div class="coupon d-flex gap-2 mb-3">

                                        <label
                                            for="coupon_code"
                                            class="visually-hidden"
                                        >
                                            <?php esc_html_e( 'Coupon code', 'wooshop' ); ?>
                                        </label>

                                        <input
                                            type="text"
                                            name="coupon_code"
                                            class="input-text form-control"
                                            id="coupon_code"
                                            value=""
                                            placeholder="<?php esc_attr_e( 'Coupon code', 'wooshop' ); ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="button btn btn-outline-secondary"
                                            name="apply_coupon"
                                            value="<?php esc_attr_e( 'Apply coupon', 'wooshop' ); ?>"
                                        >
                                            <?php esc_html_e( 'Apply coupon', 'wooshop' ); ?>
                                        </button>

                                        <?php do_action( 'woocommerce_cart_coupon' ); ?>

                                    </div>

                                <?php endif; ?>

                                <button
                                    type="submit"
                                    class="button btn btn-primary"
                                    name="update_cart"
                                    value="<?php esc_attr_e( 'Update cart', 'wooshop' ); ?>"
                                >
                                    <?php esc_html_e( 'Update cart', 'wooshop' ); ?>
                                </button>

                                <?php do_action( 'woocommerce_cart_actions' ); ?>

                                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

                            </td>

                        </tr>

                        <?php do_action( 'woocommerce_after_cart_contents' ); ?>

                        </tbody>

                    </table>

                    <?php do_action( 'woocommerce_after_cart_table' ); ?>

                </form>

                <?php do_action( 'woocommerce_after_cart' ); ?>

            </div>

        </div>

    </div>

<?php
get_footer();