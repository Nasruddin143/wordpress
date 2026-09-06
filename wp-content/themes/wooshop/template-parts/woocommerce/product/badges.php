<?php
/**
 * WooShop Product Badges.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

$product = $args['product'] ?? null;

if (!$product instanceof WC_Product) {
    return;
}

if ($product->is_on_sale()) {
    woocommerce_show_product_loop_sale_flash();
}