<?php defined('ABSPATH') || exit;

/**
 * King Tailors Theme Cleanup & Performance Optimization
 * Hybrid Mode: Classic + Block Theme Compatible
 */


/* -------------------------------------------------------
   HELPER: Detect Block Theme
------------------------------------------------------- */
function kt_is_block_theme()
{
    return function_exists('wp_is_block_theme') && wp_is_block_theme();
}


/* -------------------------------------------------------
   CORE CLEANUP (SAFE FOR BOTH)
------------------------------------------------------- */
add_action('after_setup_theme', function () {

    /* SECURITY */
    add_filter('xmlrpc_enabled', '__return_false');


    /* CLEAN HEAD */
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');

    // Keep REST + oEmbed for block themes
    if (!kt_is_block_theme()) {
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
    }

    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
    add_filter('feed_links_show_comments_feed', '__return_false');


    /* REMOVE EMOJIS */
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');


    /* WPBAKERY CLEANUP (ONLY FOR CLASSIC) */
    if (!kt_is_block_theme()) {
        remove_action('wp_head', 'visual_composer_generator');
        add_filter('vc_hide_updater', '__return_true');
    }


    /* GLOBAL STYLES (ONLY REMOVE FOR CLASSIC) */
    if (!kt_is_block_theme()) {
        remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
        remove_action('wp_head', 'wp_global_styles', 1);
        remove_action('wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles');
    }


    /* REMOVE IMAGE INLINE CSS */
    remove_action('wp_head', 'wp_print_auto_sizes_contain_css_fix', 1);


    /* BLOCK THEME SUPPORT */
    if (kt_is_block_theme()) {
        add_theme_support('block-templates');
        add_theme_support('editor-styles');
        add_theme_support('wp-block-styles');
        add_theme_support('align-wide');
        add_theme_support('responsive-embeds');
    }
}, 0);



/* -------------------------------------------------------
   FRONTEND PERFORMANCE CLEANUP
------------------------------------------------------- */
add_action('wp_enqueue_scripts', function () {

    // ❗ IMPORTANT: Do NOT touch styles for block themes
    if (kt_is_block_theme()) {
        return;
    }

    /* REMOVE GUTENBERG CSS (CLASSIC ONLY) */
    wp_dequeue_style('global-styles');
    wp_deregister_style('global-styles');

    wp_dequeue_style('classic-theme-styles');
    wp_deregister_style('classic-theme-styles');

    wp_dequeue_style('wp-block-library');
    wp_deregister_style('wp-block-library');

    wp_dequeue_style('wp-block-library-theme');
    wp_deregister_style('wp-block-library-theme');


    /* REMOVE DASHICONS (frontend only) */
    if (!is_user_logged_in()) {
        wp_deregister_style('dashicons');
    }
}, 999);



/* -------------------------------------------------------
   BLOCK ASSETS CONTROL
------------------------------------------------------- */
if (!kt_is_block_theme()) {
    add_filter('should_load_separate_core_block_assets', '__return_false', 99);
}


/* -------------------------------------------------------
   REMOVE WPBAKERY META (ADVANCED)
------------------------------------------------------- */
add_action('after_setup_theme', function () {

    if (!kt_is_block_theme() && class_exists('Vc_Manager')) {

        // Modern WPBakery
        if (function_exists('visual_composer')) {
            remove_action('wp_head', [visual_composer(), 'addMetaData'], 1);
        }

        // Legacy fallback
        remove_action('wp_head', 'visual_composer_generator');
    }
}, 0);
