<?php
/**
 * WooShop Module Configuration
 *
 * Defines all modules loaded by the WooShop application.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme Modules Declaration
 */
use WooShop\Modules\Theme\Accessibility;
use WooShop\Modules\Theme\Admin;
use WooShop\Modules\Theme\AdminBar;
use WooShop\Modules\Theme\Assets;
use WooShop\Modules\Theme\BlockEditor;
use WooShop\Modules\Theme\Comments;
use WooShop\Modules\Theme\Customizer;
use WooShop\Modules\Theme\Editor;
use WooShop\Modules\Theme\Embeds;
use WooShop\Modules\Theme\Excerpt;
use WooShop\Modules\Theme\Feeds;
use WooShop\Modules\Theme\Filters;
use WooShop\Modules\Theme\Hooks;
use WooShop\Modules\Theme\ImageSizes;
use WooShop\Modules\Theme\Localization;
use WooShop\Modules\Theme\Metadata;
use WooShop\Modules\Theme\MobileCommerce;
use WooShop\Modules\Theme\Navigation;
use WooShop\Modules\Theme\Pagination;
use WooShop\Modules\Theme\Performance;
use WooShop\Modules\Theme\PostFormats;
use WooShop\Modules\Theme\PostTypes;
use WooShop\Modules\Theme\Search;
use WooShop\Modules\Theme\Security;
use WooShop\Modules\Theme\Setup;
use WooShop\Modules\Theme\Sidebars;
use WooShop\Modules\Theme\Taxonomies;
use WooShop\Modules\Theme\Template;
use WooShop\Modules\Theme\Widgets;


/**
 * WooCommerce Modules Declaration
 */
//use WooShop\Modules\WooCommerce\Bootstrap;
use WooShop\Modules\WooCommerce\Compare;
use WooShop\Modules\WooCommerce\FreeShippingBar;
use WooShop\Modules\WooCommerce\MiniCart;
use WooShop\Modules\WooCommerce\MobileSales;
use WooShop\Modules\WooCommerce\PaymentIcons;
use WooShop\Modules\WooCommerce\ProductBrands;
use WooShop\Modules\WooCommerce\ProductCustomTabs;
use WooShop\Modules\WooCommerce\ProductFilters;
use WooShop\Modules\WooCommerce\ProductVideos;
use WooShop\Modules\WooCommerce\ProductWaitlist;
use WooShop\Modules\WooCommerce\QuickView;
use WooShop\Modules\WooCommerce\Reviews;
use WooShop\Modules\WooCommerce\SaleCountdown;
use WooShop\Modules\WooCommerce\SizeGuide;
use WooShop\Modules\WooCommerce\SocialSharing;
use WooShop\Modules\WooCommerce\StockScarcity;
use WooShop\Modules\WooCommerce\VariationSwatches;
use WooShop\Modules\WooCommerce\Wishlist;



return array(

    /**
     * Theme modules.
     */
    'theme' => array(

        Assets::class,
        Accessibility::class,
        Localization::class,
        MobileCommerce::class,
        Setup::class,
        Navigation::class,
        Widgets::class,
        Sidebars::class,
        Customizer::class,
        ImageSizes::class,
        Comments::class,
        Template::class,
        Editor::class,
        Excerpt::class,
        Pagination::class,
        Search::class,
        PostTypes::class,
        Taxonomies::class,
        Feeds::class,
        Performance::class,
        Security::class,
        Admin::class,
        Metadata::class,
        Hooks::class,
        Filters::class,
        AdminBar::class,
        PostFormats::class,
        Embeds::class,
        BlockEditor::class,

    ),

    /**
     * WooCommerce modules.
     *
     * These will be populated as each feature is implemented.
     */
    'woocommerce' => array(

        ProductFilters::class,
        VariationSwatches::class,
        Wishlist::class,
        QuickView::class,
        Reviews::class,
        SizeGuide::class,
        Compare::class,
        MobileSales::class,
        ProductBrands::class,
        StockScarcity::class,
        FreeShippingBar::class,
        ProductCustomTabs::class,
        ProductWaitlist::class,
        ProductVideos::class,
        SaleCountdown::class,
        SocialSharing::class,
        PaymentIcons::class,
        MiniCart::class,
        WooShop\Modules\WooCommerce\Assets::class,
//        Bootstrap::class

    ),
);