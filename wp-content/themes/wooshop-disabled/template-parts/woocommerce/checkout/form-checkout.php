<?php
/**
 * WooCommerce Checkout Template
 *
 * Provides the WooShop checkout page structure.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

    <div class="ws-checkout-page">

        <div class="container py-4 py-lg-5">

            <?php
            get_template_part(
                'template-parts/components/breadcrumbs'
            );
            ?>

            <header class="ws-page-header mb-4">

                <h1 class="h2 mb-0">
                    <?php esc_html_e( 'Checkout', 'wooshop' ); ?>
                </h1>

            </header>

            <?php
            do_action( 'woocommerce_before_checkout_form', $checkout );
            ?>

            <form
                name="checkout"
                method="post"
                class="checkout woocommerce-checkout"
                action="<?php echo esc_url( wc_get_checkout_url() ); ?>"
                enctype="multipart/form-data"
            >

                <div class="row g-4">

                    <div class="col-12 col-lg-7">

                        <?php if ( ! $checkout->get_checkout_fields() ) : ?>

                            <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                        <?php else : ?>

                            <div class="ws-checkout-fields">

                                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                                <div id="customer_details">

                                    <div class="col-12">

                                        <?php do_action( 'woocommerce_checkout_billing' ); ?>

                                    </div>

                                    <div class="col-12">

                                        <?php do_action( 'woocommerce_checkout_shipping' ); ?>

                                    </div>

                                </div>

                                <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="col-12 col-lg-5">

                        <div class="ws-checkout-review card">

                            <div class="card-body">

                                <h2 class="h4 mb-4">
                                    <?php esc_html_e( 'Your order', 'wooshop' ); ?>
                                </h2>

                                <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

                                <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                                <div id="order_review" class="woocommerce-checkout-review-order">

                                    <?php do_action( 'woocommerce_checkout_order_review' ); ?>

                                </div>

                                <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

            <?php
            do_action( 'woocommerce_after_checkout_form', $checkout );
            ?>

        </div>

    </div>

<?php
get_footer();