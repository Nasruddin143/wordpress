<?php
/**
 * Customizer Module.
 *
 * Handles WooShop Customizer settings.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WP_Customize_Manager;
use WooShop\Core\Module;

defined('ABSPATH') || exit;

/**
 * Theme customizer module.
 */
final class Customizer extends Module
{
    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void
    {

        add_action('customize_register', array($this, 'register_controls'));

        add_action('customize_preview_init', array($this, 'enqueue_preview_script'));
    }

    /**
     * Register Customizer controls.
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager.
     *
     * @return void
     */
    public function register_controls(WP_Customize_Manager $wp_customize): void
    {
        /*
         * ---------------------------------------------------------
         * WooShop Theme Panel
         * ---------------------------------------------------------
         */
        $wp_customize->add_panel(
            'wooshop_theme',
            array(
                'title' => esc_html__('WooShop Theme', 'wooshop'),
                'description' => esc_html__('Configure WooShop theme settings.', 'wooshop'),
                'priority' => 10,
            )
        );

        /*
         * ---------------------------------------------------------
         * Topbar Section
         * ---------------------------------------------------------
         */
        $wp_customize->add_section(
            'wooshop_topbar',
            array(
                'title' => esc_html__('Topbar', 'wooshop'),
                'panel' => 'wooshop_theme',
                'priority' => 5,
            )
        );

        // Contact Number Setting
        $wp_customize->add_setting(
            'wooshop_header_phone',
            array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field',
                'transport' => 'refresh',
            )
        );

        $wp_customize->add_control(
            'wooshop_header_phone',
            array(
                'label' => esc_html__('Mobile Number', 'wooshop'),
                'description' => esc_html__('Phone number displayed in the topbar.', 'wooshop'),
                'section' => 'wooshop_topbar',
                'type' => 'text',
            )
        );

        // Email Address Setting
        $wp_customize->add_setting(
            'wooshop_header_email',
            array(
                'default' => '',
                'sanitize_callback' => 'sanitize_email',
                'transport' => 'refresh',
            )
        );

        $wp_customize->add_control(
            'wooshop_header_email',
            array(
                'label' => esc_html__('Email Address', 'wooshop'),
                'description' => esc_html__('Email address displayed in the topbar.', 'wooshop'),
                'section' => 'wooshop_topbar',
                'type' => 'email',
            )
        );
    }



    /**
     * Sanitize CSS size value.
     *
     * @param string $value CSS size.
     *
     * @return string
     */
    public function sanitize_css_size(string $value): string
    {

        $value = trim($value);

        if (preg_match('/^\d+(?:\.\d+)?(?:px|rem|em|%|vw|vh)$/', $value)) {
            return $value;
        }

        return '1200px';
    }

    /**
     * Enqueue Customizer preview script.
     *
     * @return void
     */
    public function enqueue_preview_script(): void
    {

        wp_enqueue_script('wooshop-customizer', get_template_directory_uri() . '/assets/build/js/customizer.min.js',
            array('customize-preview'), wp_get_theme()->get('Version'), true
        );
    }
}