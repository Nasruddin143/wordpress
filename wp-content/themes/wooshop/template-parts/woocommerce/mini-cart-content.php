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
    if (function_exists('woocommerce_mini_cart')) {
        woocommerce_mini_cart();
    } else {
        esc_html_e(
            'Your cart is currently empty.',
            'wooshop'
        );
    }
    ?>

</div>