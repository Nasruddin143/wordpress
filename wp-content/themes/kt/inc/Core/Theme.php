<?php
// Theme Core
namespace KT\Core;

// Theme Setup and Assets
use KT\Setup\ThemeSetup;
use KT\Setup\Assets;
use KT\Setup\SwiperAssets;

// Features (CPT + Taxonomies)
use KT\Features\PostTypes;
use KT\Features\Taxonomies;
use KT\Features\Widgets;

// WP Bakery Shortcodes
use KT\Shortcodes\SliderBanner;
use KT\Shortcodes\ClientTestimonials;
use KT\Shortcodes\ProductSlider;
use KT\Shortcodes\Brands;

// SEO + META
use KT\SEO\Schema;

// UI + MODULES
use KT\Helpers\UI;
use KT\Helpers\Bootstrap_NavWalker;
use KT\Helpers\Badges;
use KT\Helpers\Price;
use KT\Helpers\Ratings;
use KT\Helpers\Terms;
use KT\Modules\FAQ;
use KT\Modules\RelatedPosts;

// INTEGTRATIONS
use KT\Integrations\Jetpack;
use KT\Integrations\WooCommerce;

// CUSTOMIZER
use KT\Customizer\Customizer;

class Theme
{
    private static $instance = null;

    public static function get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->init_services();
    }

    private function init_services()
    {
        $services = [
            ThemeSetup::class,
            Assets::class,
            PostTypes::class,
            Taxonomies::class,
            SliderBanner::class,
            ClientTestimonials::class,
            ProductSlider::class,
            Brands::class,
            Schema::class,
            Bootstrap_NavWalker::class,
            Price::class,
            Ratings::class,
            Terms::class,
            UI::class,
            Jetpack::class,
            WooCommerce::class,
            FAQ::class,
            RelatedPosts::class,
            Widgets::class,
            Customizer::class,
            SwiperAssets::class,
        ];

        foreach ($services as $service) {
            new $service();
        }
    }
}
