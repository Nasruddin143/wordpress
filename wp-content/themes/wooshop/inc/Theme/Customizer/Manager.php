<?php
/**
 * Theme Customizer Manager
 *
 * @package WooShop
 */

namespace WooShop\Theme\Customizer;

use WooShop\Theme\Customizer\Blog;
use WooShop\Theme\Customizer\Colors;
use WooShop\Theme\Customizer\Footer;
use WooShop\Theme\Customizer\Header;
use WooShop\Theme\Customizer\Layout;
use WooShop\Theme\Customizer\Panel;
use WooShop\Theme\Customizer\Performance;
use WooShop\Theme\Customizer\Social;
use WooShop\Theme\Customizer\Typography;

use WP_Customize_Manager;

defined('ABSPATH') || exit;

class Manager
{

    /**
     * Register hooks.
     *
     * @return void
     */
    public function register(): void
    {

        add_action(
            'customize_register',
            [$this, 'register_customizer']
        );
    }

    /**
     * Register customizer.
     *
     * @param WP_Customize_Manager $wp_customize
     * @return void
     */
    public function register_customizer(WP_Customize_Manager $wp_customize): void
    {

        Panel::register($wp_customize);

        Colors::register($wp_customize);

        Typography::register($wp_customize);

        Layout::register($wp_customize);

        Header::register($wp_customize);

        Footer::register($wp_customize);

        Blog::register($wp_customize);

        Social::register($wp_customize);

        Performance::register($wp_customize);

    }
}