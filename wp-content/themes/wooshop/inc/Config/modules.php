<?php
/**
 * WooShop Module Configuration
 *
 * Defines all WooShop Theme and WooCommerce modules and their
 * registration state.
 *
 * Each module is resolved through the WooShop Container and
 * managed by the ModuleManager.
 *
 * @package WooShop
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

return array(

    /*
     * ---------------------------------------------------------
     * Core Modules
     * ---------------------------------------------------------
     *
     * Core services are bootstrapped separately by Loader.
     * Keep this group available for future application-level
     * modules that extend the Core architecture.
     */
    'core' => array(),

    /*
     * ---------------------------------------------------------
     * Theme Modules
     * ---------------------------------------------------------
     */
    'theme' => array(

        'accessibility' => array(
            'class'   => \WooShop\Modules\Theme\Accessibility::class,
            'enabled' => true,
        ),

        'admin' => array(
            'class'   => \WooShop\Modules\Theme\Admin::class,
            'enabled' => true,
        ),

        'admin_bar' => array(
            'class'   => \WooShop\Modules\Theme\AdminBar::class,
            'enabled' => true,
        ),

        'assets' => array(
            'class'   => \WooShop\Modules\Theme\Assets::class,
            'enabled' => true,
        ),

        'block_editor' => array(
            'class'   => \WooShop\Modules\Theme\BlockEditor::class,
            'enabled' => true,
        ),

        'comments' => array(
            'class'   => \WooShop\Modules\Theme\Comments::class,
            'enabled' => true,
        ),

        'customizer' => array(
            'class'   => \WooShop\Modules\Theme\Customizer::class,
            'enabled' => true,
        ),

        'editor' => array(
            'class'   => \WooShop\Modules\Theme\Editor::class,
            'enabled' => true,
        ),

        'embeds' => array(
            'class'   => \WooShop\Modules\Theme\Embeds::class,
            'enabled' => true,
        ),

        'excerpt' => array(
            'class'   => \WooShop\Modules\Theme\Excerpt::class,
            'enabled' => true,
        ),

        'feeds' => array(
            'class'   => \WooShop\Modules\Theme\Feeds::class,
            'enabled' => true,
        ),

        'filters' => array(
            'class'   => \WooShop\Modules\Theme\Filters::class,
            'enabled' => true,
        ),

        'hooks' => array(
            'class'   => \WooShop\Modules\Theme\Hooks::class,
            'enabled' => true,
        ),

        'image_sizes' => array(
            'class'   => \WooShop\Modules\Theme\ImageSizes::class,
            'enabled' => true,
        ),

        'localization' => array(
            'class'   => \WooShop\Modules\Theme\Localization::class,
            'enabled' => true,
        ),

        'metadata' => array(
            'class'   => \WooShop\Modules\Theme\Metadata::class,
            'enabled' => true,
        ),

        'mobile_commerce' => array(
            'class'   => \WooShop\Modules\Theme\MobileCommerce::class,
            'enabled' => true,
        ),

        'navigation' => array(
            'class'   => \WooShop\Modules\Theme\Navigation::class,
            'enabled' => true,
        ),

        'pagination' => array(
            'class'   => \WooShop\Modules\Theme\Pagination::class,
            'enabled' => true,
        ),

        'post_formats' => array(
            'class'   => \WooShop\Modules\Theme\PostFormats::class,
            'enabled' => true,
        ),

        'post_types' => array(
            'class'   => \WooShop\Modules\Theme\PostTypes::class,
            'enabled' => true,
        ),

        'search' => array(
            'class'   => \WooShop\Modules\Theme\Search::class,
            'enabled' => true,
        ),

        'security' => array(
            'class'   => \WooShop\Modules\Theme\Security::class,
            'enabled' => true,
        ),

        'setup' => array(
            'class'   => \WooShop\Modules\Theme\Setup::class,
            'enabled' => true,
        ),

        'sidebars' => array(
            'class'   => \WooShop\Modules\Theme\Sidebars::class,
            'enabled' => true,
        ),

        'taxonomies' => array(
            'class'   => \WooShop\Modules\Theme\Taxonomies::class,
            'enabled' => true,
        ),

        'template' => array(
            'class'   => \WooShop\Modules\Theme\Template::class,
            'enabled' => true,
        ),

        'widgets' => array(
            'class'   => \WooShop\Modules\Theme\Widgets::class,
            'enabled' => true,
        ),

    ),

    /*
     * ---------------------------------------------------------
     * WooCommerce Modules
     * ---------------------------------------------------------
     *
     * These modules are loaded only when WooCommerce is active.
     */
    'woocommerce' => array(

        'assets' => array(
            'class'   => \WooShop\Modules\WooCommerce\Assets::class,
            'enabled' => true,
        ),

        'bootstrap' => array(
            'class'   => \WooShop\Modules\WooCommerce\Bootstrap::class,
            'enabled' => true,
        ),

        'compare' => array(
            'class'   => \WooShop\Modules\WooCommerce\Compare::class,
            'enabled' => true,
        ),

        'free_shipping_bar' => array(
            'class'   => \WooShop\Modules\WooCommerce\FreeShippingBar::class,
            'enabled' => true,
        ),

        'mini_cart' => array(
            'class'   => \WooShop\Modules\WooCommerce\MiniCart::class,
            'enabled' => true,
        ),

        'mobile_sales' => array(
            'class'   => \WooShop\Modules\WooCommerce\MobileSales::class,
            'enabled' => true,
        ),

        'payment_icons' => array(
            'class'   => \WooShop\Modules\WooCommerce\PaymentIcons::class,
            'enabled' => true,
        ),

        'product_brands' => array(
            'class'   => \WooShop\Modules\WooCommerce\ProductBrands::class,
            'enabled' => true,
        ),

        'product_custom_tabs' => array(
            'class'   => \WooShop\Modules\WooCommerce\ProductCustomTabs::class,
            'enabled' => true,
        ),

        'product_filters' => array(
            'class'   => \WooShop\Modules\WooCommerce\ProductFilters::class,
            'enabled' => true,
        ),

        'product_search' => array(
            'class'   => \WooShop\Modules\WooCommerce\ProductSearch::class,
            'enabled' => true,
        ),

        'product_videos' => array(
            'class'   => \WooShop\Modules\WooCommerce\ProductVideos::class,
            'enabled' => true,
        ),

        'product_waitlist' => array(
            'class'   => \WooShop\Modules\WooCommerce\ProductWaitlist::class,
            'enabled' => true,
        ),

        'quick_view' => array(
            'class'   => \WooShop\Modules\WooCommerce\QuickView::class,
            'enabled' => true,
        ),

        'reviews' => array(
            'class'   => \WooShop\Modules\WooCommerce\Reviews::class,
            'enabled' => true,
        ),

        'sale_countdown' => array(
            'class'   => \WooShop\Modules\WooCommerce\SaleCountdown::class,
            'enabled' => true,
        ),

        'size_guide' => array(
            'class'   => \WooShop\Modules\WooCommerce\SizeGuide::class,
            'enabled' => true,
        ),

        'social_sharing' => array(
            'class'   => \WooShop\Modules\WooCommerce\SocialSharing::class,
            'enabled' => true,
        ),

        'stock_scarcity' => array(
            'class'   => \WooShop\Modules\WooCommerce\StockScarcity::class,
            'enabled' => true,
        ),

        'variation_swatches' => array(
            'class'   => \WooShop\Modules\WooCommerce\VariationSwatches::class,
            'enabled' => true,
        ),

        'wishlist' => array(
            'class'   => \WooShop\Modules\WooCommerce\Wishlist::class,
            'enabled' => true,
        ),

    ),
);