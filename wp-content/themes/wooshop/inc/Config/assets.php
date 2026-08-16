<?php
/**
 * WooShop Asset Configuration
 *
 * Defines globally available theme assets,
 * component assets, and WooCommerce assets.
 *
 * All assets follow the same configuration structure.
 *
 * @package WooShop
 */

defined("ABSPATH") || exit();

return [
    /*
     * Global theme assets.
     */
    "styles" => [
        "bootstrap" => [
            "style" => "assets/build/css/bootstrap.min.css",
            "style_deps" => [],
            "version" => null,
            "media" => "all",
        ],

        "app" => [
            "style" => "assets/build/css/app.min.css",
            "style_deps" => ["bootstrap"],
            "version" => null,
            "media" => "all",
        ],
    ],

    "scripts" => [
        "bootstrap" => [
            "script" => "assets/build/js/bootstrap.bundle.min.js",
            "script_deps" => [],
            "version" => null,
            "in_footer" => true,
        ],

        "app" => [
            "script" => "assets/build/js/app.min.js",
            "script_deps" => ["bootstrap"],
            "version" => null,
            "in_footer" => true,
        ],
    ],

    /*
     * Component assets.
     *
     * Components are loaded only when requested
     * by their corresponding module.
     */
    "components" => [
        "product-filters" => [
            "style" => "assets/build/css/components/product-filters.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/product-filters.min.js",
            "script_deps" => [],
        ],

        "variation-swatches" => [
            "style" => "assets/build/css/components/variation-swatches.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/variation-swatches.min.js",
            "script_deps" => ["jquery", "wc-add-to-cart-variation"],
        ],

        "wishlist" => [
            "style" => "assets/build/css/components/wishlist.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/wishlist.min.js",
            "script_deps" => [],
        ],

        "quick-view" => [
            "style" => "assets/build/css/components/quick-view.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/quick-view.min.js",
            "script_deps" => ["jquery", "wc-add-to-cart-variation"],
        ],

        "reviews" => [
            "style" => "assets/build/css/components/reviews.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/reviews.min.js",
            "script_deps" => [],
        ],

        "size-guide" => [
            "style" => "assets/build/css/components/size-guide.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/size-guide.min.js",
            "script_deps" => [],
        ],

        "compare" => [
            "style" => "assets/build/css/components/compare.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/compare.min.js",
            "script_deps" => [],
        ],

        "mobile-sales" => [
            "style" => "assets/build/css/components/mobile-sales.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/mobile-sales.min.js",
            "script_deps" => [],
        ],

        "product-brands" => [
            "style" => "assets/build/css/components/product-brands.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/product-brands.min.js",
            "script_deps" => [],
        ],

        "stock-scarcity" => [
            "style" => "assets/build/css/components/stock-scarcity.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/stock-scarcity.min.js",
            "script_deps" => [],
        ],

        "free-shipping-bar" => [
            "style" => "assets/build/css/components/free-shipping-bar.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/free-shipping-bar.min.js",
            "script_deps" => ["jquery"],
        ],

        "product-custom-tabs" => [
            "style" =>
                "assets/build/css/components/product-custom-tabs.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/product-custom-tabs.min.js",
            "script_deps" => [],
        ],

        "product-waitlist" => [
            "style" => "assets/build/css/components/product-waitlist.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/product-waitlist.min.js",
            "script_deps" => [],
        ],

        "product-videos" => [
            "style" => "assets/build/css/components/product-videos.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/product-videos.min.js",
            "script_deps" => [],
        ],

        "sale-countdown" => [
            "style" => "assets/build/css/components/sale-countdown.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/sale-countdown.min.js",
            "script_deps" => [],
        ],

        "social-sharing" => [
            "style" => "assets/build/css/components/social-sharing.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/social-sharing.min.js",
            "script_deps" => [],
        ],

        "payment-icons" => [
            "style" => "assets/build/css/components/payment-icons.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/payment-icons.min.js",
            "script_deps" => [],
        ],

        "mini-cart" => [
            "style" => "assets/build/css/components/mini-cart.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/mini-cart.min.js",
            "script_deps" => ["wc-cart-fragments"],
        ],

        "accessibility" => [
            "style" => "assets/build/css/components/accessibility.min.css",
            "style_deps" => [],
            "script" => null,
            "script_deps" => [],
        ],

        "mobile-commerce" => [
            "style" => "assets/build/css/components/mobile-commerce.min.css",
            "style_deps" => [],
            "script" => "assets/build/js/components/mobile-commerce.min.js",
            "script_deps" => [],
        ],
    ],

    /*
     * WooCommerce assets.
     *
     * WooCommerce assets use the exact same
     * structure as component assets.
     *
     * Assets are loaded only when requested
     * by the corresponding WooCommerce module.
     */
    "woocommerce" => [
        "base" => [
            "style" => "assets/build/css/woocommerce.min.css",
            "style_deps" => [],
            "script" => null,
            "script_deps" => [],
        ],

        "shop" => [
            "style" => "assets/build/css/shop.min.css",
            "style_deps" => ["wooshop-woocommerce-base-base"],
            "script" => "assets/build/js/shop.min.js",
            "script_deps" => [],
        ],

        "product" => [
            "style" => "assets/build/css/product.min.css",
            "style_deps" => ["wooshop-woocommerce-base-base"],
            "script" => "assets/build/js/product.min.js",
            "script_deps" => [],
        ],

        "cart" => [
            "style" => "assets/build/css/cart.min.css",
            "style_deps" => ["wooshop-woocommerce-base-base"],
            "script" => "assets/build/js/cart.min.js",
            "script_deps" => ["wc-cart-fragments"],
        ],

        "checkout" => [
            "style" => "assets/build/css/checkout.min.css",
            "style_deps" => ["wooshop-woocommerce-base-base"],
            "script" => "assets/build/js/checkout.min.js",
            "script_deps" => ["wc-checkout"],
        ],

        "account" => [
            "style" => "assets/build/css/account.min.css",
            "style_deps" => ["wooshop-woocommerce-base-base"],
            "script" => "assets/build/js/account.min.js",
            "script_deps" => [],
        ],
    ],
];
