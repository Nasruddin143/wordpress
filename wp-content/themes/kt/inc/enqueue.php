<?php defined('ABSPATH') || exit;

/**
 * -----------------------------------------------------------------------------
 * ENQUEUE SCRIPTS & STYLES
 * -----------------------------------------------------------------------------
 */
function kt_scripts()
{
	// Styles
	wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', [], _S_VERSION);
	wp_enqueue_style('bs-icon', get_template_directory_uri() . '/css/bootstrap-icons.min.css', [], _S_VERSION);
	wp_enqueue_style('kt-style', get_stylesheet_uri(), [], _S_VERSION);

	// Scripts
	wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.bundle.min.js', [], _S_VERSION, true);
	wp_enqueue_script('customizer', get_template_directory_uri() . '/js/customizer.js', ['customize-preview'], _S_VERSION, true);
	wp_enqueue_script('custom', get_template_directory_uri() . '/js/custom.js', [], _S_VERSION, true);
	
	// Comments
	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	/**
	 * ---------------------------------------------------------
	 * SWIPER SLIDER ASSETS
	 * ---------------------------------------------------------
	 */
	if (is_front_page()) {
		wp_enqueue_style('kt-swiper', get_template_directory_uri() . '/css/swiper-bundle.min.css', [], '11.0.0');
		wp_enqueue_script('kt-swiper', get_template_directory_uri() . '/js/swiper-bundle.min.js', [], '11.0.0', true);
		wp_enqueue_script('kt-swiper-init', get_template_directory_uri() . '/js/kt-swiper-init.js', ['kt-swiper'], '1.0.0', true);
	}
	/**
	 * ---------------------------------------------------------
	 * WEB3FORMS SCRIPTS (CONTACT & FEEDBACK)
	 * ---------------------------------------------------------
	 */
	$is_contact = is_page_template('page-contact-us.php');
	$is_feedback = is_page_template('page-client-feedback.php');

	if (!$is_contact && !$is_feedback) {
		return;
	}

	// Common Web3Forms script (load once)
	wp_enqueue_script(
		'web3forms-client',
		'https://web3forms.com/client/script.js',
		[],
		'1.0',
		true
	);

	// Contact page only
	if ($is_contact) {
		wp_enqueue_script(
			'contact-form',
			get_template_directory_uri() . '/js/contact-form.js',
			['web3forms-client'],
			'1.0',
			true
		);
	}

	// Feedback page only
	if ($is_feedback) {
		wp_enqueue_script(
			'feedback-form',
			get_template_directory_uri() . '/js/feedback-form.js',
			['web3forms-client'],
			'1.0',
			true
		);
	}
}
add_action('wp_enqueue_scripts', 'kt_scripts');
