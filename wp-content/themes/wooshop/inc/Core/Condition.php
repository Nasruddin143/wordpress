<?php
/**
 * WooShop Condition Helper.
 *
 * Determines the current WordPress/WooCommerce request context.
 *
 * @package WooShop
 */

namespace WooShop\Core;

defined( 'ABSPATH' ) || exit;

class Condition
{

    /**
     * Get frontend asset contexts.
     *
     * The global context is always loaded first.
     * Additional contexts are appended according to
     * the current request.
     *
     * @return array<int,string>
     */
    public function assetContexts(): array
    {
        $contexts = [
            'global',
        ];

        /*
         * WooCommerce single product.
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
         * WooCommerce My Account.
         */
        if (
            function_exists( 'is_account_page' )
            && is_account_page()
        ) {

            $contexts[] = 'my-account';

            return $contexts;
        }

        /*
         * WooCommerce shop.
         */
        if (
            function_exists( 'is_shop' )
            && is_shop()
        ) {

            $contexts[] = 'shop';

            return $contexts;
        }

        /*
         * WooCommerce product category.
         */
        if (
            function_exists( 'is_product_category' )
            && is_product_category()
        ) {

            $contexts[] = 'shop';

            return $contexts;
        }

        /*
         * WooCommerce product tag.
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
        if ( is_singular( 'post' ) ) {

            $contexts[] = 'single-post';

            return $contexts;
        }

        return $contexts;
    }
}