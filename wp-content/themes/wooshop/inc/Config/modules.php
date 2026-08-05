<?php
/**
 * Registered Theme Modules
 *
 * @package WooShop
 */

use WooShop\Modules\Theme\Images;
use WooShop\Modules\Theme\MobileMenus;
use WooShop\Modules\Theme\Navigation;
use WooShop\Modules\Theme\Setup;
use WooShop\Modules\Theme\Sidebars;
use WooShop\Modules\Theme\Header;
use WooShop\Modules\WooCommerce\Bootstrap;

defined('ABSPATH') || exit;

return [

    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    */

    Setup::class,

    MobileMenus::class,

    Sidebars::class,

    Images::class,

    //Editor::class,

    Navigation::class,

    Header::class,

    //Footer::class,

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