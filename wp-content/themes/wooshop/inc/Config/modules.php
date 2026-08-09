<?php
/**
 * Registered Theme Modules
 *
 * @package WooShop
 */

use WooShop\Modules\Theme\Footer;
//use WooShop\Modules\Theme\Header\Navigation;
use WooShop\Modules\Theme\Images;
use WooShop\Modules\Theme\MobileMenus;
use WooShop\Modules\Theme\Setup;
use WooShop\Modules\Theme\Sidebars;
use WooShop\Modules\Theme\Header;
use WooShop\Modules\WooCommerce\Bootstrap;
use WooShop\Modules\WooCommerce\Integration;


defined('ABSPATH') || exit;

return [

    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    */

    WooShop\Modules\Theme\Header::class,

    WooShop\Modules\Theme\Footer::class,

    WooShop\Modules\WooCommerce\Integration::class,

    WooShop\Modules\WooCommerce\ProductCard::class,

    WooShop\Modules\WooCommerce\SingleProduct::class,

    WooShop\Modules\WooCommerce\VariationSwatches::class,

    WooShop\Modules\WooCommerce\VariationSwatchMeta::class,

    Setup::class,

    MobileMenus::class,

    Sidebars::class,

    Images::class,

    //Editor::class,

    //Navigation::class,

    //Blog::class,

    //Search::class,

    //Comments::class,

    //Accessibility::class,

    /*
    |--------------------------------------------------------------------------
    | WooCommerce
    |--------------------------------------------------------------------------
    */

    Bootstrap::class,

];