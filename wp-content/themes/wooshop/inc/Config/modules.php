<?php
/**
 * WooShop Module Configuration.
 *
 * @package WooShop
 */

defined("ABSPATH") || exit();

return [
    /*
     * ---------------------------------------------------------
     * WordPress Theme Modules
     * ---------------------------------------------------------
     */
    "theme" => [
        [
            "class" => \WooShop\Modules\Theme\Setup::class,
            "enabled" => true
        ],
        [
            "class" => \WooShop\Modules\Theme\Asset::class,
            "enabled" => true
        ],
        [
            "class" => \WooShop\Modules\Theme\Navigation::class,
            "enabled" => true,
        ],
        [
            "class" => \WooShop\Modules\Theme\Widgets::class,
            "enabled" => true
        ],
        [
            "class" => \WooShop\Modules\Theme\Editor::class,
            "enabled" => true
        ],
        [
            "class" => \WooShop\Modules\Theme\Customizer::class,
            "enabled" => true,
        ],
        [
            "class" => \WooShop\Modules\Theme\Header::class,
            "enabled" => true
        ],
        [
            "class" => \WooShop\Modules\Theme\Actions::class,
            "enabled" => true
        ],
        [
            "class" => \WooShop\Modules\Theme\Footer::class,
            "enabled" => true
        ],
        [
            "class" => \WooShop\Modules\Theme\Background::class,
            "enabled" => true,
        ],
        [
            "class" => \WooShop\Modules\Theme\Comments::class,
            "enabled" => true
        ],
        [
            "class" => \WooShop\Modules\Theme\Feeds::class,
            "enabled" => true
        ],
        [
            "class" => \WooShop\Modules\Theme\Filters::class,
            "enabled" => true
        ],
        [
            "class" => \WooShop\Modules\Theme\Template::class,
            "enabled" => true
        ],
        [
            "class" => \WooShop\Modules\Theme\Jetpack::class,
            "enabled" => true
        ],
        [
            "class" => \WooShop\Modules\Theme\Slider::class,
            "enabled" => true
        ],
    ],

    /*
     * ---------------------------------------------------------
     * WordPress Theme Modules
     * ---------------------------------------------------------
     */
    "woocommerce" => [
        [
            "class" => \WooShop\Modules\WooCommerce\Setup::class,
            "enabled" => true,
        ],
        [
            "class" => \WooShop\Modules\WooCommerce\Assets::class,
            "enabled" => true,
        ],
        [
            "class" => \WooShop\Modules\WooCommerce\Templates::class,
            "enabled" => true,
        ],
        [
            "class" => \WooShop\Modules\WooCommerce\Accessibility::class,
            "enabled" => true,
        ],
        [
            "class" => \WooShop\Modules\WooCommerce\Translation::class,
            "enabled" => true,
        ],
        [
            "class" => \WooShop\Modules\WooCommerce\Filters::class,
            "enabled" => true,
        ],
        [
            "class" => \WooShop\Modules\WooCommerce\Wishlist::class,
            "enabled" => true,
        ],
        [
            "class" => \WooShop\Modules\WooCommerce\Categories::class,
            "enabled" => true,
        ],
        [
            "class" => \WooShop\Modules\WooCommerce\Shop::class,
            "enabled" => true,
        ],
    ],
];
