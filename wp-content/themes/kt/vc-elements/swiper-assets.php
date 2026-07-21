<?php

/**
 * Ensure Swiper assets are loaded only once
 */
function kt_ensure_swiper_loaded()
{
    // CSS
    if (!wp_style_is('swiper', 'enqueued') && !wp_style_is('swiper', 'registered')) {
        wp_enqueue_style('kt-swiper', get_template_directory_uri() . '/css/swiper-bundle.min.css', [], '11.0.0');
    } else {
        wp_enqueue_style('kt-swiper');
    }

    // JS
    if (!wp_script_is('swiper', 'enqueued') && !wp_script_is('swiper', 'registered')) {
        wp_enqueue_script('kt-swiper', get_template_directory_uri() . '/js/swiper-bundle.min.js', [], '11.0.0', true);
    } else {
        wp_enqueue_script('kt-swiper');
    }

    // Init JS
    if (!wp_script_is('kt-swiper-init', 'enqueued') && !wp_script_is('kt-swiper-init', 'registered')) {
        wp_enqueue_script('kt-swiper-init', get_template_directory_uri() . '/js/kt-swiper-init.js', ['kt-swiper'], '1.0.0', true);
    } else {
        wp_enqueue_script('kt-swiper-init');
    }
}