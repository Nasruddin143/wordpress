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
function wooshop_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'wooshop_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'wooshop_customize_partial_blogdescription',
			)
		);
	}
}
add_action( 'customize_register', 'wooshop_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function wooshop_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function wooshop_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function wooshop_customize_preview_js() {
	wp_enqueue_script( 'wooshop-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'wooshop_customize_preview_js' );

/**
 * Add email and mobile contact settings.
 */
/**
 * Add a separate Theme Settings section for email and mobile.
 */
function my_theme_customize_register( $wp_customize ) {

    // 1. Add New Section
    $wp_customize->add_section( 'my_theme_settings_section', array(
        'title'       => __( 'Theme Settings', 'wooshop' ),
        'priority'    => 30, // Position in the customizer menu
        'description' => __( 'Manage your website contact information here.', 'wooshop' ),
    ) );

    // 2. Mobile Setting & Control
    $wp_customize->add_setting( 'mobile_contact_number', array(
        'default'   => '+919800000000',
        'transport' => 'refresh',
    ) );
    $wp_customize->add_control( 'mobile_contact_number', array(
        'label'    => __( 'Mobile Number', 'wooshop' ),
        'section'  => 'my_theme_settings_section', // Linked to the new section
        'type'     => 'text',
    ) );

    // 3. Email Setting & Control
    $wp_customize->add_setting( 'email_contact_address', array(
        'default'   => 'info@yourdomain.com',
        'transport' => 'refresh',
    ) );
    $wp_customize->add_control( 'email_contact_address', array(
        'label'    => __( 'Email Address', 'wooshop' ),
        'section'  => 'my_theme_settings_section', // Linked to the new section
        'type'     => 'text',
    ) );
}
add_action( 'customize_register', 'my_theme_customize_register' );