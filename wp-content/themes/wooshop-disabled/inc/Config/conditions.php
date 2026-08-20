<?php
/**
 * WooShop Asset Conditions Configuration
 *
 * Defines reusable WordPress and WooCommerce conditional
 * callbacks for the WooShop Smart Asset Loading system.
 *
 * Asset configurations reference these conditions by key
 * instead of implementing conditional logic themselves.
 *
 * @package WooShop
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

return array(

    /*
     * ---------------------------------------------------------
     * WordPress Conditions
     * ---------------------------------------------------------
     */

    'is_front_page' => 'is_front_page',

    'is_home' => 'is_home',

    'is_page' => 'is_page',

    'is_single' => 'is_single',

    'is_singular' => 'is_singular',

    'is_archive' => 'is_archive',

    'is_search' => 'is_search',

    'is_404' => 'is_404',

    'is_category' => 'is_category',

    'is_tag' => 'is_tag',

    'is_author' => 'is_author',

    'is_date' => 'is_date',

    'is_tax' => 'is_tax',

    'is_post_type_archive' => 'is_post_type_archive',

    'is_attachment' => 'is_attachment',

    'is_feed' => 'is_feed',

    'is_admin' => 'is_admin',

    /*
     * ---------------------------------------------------------
     * WooCommerce Conditions
     * ---------------------------------------------------------
     */

    'is_woocommerce' => 'is_woocommerce',

    'is_shop' => 'is_shop',

    'is_product' => 'is_product',

    'is_product_category' => 'is_product_category',

    'is_product_tag' => 'is_product_tag',

    'is_cart' => 'is_cart',

    'is_checkout' => 'is_checkout',

    'is_account_page' => 'is_account_page',

    'is_wc_endpoint_url' => 'is_wc_endpoint_url',

    'is_product_taxonomy' => 'is_product_taxonomy',

    /*
     * ---------------------------------------------------------
     * Theme / Request Conditions
     * ---------------------------------------------------------
     */

    'is_user_logged_in' => 'is_user_logged_in',

    'is_user_logged_out' => static function (): bool {

        return !is_user_logged_in();
    },

    'is_mobile' => static function (): bool {

        return wp_is_mobile();
    },

    'is_desktop' => static function (): bool {

        return !wp_is_mobile();
    },

    /*
     * ---------------------------------------------------------
     * WooCommerce Availability
     * ---------------------------------------------------------
     */

    'woocommerce_active' => static function (): bool {

        return class_exists('WooCommerce');
    },

    /*
     * ---------------------------------------------------------
     * Product Context
     * ---------------------------------------------------------
     */

    'has_product' => static function (): bool {

        return function_exists('wc_get_product')
            && is_product();
    },

    /*
     * ---------------------------------------------------------
     * Cart Context
     * ---------------------------------------------------------
     */

    'has_cart' => static function (): bool {

        return function_exists('WC')
            && WC()->cart instanceof \WC_Cart;
    },

    /*
     * ---------------------------------------------------------
     * AJAX
     * ---------------------------------------------------------
     */

    'is_ajax' => static function (): bool {

        return wp_doing_ajax();
    },

    /*
     * ---------------------------------------------------------
     * REST API
     * ---------------------------------------------------------
     */

    'is_rest' => static function (): bool {

        return defined('REST_REQUEST')
            && REST_REQUEST;
    },

    /*
     * ---------------------------------------------------------
     * Login / Authentication
     * ---------------------------------------------------------
     */

    'is_login_page' => static function (): bool {

        return false !== stripos(
                (string) $GLOBALS['pagenow'] ?? '',
                'wp-login.php'
            );
    },

);