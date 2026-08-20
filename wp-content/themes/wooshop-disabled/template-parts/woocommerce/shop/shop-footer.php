<?php
/**
 * Shop Footer
 *
 * Displays the reusable footer area for the WooCommerce shop.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<footer class="ws-shop-footer border-top">

    <div class="container py-4">

        <div class="row row-cols-1 row-cols-md-3 g-4 text-center">

            <div class="col">

                <div class="ws-shop-footer-item">

                    <strong class="d-block mb-1">
                        <?php esc_html_e( 'Secure Payments', 'wooshop' ); ?>
                    </strong>

                    <span class="small text-body-secondary">
						<?php esc_html_e( 'Safe and secure checkout.', 'wooshop' ); ?>
					</span>

                </div>

            </div>

            <div class="col">

                <div class="ws-shop-footer-item">

                    <strong class="d-block mb-1">
                        <?php esc_html_e( 'Fast Shipping', 'wooshop' ); ?>
                    </strong>

                    <span class="small text-body-secondary">
						<?php esc_html_e( 'Reliable delivery options.', 'wooshop' ); ?>
					</span>

                </div>

            </div>

            <div class="col">

                <div class="ws-shop-footer-item">

                    <strong class="d-block mb-1">
                        <?php esc_html_e( 'Customer Support', 'wooshop' ); ?>
                    </strong>

                    <span class="small text-body-secondary">
						<?php esc_html_e( 'We are here to help.', 'wooshop' ); ?>
					</span>

                </div>

            </div>

        </div>

    </div>

</footer>