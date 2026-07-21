<?php

namespace KT\Setup;

class Assets
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'load_assets']);
    }

    public function load_assets()
    {
        // Retrive Theme Version
        $theme_version = wp_get_theme()->get('Version');

        // Styles
        wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', [], $theme_version,);
        wp_enqueue_style('bs-icon', get_template_directory_uri() . '/assets/css/bootstrap-icons.min.css', [], $theme_version);
        wp_enqueue_style('kt-style', get_stylesheet_uri(), [], false);

        // Scripts
        wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js', [], $theme_version, true);
        wp_enqueue_script('customizer', get_template_directory_uri() . '/assets/js/customizer.js', ['customize-preview'], $theme_version, true);
        wp_enqueue_script('custom', get_template_directory_uri() . '/assets/js/custom.js', [], $theme_version, true);

        // Comments
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
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
            '',
            true
        );

        // Contact page only
        if ($is_contact) {
            wp_enqueue_script(
                'contact-form',
                get_template_directory_uri() . '/assets/js/contact-form.js',
                ['web3forms-client'],
                $theme_version,
                true
            );
        }

        // Feedback page only
        if ($is_feedback) {
            wp_enqueue_script(
                'feedback-form',
                get_template_directory_uri() . '/assets/js/feedback-form.js',
                ['web3forms-client'],
                $theme_version,
                true
            );
        }
    }
}
