<?php
/**
 * WordPress Customizer.
 *
 * Registers WooShop theme settings in the WordPress Customizer.
 *
 * @package WooShop
 */

namespace WooShop\Modules\Theme;

use WP_Customize_Manager;
use WooShop\Core\Module;

defined( 'ABSPATH' ) || exit;

/**
 * Handles WordPress Customizer functionality.
 */
class Customizer extends Module {

    /**
     * Register module hooks.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'customize_register',
            [ $this, 'register_controls' ]
        );
    }

    /**
     * Register theme Customizer controls.
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager.
     * @return void
     */
    public function register_controls( WP_Customize_Manager $wp_customize ): void {

        $wp_customize->add_section(
            'wooshop_general',
            [
                'title'       => esc_html__( 'WooShop General', 'wooshop' ),
                'description' => esc_html__(
                    'General WooShop theme settings.',
                    'wooshop'
                ),
                'priority'    => 30,
            ]
        );

        $wp_customize->add_setting(
            'wooshop_footer_text',
            [
                'default'           => '',
                'type'              => 'theme_mod',
                'sanitize_callback' => 'wp_kses_post',
            ]
        );

        $wp_customize->add_control(
            'wooshop_footer_text',
            [
                'label'       => esc_html__( 'Footer Text', 'wooshop' ),
                'description' => esc_html__(
                    'Optional text displayed in the footer.',
                    'wooshop'
                ),
                'section'     => 'wooshop_general',
                'type'        => 'textarea',
            ]
        );
    }
}