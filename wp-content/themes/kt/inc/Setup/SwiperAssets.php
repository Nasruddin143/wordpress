<?php
namespace KT\Setup;

defined('ABSPATH') || exit;

class SwiperAssets
{
    private static $swiper_loaded = false;

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'register_assets']);
    }

    /**
     * Register only (DO NOT enqueue here)
     */
    public function register_assets()
    {
        $version = wp_get_theme()->get('Version');

        wp_register_style(
            'kt-swiper',
            get_template_directory_uri() . '/assets/css/swiper-bundle.min.css',
            [],
            $version
        );

        wp_register_script(
            'kt-swiper',
            get_template_directory_uri() . '/assets/js/swiper-bundle.min.js',
            [],
            $version,
            true
        );

        wp_register_script(
            'kt-swiper-init',
            get_template_directory_uri() . '/assets/js/kt-swiper-init.js',
            ['kt-swiper'],
            $version,
            true
        );
    }

    /**
     * Load Swiper (ONLY ONCE)
     */
    public static function load_swiper()
    {
        if (self::$swiper_loaded) {
            return; // already loaded ✅
        }

        wp_enqueue_style('kt-swiper');
        wp_enqueue_script('kt-swiper');
        wp_enqueue_script('kt-swiper-init');

        self::$swiper_loaded = true;
    }
}