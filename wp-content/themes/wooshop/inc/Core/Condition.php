<?php
/**
 * WooShop Condition Helper.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined( 'ABSPATH' ) || exit;

class Condition {

    /**
     * Get the current frontend asset context.
     *
     * @return string
     */
    public static function asset_context(): string {

        if ( function_exists( 'is_product' ) && is_product() ) {
            return 'product';
        }

        if ( function_exists( 'is_cart' ) && is_cart() ) {
            return 'cart';
        }

        if ( function_exists( 'is_checkout' ) && is_checkout() ) {
            return 'checkout';
        }

        if ( function_exists( 'is_account_page' ) && is_account_page() ) {
            return 'my-account';
        }

        if ( function_exists( 'is_shop' ) && is_shop() ) {
            return 'shop';
        }

        if (
            function_exists( 'is_product_category' )
            && is_product_category()
        ) {
            return 'shop';
        }

        if (
            function_exists( 'is_product_tag' )
            && is_product_tag()
        ) {
            return 'shop';
        }

        return 'global';
    }

    /**
     * Get frontend asset contexts.
     *
     * @return array<int,string>
     */
    public function assetContexts(): array
    {
        $contexts = [
            'global',
        ];

        /*
         * WooCommerce product.
         */
        if (
            function_exists( 'is_product' )
            && is_product()
        ) {

            $contexts[] = 'product';

            return $contexts;
        }

        /*
         * WooCommerce cart.
         */
        if (
            function_exists( 'is_cart' )
            && is_cart()
        ) {

            $contexts[] = 'cart';

            return $contexts;
        }

        /*
         * WooCommerce checkout.
         */
        if (
            function_exists( 'is_checkout' )
            && is_checkout()
        ) {

            $contexts[] = 'checkout';

            return $contexts;
        }

        /*
         * WooCommerce account.
         */
        if (
            function_exists( 'is_account_page' )
            && is_account_page()
        ) {

            $contexts[] = 'my-account';

            return $contexts;
        }

        /*
         * WooCommerce shop/archive.
         */
        if (
            function_exists( 'is_shop' )
            && is_shop()
        ) {

            $contexts[] = 'shop';

            return $contexts;
        }

        /*
         * Product category.
         */
        if (
            function_exists( 'is_product_category' )
            && is_product_category()
        ) {

            $contexts[] = 'shop';

            return $contexts;
        }

        /*
         * Product tag.
         */
        if (
            function_exists( 'is_product_tag' )
            && is_product_tag()
        ) {

            $contexts[] = 'shop';

            return $contexts;
        }

        /*
         * Single blog post.
         */
        if (
            is_singular( 'post' )
        ) {

            $contexts[] = 'single-post';

            return $contexts;
        }

        return $contexts;
    }
}