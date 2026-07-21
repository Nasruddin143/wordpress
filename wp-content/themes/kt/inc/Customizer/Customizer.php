<?php

namespace KT\Customizer;

defined('ABSPATH') || exit;

class Customizer
{
    public function __construct()
    {
        add_action('customize_register', [$this, 'register']);
        add_action('customize_preview_init', [$this, 'preview_js']);
    }

    /**
     * =====================================================
     * REGISTER CUSTOMIZER
     * =====================================================
     */
    public function register($wp_customize)
    {
        $this->live_preview($wp_customize);

        $this->contact_section($wp_customize);
        $this->web3forms_section($wp_customize);
        $this->social_section($wp_customize);
    }

    /**
     * =====================================================
     * LIVE PREVIEW
     * =====================================================
     */
    private function live_preview($wp_customize)
    {
        foreach (['blogname', 'blogdescription', 'header_textcolor'] as $setting) {
            if ($wp_customize->get_setting($setting)) {
                $wp_customize->get_setting($setting)->transport = 'postMessage';
            }
        }

        if (isset($wp_customize->selective_refresh)) {

            $wp_customize->selective_refresh->add_partial('blogname', [
                'selector' => '.site-title a',
                'render_callback' => function () {
                    bloginfo('name');
                }
            ]);

            $wp_customize->selective_refresh->add_partial('blogdescription', [
                'selector' => '.site-description',
                'render_callback' => function () {
                    bloginfo('description');
                }
            ]);
        }
    }

    /**
     * =====================================================
     * CONTACT SECTION
     * =====================================================
     */
    private function contact_section($wp_customize)
    {
        $wp_customize->add_section('kt_contact_section', [
            'title' => __('Contact Info', 'kt'),
            'priority' => 30,
        ]);

        $fields = [
            'kt_phone' => ['Phone Number', 'Your Mobile Number', 'sanitize_text_field', 'text'],
            'kt_email' => ['Email Address', 'Your Email', 'sanitize_email', 'text'],
            'kt_address' => ['Address', 'Your full address here', 'sanitize_textarea_field', 'textarea'],
            'kt_opening_time' => ['Shop Opening Time', '10:00 AM - 9:00 PM', 'sanitize_text_field', 'text'],
            'kt_holiday' => ['Weekly Holiday', 'Sunday', 'sanitize_text_field', 'text'],
            'kt_google_map_embed' => ['Google Map Embed URL', '', 'wp_kses_post', 'textarea'],
        ];

        foreach ($fields as $id => $f) {
            $this->add_field($wp_customize, [
                'id' => $id,
                'label' => $f[0],
                'default' => $f[1],
                'sanitize' => $f[2],
                'type' => $f[3],
                'section' => 'kt_contact_section',
            ]);
        }
    }

    /**
     * =====================================================
     * WEB3FORMS SECTION
     * =====================================================
     */
    private function web3forms_section($wp_customize)
    {
        $wp_customize->add_section('kt_contact_form_section', [
            'title' => __('Contact & Feedback Forms', 'kt'),
            'priority' => 35
        ]);

        $fields = [
            'kt_web3forms_contact_key' => 'Contact Form Access Key',
            'kt_web3forms_feedback_key' => 'Feedback Form Access Key'
        ];

        foreach ($fields as $id => $label) {
            $this->add_field($wp_customize, [
                'id' => $id,
                'label' => $label,
                'sanitize' => 'sanitize_text_field',
                'section' => 'kt_contact_form_section',
            ]);
        }
    }

    /**
     * =====================================================
     * SOCIAL MEDIA SECTION
     * =====================================================
     */
    private function social_section($wp_customize)
    {
        $wp_customize->add_section('kt_social_section', [
            'title' => __('Social Media', 'kt'),
            'priority' => 40,
        ]);

        $platforms = [
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'twitter-x' => 'Twitter',
            'linkedin' => 'LinkedIn',
            'youtube' => 'YouTube'
        ];

        foreach ($platforms as $key => $label) {

            // URL
            $this->add_field($wp_customize, [
                'id' => "kt_{$key}_url",
                'label' => "$label URL",
                'sanitize' => 'esc_url_raw',
                'type' => 'url',
                'section' => 'kt_social_section',
            ]);

            // Icon
            $this->add_field($wp_customize, [
                'id' => "kt_{$key}_icon",
                'label' => "$label Icon Class",
                'default' => "bi bi-$key",
                'sanitize' => 'sanitize_text_field',
                'section' => 'kt_social_section',
            ]);
        }
    }

    /**
     * =====================================================
     * REUSABLE FIELD METHOD
     * =====================================================
     */
    private function add_field($wp_customize, $args)
    {
        $defaults = [
            'id' => '',
            'label' => '',
            'default' => '',
            'sanitize' => 'sanitize_text_field',
            'type' => 'text',
            'section' => '',
            'description' => '',
        ];

        $args = wp_parse_args($args, $defaults);

        $wp_customize->add_setting($args['id'], [
            'default' => $args['default'],
            'sanitize_callback' => $args['sanitize'],
            'transport' => 'refresh'
        ]);

        $wp_customize->add_control($args['id'], [
            'label' => __($args['label'], 'kt'),
            'section' => $args['section'],
            'type' => $args['type'],
            'description' => $args['description']
        ]);
    }

    /**
     * =====================================================
     * CUSTOMIZER PREVIEW JS
     * =====================================================
     */
    public function preview_js()
    {
        wp_enqueue_script(
            'kt-customizer',
            get_template_directory_uri() . '/js/customizer.js',
            ['customize-preview'],
            wp_get_theme()->get('Version'),
            true
        );
    }
}