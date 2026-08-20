<?php
/**
 * WooShop Theme Customizer Module
 *
 * Registers WooShop Customizer sections and controls for
 * theme branding, layout, and product display settings.
 *
 * The Customizer preview script is loaded only inside
 * the Customizer preview context.
 *
 * @package WooShop
 */

declare(strict_types=1);

namespace WooShop\Modules\Theme;

defined('ABSPATH') || exit;

use WP_Customize_Manager;
use WP_Customize_Section;
use WP_Customize_Control;

final class Customizer
{
    /**
     * Register the Customizer module.
     *
     * @return void
     */
    public function register(): void
    {
        add_action(
            'customize_register',
            [$this, 'register_customizer']
        );

        add_action(
            'customize_preview_init',
            [$this, 'enqueue_preview_script']
        );
    }

    /**
     * Register WooShop Customizer settings and controls.
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager.
     *
     * @return void
     */
    public function register_customizer(
        WP_Customize_Manager $wp_customize
    ): void {
        /*
         * ---------------------------------------------------------
         * Theme Section
         * ---------------------------------------------------------
         */

        $wp_customize->add_section(
            'wooshop_theme',
            [
                'title'       => __('Theme', 'wooshop'),
                'description' => __(
                    'Configure the main WooShop theme appearance and layout.',
                    'wooshop'
                ),
                'priority'    => 30,
            ]
        );

        /*
         * ---------------------------------------------------------
         * Branding Controls
         * ---------------------------------------------------------
         */

        $wp_customize->add_setting(
            'wooshop_branding_color',
            [
                'default'           => '#212529',
                'type'              => 'theme_mod',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );

        $wp_customize->add_control(
            'wooshop_branding_color',
            [
                'label'    => __('Branding Color', 'wooshop'),
                'section'  => 'wooshop_theme',
                'type'     => 'color',
                'settings' => 'wooshop_branding_color',
            ]
        );

        /*
         * ---------------------------------------------------------
         * Layout Controls
         * ---------------------------------------------------------
         */

        $wp_customize->add_setting(
            'wooshop_container_width',
            [
                'default'           => '1200',
                'type'              => 'theme_mod',
                'sanitize_callback' => [$this, 'sanitize_container_width'],
            ]
        );

        $wp_customize->add_control(
            'wooshop_container_width',
            [
                'label'       => __('Container Width', 'wooshop'),
                'description' => __(
                    'Set the maximum content container width in pixels.',
                    'wooshop'
                ),
                'section'     => 'wooshop_theme',
                'type'        => 'number',
                'input_attrs' => [
                    'min'  => 960,
                    'max'  => 1920,
                    'step' => 10,
                ],
            ]
        );

        /*
         * ---------------------------------------------------------
         * Product Controls
         * ---------------------------------------------------------
         */

        $wp_customize->add_setting(
            'wooshop_products_per_row',
            [
                'default'           => 4,
                'type'              => 'theme_mod',
                'sanitize_callback' => [$this, 'sanitize_products_per_row'],
            ]
        );

        $wp_customize->add_control(
            'wooshop_products_per_row',
            [
                'label'       => __('Products Per Row', 'wooshop'),
                'description' => __(
                    'Set the number of products displayed per row.',
                    'wooshop'
                ),
                'section'     => 'wooshop_theme',
                'type'        => 'select',
                'choices'     => [
                    2 => __('2 Products', 'wooshop'),
                    3 => __('3 Products', 'wooshop'),
                    4 => __('4 Products', 'wooshop'),
                    5 => __('5 Products', 'wooshop'),
                    6 => __('6 Products', 'wooshop'),
                ],
            ]
        );
    }

    /**
     * Sanitize the theme container width.
     *
     * @param mixed $value Container width value.
     *
     * @return int
     */
    public function sanitize_container_width(mixed $value): int
    {
        $value = absint($value);

        if ($value < 960) {
            return 960;
        }

        if ($value > 1920) {
            return 1920;
        }

        return $value;
    }

    /**
     * Sanitize the number of products displayed per row.
     *
     * @param mixed $value Products per row value.
     *
     * @return int
     */
    public function sanitize_products_per_row(mixed $value): int
    {
        $value = absint($value);

        $allowed_values = [
            2,
            3,
            4,
            5,
            6,
        ];

        if (!in_array($value, $allowed_values, true)) {
            return 4;
        }

        return $value;
    }

    /**
     * Enqueue the Customizer preview JavaScript.
     *
     * This script is loaded only in the Customizer preview
     * and is never registered as a global frontend asset.
     *
     * @return void
     */
    public function enqueue_preview_script(): void
    {
        $script_path = get_theme_file_path(
            '/assets/build/js/customizer.min.js'
        );

        $script_uri = get_theme_file_uri(
            '/assets/build/js/customizer.min.js'
        );

        if (!file_exists($script_path)) {
            return;
        }

        wp_enqueue_script(
            'wooshop-customizer',
            $script_uri,
            ['customize-preview'],
            (string) filemtime($script_path),
            true
        );
    }
}