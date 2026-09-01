<?php
/**
 * WooShop Asset Conditions
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

return [

    /*
     * ---------------------------------------------------------
     * Global
     * ---------------------------------------------------------
     */

    'always' => static function (): bool {
        return true;
    },

    /*
     * ---------------------------------------------------------
     * WordPress Frontend
     * ---------------------------------------------------------
     */

    'front_page' => static function (): bool {
        return is_front_page();
    },

    'home' => static function (): bool {
        return is_home();
    },

    'singular' => static function (): bool {
        return is_singular();
    },

    'single' => static function (): bool {
        return is_single();
    },

    'page' => static function (): bool {
        return is_page();
    },

    'archive' => static function (): bool {
        return is_archive();
    },

    'search' => static function (): bool {
        return is_search();
    },

    '404' => static function (): bool {
        return is_404();
    },

    'comments_open' => static function (): bool {
        return is_singular() && comments_open();
    },

    /*
     * ---------------------------------------------------------
     * Navigation
     * ---------------------------------------------------------
     */

    'navigation' => static function (): bool {
        return has_nav_menu('primary');
    },

    /*
     * ---------------------------------------------------------
     * WooCommerce
     * ---------------------------------------------------------
     */

    'woocommerce' => static function (): bool {
        return function_exists('is_woocommerce')
            && is_woocommerce();
    },

    'shop' => static function (): bool {
        return function_exists('is_shop')
            && is_shop();
    },

    'product' => static function (): bool {
        return function_exists('is_product')
            && is_product();
    },

    'product_archive' => static function (): bool {
        return function_exists('is_product_category')
            && (
                is_product_category()
                || is_product_tag()
                || is_product_taxonomy()
            );
    },

    'cart' => static function (): bool {
        return function_exists('is_cart')
            && is_cart();
    },

    'checkout' => static function (): bool {
        return function_exists('is_checkout')
            && is_checkout();
    },

    'account' => static function (): bool {
        return function_exists('is_account_page')
            && is_account_page();
    },

    /*
     * ---------------------------------------------------------
     * WooCommerce Product Pages
     * ---------------------------------------------------------
     */

    'product_related' => static function (): bool {
        return function_exists('is_product')
            && is_product();
    },

    /*
     * ---------------------------------------------------------
     * Miscellaneous
     * ---------------------------------------------------------
     */
    'back_to_top' => static function (): bool {
        return true;
    },

];