<?php

/**
 * King Tailors Theme Customizer
 *
 * @package king_tailors
 *
 * King Tailors Theme Customizer
 * Optimized, Compact, Classic Theme Compatible
 */

defined('ABSPATH') || exit;


/* =========================================================
 * CUSTOMIZER REGISTER
 * ========================================================= */

function kt_customize_register($wp_customize)
{

    /* ------------------------------
     * Live Preview Support
     * ------------------------------ */

    foreach (['blogname', 'blogdescription', 'header_textcolor'] as $setting) {
        $wp_customize->get_setting($setting)->transport = 'postMessage';
    }

    if (isset($wp_customize->selective_refresh)) {

        $wp_customize->selective_refresh->add_partial('blogname', [
            'selector' => '.site-title a',
            'render_callback' => fn() => bloginfo('name')
        ]);

        $wp_customize->selective_refresh->add_partial('blogdescription', [
            'selector' => '.site-description',
            'render_callback' => fn() => bloginfo('description')
        ]);
    }


    /* =====================================================
     * CONTACT SECTION
     * ===================================================== */

    $wp_customize->add_section('kt_contact_section', [
        'title' => __('Contact Info', 'kt'),
        'priority' => 30,
    ]);

    $contact_fields = [

        'kt_phone' => [
            'label' => 'Phone Number',
            'default' => 'Your Mobile Number',
            'sanitize' => 'sanitize_text_field',
            'type' => 'text'
        ],

        'kt_email' => [
            'label' => 'Email Address',
            'default' => 'Your Email',
            'sanitize' => 'sanitize_email',
            'type' => 'text'
        ],

        'kt_address' => [
            'label' => 'Address',
            'default' => 'Your full address here',
            'sanitize' => 'sanitize_textarea_field',
            'type' => 'textarea'
        ],

        'kt_opening_time' => [
            'label' => 'Shop Opening Time',
            'default' => '10:00 AM - 9:00 PM',
            'sanitize' => 'sanitize_text_field',
            'type' => 'text',
            'description' => 'Example: 10:00 AM - 9:00 PM'
        ],

        'kt_holiday' => [
            'label' => 'Weekly Holiday',
            'default' => 'Sunday',
            'sanitize' => 'sanitize_text_field',
            'type' => 'text',
            'description' => 'Example: Sunday'
        ],

        'kt_google_map_embed' => [
            'label' => 'Google Map Embed URL',
            'default' => '',
            'sanitize' => 'wp_kses_post',
            'type' => 'textarea'
        ],

    ];

    foreach ($contact_fields as $id => $field) {

        $wp_customize->add_setting($id, [
            'default' => $field['default'],
            'sanitize_callback' => $field['sanitize'],
            'transport' => 'refresh'
        ]);

        $wp_customize->add_control($id, [
            'label' => __($field['label'], 'kt'),
            'section' => 'kt_contact_section',
            'type' => $field['type'],
            'description' => $field['description'] ?? ''
        ]);
    }


    /* =====================================================
     * WEB3FORMS SECTION
     * ===================================================== */

    $wp_customize->add_section('kt_contact_form_section', [
        'title' => __('Contact & Feedback Forms', 'kt'),
        'priority' => 35
    ]);

    foreach (
        [
            'kt_web3forms_contact_key' => 'Contact Form Access Key',
            'kt_web3forms_feedback_key' => 'Feedback Form Access Key'
        ] as $id => $label
    ) {

        $wp_customize->add_setting($id, [
            'sanitize_callback' => 'sanitize_text_field'
        ]);

        $wp_customize->add_control($id, [
            'label' => __($label, 'kt'),
            'section' => 'kt_contact_form_section',
            'type' => 'text'
        ]);
    }


    /* =====================================================
     * SOCIAL MEDIA SECTION
     * ===================================================== */

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

        /* URL */

        $wp_customize->add_setting("kt_{$key}_url", [
            'sanitize_callback' => 'esc_url_raw'
        ]);

        $wp_customize->add_control("kt_{$key}_url", [
            'label' => __("$label URL", 'kt'),
            'section' => 'kt_social_section',
            'type' => 'url'
        ]);


        /* Icon */

        $wp_customize->add_setting("kt_{$key}_icon", [
            'default' => "bi bi-$key",
            'sanitize_callback' => 'sanitize_text_field'
        ]);

        $wp_customize->add_control("kt_{$key}_icon", [
            'label' => __("$label Icon Class", 'kt'),
            'section' => 'kt_social_section',
            'type' => 'text'
        ]);
    }
}
add_action('customize_register', 'kt_customize_register');

/* =========================================================
 * CUSTOMIZER LIVE PREVIEW SCRIPT
 * ========================================================= */

function kt_customize_preview_js()
{
    wp_enqueue_script(
        'customizer',
        get_template_directory_uri() . '/js/customizer.js',
        ['customize-preview'],
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('customize_preview_init', 'kt_customize_preview_js');


    // CK-78912d4e-149b-43f5-b9ec-f1d307d9a7f3
    // FK-6e6ba11a-2f41-4f29-8de2-f06c1957cdb4