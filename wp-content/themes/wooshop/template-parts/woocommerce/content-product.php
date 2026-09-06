<?php
/**
 * WooShop Product Loop Item
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

global $product;

if (!is_a($product, WC_Product::class) || !$product->is_visible()) {
    return;
}

get_template_part('template-parts/woocommerce/product/card', null, ['product' => $product,]);