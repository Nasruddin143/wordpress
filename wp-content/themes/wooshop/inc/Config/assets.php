<?php
/**
 * WooShop Asset Configuration.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

return [

    /*
     * Global frontend assets.
     */
    'global' => [

        'styles' => [

            [
                'handle' => 'wooshop-app',
                'src'    => 'assets/build/css/app.min.css',
                'deps'   => [],
                'media'  => 'all',
            ],

        ],

        'scripts' => [

            [
                'handle'   => 'wooshop-app',
                'src'      => 'assets/build/js/app.min.js',
                'deps'     => [],
                'strategy' => 'defer',
                'footer'   => true,
            ],

        ],

    ],

    /*
     * Shop / product archives.
     */
    'shop' => [

        'styles' => [

            [
                'handle' => 'wooshop-shop',
                'src'    => 'assets/build/css/shop.min.css',
                'deps'   => [],
                'media'  => 'all',
            ],

        ],

        'scripts' => [

            [
                'handle'   => 'wooshop-shop',
                'src'      => 'assets/build/js/shop.min.js',
                'deps'     => [],
                'strategy' => 'defer',
                'footer'   => true,
            ],

        ],

    ],

    /*
     * Single WooCommerce product.
     */
    'product' => [

        'styles' => [

            [
                'handle' => 'wooshop-single-product',
                'src'    => 'assets/build/css/single-product.min.css',
                'deps'   => [],
                'media'  => 'all',
            ],

        ],

        'scripts' => [

            [
                'handle'   => 'wooshop-single-product',
                'src'      => 'assets/build/js/single-product.min.js',
                'deps'     => [],
                'strategy' => 'defer',
                'footer'   => true,
            ],

        ],

    ],

    /*
     * Cart.
     */
    'cart' => [

        'styles' => [

            [
                'handle' => 'wooshop-cart',
                'src'    => 'assets/build/css/cart.min.css',
                'deps'   => [],
                'media'  => 'all',
            ],

        ],

        'scripts' => [

            [
                'handle'   => 'wooshop-cart',
                'src'      => 'assets/build/js/cart.min.js',
                'deps'     => [],
                'strategy' => 'defer',
                'footer'   => true,
            ],

        ],

    ],

    /*
     * Checkout.
     */
    'checkout' => [

        'styles' => [

            [
                'handle' => 'wooshop-checkout',
                'src'    => 'assets/build/css/checkout.min.css',
                'deps'   => [],
                'media'  => 'all',
            ],

        ],

        'scripts' => [

            [
                'handle'   => 'wooshop-checkout',
                'src'      => 'assets/build/js/checkout.min.js',
                'deps'     => [],
                'strategy' => 'defer',
                'footer'   => true,
            ],

        ],

    ],

    /*
     * My Account.
     */
    'my-account' => [

        'styles' => [

            [
                'handle' => 'wooshop-my-account',
                'src'    => 'assets/build/css/my-account.min.css',
                'deps'   => [],
                'media'  => 'all',
            ],

        ],

        'scripts' => [

            [
                'handle'   => 'wooshop-my-account',
                'src'      => 'assets/build/js/my-account.min.js',
                'deps'     => [],
                'strategy' => 'defer',
                'footer'   => true,
            ],

        ],

    ],

    /*
     * Blog single post.
     */
    'single-post' => [

        'styles' => [

            [
                'handle' => 'wooshop-single-post',
                'src'    => 'assets/build/css/single-post.min.css',
                'deps'   => [],
                'media'  => 'all',
            ],

        ],

        'scripts' => [

            [
                'handle'   => 'wooshop-single-post',
                'src'      => 'assets/build/js/single-post.min.js',
                'deps'     => [],
                'strategy' => 'defer',
                'footer'   => true,
            ],

        ],

    ],

];