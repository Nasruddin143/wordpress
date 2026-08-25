<?php
/**
 * WooShop Module Configuration.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

return [

    /*
     * ---------------------------------------------------------
     * WordPress Theme Modules
     * ---------------------------------------------------------
     */
    'theme' => [
        \WooShop\Modules\Theme\Setup::class,
//        \WooShop\Modules\Theme\Asset::class,
//        \WooShop\Modules\Theme\Navigation::class,
//        \WooShop\Modules\Theme\Widgets::class,
//        \WooShop\Modules\Theme\Editor::class,
//        \WooShop\Modules\Theme\Customizer::class,
//        \WooShop\Modules\Theme\Header::class,
//        \WooShop\Modules\Theme\Background::class,
//        \WooShop\Modules\Theme\Comments::class,
//        \WooShop\Modules\Theme\Feeds::class,
//        \WooShop\Modules\Theme\Filters::class,
//        \WooShop\Modules\Theme\Template::class,
//        \WooShop\Modules\Theme\Jetpack::class,
//        \WooShop\Modules\Theme\Slider::class,
    ],

    /*
     * ---------------------------------------------------------
     * WooCommerce Modules
     * ---------------------------------------------------------
     */
    'woocommerce' => [
//        \WooShop\Modules\WooCommerce\Setup::class,
//        \WooShop\Modules\WooCommerce\Assets::class,
//        \WooShop\Modules\WooCommerce\Templates::class,
//        \WooShop\Modules\WooCommerce\Accessibility::class,
//        \WooShop\Modules\WooCommerce\Translation::class,
//        \WooShop\Modules\WooCommerce\Filters::class,
//        \WooShop\Modules\WooCommerce\MiniCart::class,
//        \WooShop\Modules\WooCommerce\Categories::class,
    ],
];