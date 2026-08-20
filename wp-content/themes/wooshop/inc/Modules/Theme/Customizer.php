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
use WooShop\Core\ModuleManager;

defined( 'ABSPATH' ) || exit;

/**
 * Theme customizer module.
 */
final class Customizer extends Module {

    /**
     * Constructor.
     *
     * @param ModuleManager $manager Module manager.
     */
    public function __construct( ModuleManager $manager ) {
        parent::__construct( $manager );
    }

    /**
     * Register module.
     *
     * @return void
     */
    public function register(): void {

        add_action(
            'customize_register',
            array( $this, 'register_controls' )
        );

        add_action(
            'customize_preview_init',
            array( $this, 'enqueue_preview_script' )
        );
    }

    /**
     * Register Customizer controls.
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager.
     *
     * @return void
     */
    public function register_controls(
        WP_Customize_Manager $wp_customize
    ): void {

        /*
         * ---------------------------------------------------------
         * WooShop Theme Panel
         * ---------------------------------------------------------
         */
        $wp_customize->add_panel(
            'wooshop_theme',
            array(
                'title'       => esc_html__( 'WooShop Theme', 'wooshop' ),
                'description' => esc_html__(
                    'Configure WooShop theme settings.',
                    'wooshop'
                ),
                'priority'    => 10,
            )
        );

        /*
         * ---------------------------------------------------------
         * Header Section
         * ---------------------------------------------------------
         */
        $wp_customize->add_section(
            'wooshop_header',
            array(
                'title'    => esc_html__( 'Header', 'wooshop' ),
                'panel'    => 'wooshop_theme',
                'priority' => 10,
            )
        );

        /*
         * Header announcement.
         */
        $wp_customize->add_setting(
            'wooshop_header_announcement',
            array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'wooshop_header_announcement',
            array(
                'label'       => esc_html__(
                    'Announcement Text',
                    'wooshop'
                ),
                'description' => esc_html__(
                    'Optional announcement displayed in the header.',
                    'wooshop'
                ),
                'section'     => 'wooshop_header',
                'type'        => 'text',
            )
        );

        /*
         * ---------------------------------------------------------
         * Layout Section
         * ---------------------------------------------------------
         */
        $wp_customize->add_section(
            'wooshop_layout',
            array(
                'title'    => esc_html__( 'Layout', 'wooshop' ),
                'panel'    => 'wooshop_theme',
                'priority' => 20,
            )
        );

        /*
         * Container width.
         */
        $wp_customize->add_setting(
            'wooshop_container_width',
            array(
                'default'           => '1200px',
                'sanitize_callback' => array( $this, 'sanitize_css_size' ),
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'wooshop_container_width',
            array(
                'label'       => esc_html__(
                    'Container Width',
                    'wooshop'
                ),
                'description' => esc_html__(
                    'Set the maximum content width.',
                    'wooshop'
                ),
                'section'     => 'wooshop_layout',
                'type'        => 'text',
            )
        );

        /*
         * ---------------------------------------------------------
         * Footer Section
         * ---------------------------------------------------------
         */
        $wp_customize->add_section(
            'wooshop_footer',
            array(
                'title'    => esc_html__( 'Footer', 'wooshop' ),
                'panel'    => 'wooshop_theme',
                'priority' => 30,
            )
        );

        $wp_customize->add_setting(
            'wooshop_footer_text',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            'wooshop_footer_text',
            array(
                'label'       => esc_html__(
                    'Footer Text',
                    'wooshop'
                ),
                'section'     => 'wooshop_footer',
                'type'        => 'textarea',
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
    public function sanitize_css_size( string $value ): string {

        $value = trim( $value );

        if ( preg_match( '/^\d+(?:\.\d+)?(?:px|rem|em|%|vw|vh)$/', $value ) ) {
            return $value;
        }

        return '1200px';
    }

    /**
     * Enqueue Customizer preview script.
     *
     * @return void
     */
    public function enqueue_preview_script(): void {

        wp_enqueue_script(
            'wooshop-customizer',
            get_template_directory_uri()
            . '/assets/build/js/customizer.min.js',
            array( 'customize-preview' ),
            wp_get_theme()->get( 'Version' ),
            true
        );
    }
}