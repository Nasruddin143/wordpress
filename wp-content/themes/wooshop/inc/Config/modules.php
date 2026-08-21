<?php
/**
 * WooShop Module Configuration.
 *
 * @package WooShop
 */

use WooShop\Modules\Theme\Asset;
use WooShop\Modules\Theme\Background;
use WooShop\Modules\Theme\Comments;
use WooShop\Modules\Theme\Customizer;
use WooShop\Modules\Theme\Editor;
use WooShop\Modules\Theme\Feeds;
use WooShop\Modules\Theme\Filters;
use WooShop\Modules\Theme\Header;
use WooShop\Modules\Theme\Jetpack;
use WooShop\Modules\Theme\Navigation;
use WooShop\Modules\Theme\Setup;
use WooShop\Modules\Theme\Template;
use WooShop\Modules\Theme\Widgets;

defined( 'ABSPATH' ) || exit;

return array(

    /*
     * ---------------------------------------------------------
     * Theme Foundation
     * ---------------------------------------------------------
     */
    Setup::class,

    /*
     * ---------------------------------------------------------
     * Assets
     * ---------------------------------------------------------
     */
    Asset::class,

    /*
     * ---------------------------------------------------------
     * WordPress Theme Modules
     * ---------------------------------------------------------
     */
    Navigation::class,
    Widgets::class,
    Editor::class,
    Customizer::class,
    Header::class,
    Background::class,
    Comments::class,
    Feeds::class,
    Filters::class,
    Template::class,
    Jetpack::class,
);