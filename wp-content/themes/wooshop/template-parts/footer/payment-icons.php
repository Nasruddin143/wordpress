<?php
/**
 * Payment Icons
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

$payments = ['visa', 'mastercard', 'rupay', 'upi', 'paypal',]; ?>

<div
        class="ws-footer-payments"
        aria-label="<?php esc_attr_e('Accepted Payment Methods', 'wooshop'); ?>">

    <span class="ws-payment-title">
        <?php esc_html_e('We Accept', 'wooshop'); ?>
    </span>

    <ul class="ws-payment-list">

        <?php foreach ($payments as $payment) : ?>

            <li class="ws-payment-item">

                <?php
                if (function_exists('wooshop_icon')) {

                    echo wooshop_icon(
                            $payment,
                            [
                                    'class' => 'ws-payment-icon',
                            ]
                    );

                }
                ?>

            </li>

        <?php endforeach; ?>

    </ul>

</div>