<?php
/**
 * WooShop Theme Customizer
 *
 * @package WooShop
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wooshop_customize_register($wp_customize)
{
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector' => '.site-title a',
                'render_callback' => 'wooshop_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector' => '.site-description',
                'render_callback' => 'wooshop_customize_partial_blogdescription',
            )
        );
    }
}
add_action('customize_register', 'wooshop_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function wooshop_customize_partial_blogname()
{
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function wooshop_customize_partial_blogdescription()
{
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function wooshop_customize_preview_js()
{
    wp_enqueue_script('wooshop-customizer', get_template_directory_uri() . '/js/customizer.js', array('customize-preview'), _S_VERSION, true);
}
add_action('customize_preview_init', 'wooshop_customize_preview_js');


/**
 * Add a separate Theme Settings section for email and mobile.
 */
function my_theme_customize_register($wp_customize)
{

    // 1. Add New Section
    $wp_customize->add_section('my_theme_settings_section', array(
        'title' => __('Theme Settings', 'wooshop'),
        'priority' => 30, // Position in the customizer menu
        'description' => __('Manage your website contact information here.', 'wooshop'),
    ));

    // 2. Mobile Setting & Control
    $wp_customize->add_setting('mobile_contact_number', array(
        'default' => '+919800000000',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('mobile_contact_number', array(
        'label' => __('Mobile Number', 'wooshop'),
        'section' => 'my_theme_settings_section', // Linked to the new section
        'type' => 'text',
    ));

    // 3. Email Setting & Control
    $wp_customize->add_setting('email_contact_address', array(
        'default' => 'info@yourdomain.com',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('email_contact_address', array(
        'label' => __('Email Address', 'wooshop'),
        'section' => 'my_theme_settings_section', // Linked to the new section
        'type' => 'text',
    ));

    /*
    |--------------------------------------------------------------------------
    | Shop Address
    |--------------------------------------------------------------------------
    */

    $wp_customize->add_setting('shop_address', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('shop_address', array(
        'label' => __('Shop Address', 'wooshop'),
        'section' => 'my_theme_settings_section',
        'type' => 'textarea',
    ));

    /*
    |--------------------------------------------------------------------------
    | Google Map
    |--------------------------------------------------------------------------
    */

    $wp_customize->add_setting('google_map_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('google_map_url', array(
        'label' => __('Google Maps URL', 'wooshop'),
        'description' => __('Paste your Google Maps share link.', 'wooshop'),
        'section' => 'my_theme_settings_section',
        'type' => 'url',
    ));

    /*
    |--------------------------------------------------------------------------
    | Shop Opening and Closing Time
    |--------------------------------------------------------------------------
    */

    $wp_customize->add_setting('shop_opening_days', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('shop_opening_days', array(
        'label' => __('Days Open', 'wooshop'),
        'section' => 'my_theme_settings_section',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('shop_opening_time', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('shop_opening_time', array(
        'label' => __('Opening & Closing Time', 'wooshop'),
        'section' => 'my_theme_settings_section',
        'type' => 'text',
    ));

    /*
    |--------------------------------------------------------------------------
    | Shoop Holiday
    |--------------------------------------------------------------------------
    */

    $wp_customize->add_setting('shop_holiday', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('shop_holiday', array(
        'label' => __('Shop Holiday', 'wooshop'),
        'section' => 'my_theme_settings_section',
        'type' => 'text',
    ));

    /*
    |--------------------------------------------------------------------------
    | Facebook
    |--------------------------------------------------------------------------
    */

    $wp_customize->add_setting('facebook_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('facebook_url', array(
        'label' => __('Facebook URL', 'wooshop'),
        'section' => 'my_theme_settings_section',
        'type' => 'url',
    ));

    /*
    |--------------------------------------------------------------------------
    | Instagram
    |--------------------------------------------------------------------------
    */

    $wp_customize->add_setting('instagram_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('instagram_url', array(
        'label' => __('Instagram URL', 'wooshop'),
        'section' => 'my_theme_settings_section',
        'type' => 'url',
    ));

    /*
    |--------------------------------------------------------------------------
    | X (Twitter)
    |--------------------------------------------------------------------------
    */

    $wp_customize->add_setting('twitter_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('twitter_url', array(
        'label' => __('X (Twitter) URL', 'wooshop'),
        'section' => 'my_theme_settings_section',
        'type' => 'url',
    ));
}
add_action('customize_register', 'my_theme_customize_register');