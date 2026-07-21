<?php

namespace KT\Setup;

class ThemeSetup
{

	public function __construct()
	{
		add_action('after_setup_theme', [$this, 'setup']);

		if (did_action('after_setup_theme')) {
			$this->setup();
		}
	}

	public function setup()
	{
		/**
		 * -----------------------------------------------------------------
		 * TRANSLATION SUPPORT
		 * -----------------------------------------------------------------
		 */
		load_theme_textdomain('kt', get_template_directory() . '/languages');


		/**
		 * -----------------------------------------------------------------
		 * CORE WORDPRESS SUPPORT
		 * -----------------------------------------------------------------
		 */

		add_theme_support('title-tag');
		
		add_theme_support('automatic-feed-links');

		add_theme_support('post-thumbnails');


		/**
		 * -----------------------------------------------------------------
		 * OPTIMIZED IMAGE SIZES (SEO + PERFORMANCE)
		 * -----------------------------------------------------------------
		 */

		set_post_thumbnail_size(1200, 675, true); // 16:9 SEO standard

		/* Archive / Cards */
		add_image_size('kt-card', 600, 400, true);
		add_image_size('kt-product-card', 480, 480, true);

		/* Product Images */
		add_image_size('kt-product-main', 1000, 1000, true);
		add_image_size('kt-product-grid', 600, 600, true);
		add_image_size('kt-product-thumb', 300, 300, true);

		/* Hero / Slider */
		add_image_size('kt-hero', 1920, 600, true);
		add_image_size('kt-slide-desktop', 1400, 600, true);
		add_image_size('kt-slide-tablet', 992, 600, true);
		add_image_size('kt-slide-mobile', 576, 600, true);

		/* Content */
		add_image_size('kt-content-lg', 1200, 800, false);
		add_image_size('kt-content', 900, 600, false);

		/* Blog */
		add_image_size('kt-blog-image', 1200, 700, true);

		/* Utility */
		add_image_size('kt-thumb', 150, 150, true);
		add_image_size('kt-brand', 240, 120, false);
		add_image_size('kt-lightbox', 1600, 1200, false);

		add_image_size('kt-site-logo', 160, 160, true);
		add_image_size('kt-page-banner', 1920, 420, true);
		add_image_size('kt-site-banner', 1905, 230, true);

		/**
		 * -----------------------------------------------------------------
		 * NAVIGATION MENUS
		 * -----------------------------------------------------------------
		 */

		register_nav_menus([
			'primary' => __('Primary Menu', 'kt'),
			'cooler' => __('Footer Coolers', 'kt'),
			'sewing' => __('Footer Machines', 'kt'),
			'accessory' => __('Footer Accessories', 'kt'),
			'footer_legal' => __('Footer Legal Links', 'kt'),
			// 'language'     => __('Topbar Language', 'kt'),
		]);


		/**
		 * -----------------------------------------------------------------
		 * HTML5 SUPPORT (SEO + MODERN MARKUP)
		 * -----------------------------------------------------------------
		 */

		add_theme_support('html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		]);


		/**
		 * -----------------------------------------------------------------
		 * LOGO SUPPORT
		 * -----------------------------------------------------------------
		 */

		add_theme_support('custom-logo', [
			'height' => 256,
			'width' => 256,
			'flex-width' => true,
			'flex-height' => true,
		]);


		/**
		 * -----------------------------------------------------------------
		 * CUSTOM HEADER SUPPORT
		 * -----------------------------------------------------------------
		 */

		add_theme_support('custom-header', [
			'width' => 1920,
			'height' => 230,
			'flex-width' => true,
			'flex-height' => true,
			'header-text' => false,
		]);


		/**
		 * -----------------------------------------------------------------
		 * CUSTOM BACKGROUND
		 * -----------------------------------------------------------------
		 */

		add_theme_support('custom-background', [
			'default-color' => 'ffffff',
		]);


		/**
		 * -----------------------------------------------------------------
		 * WIDGET SUPPORT
		 * -----------------------------------------------------------------
		 */

		add_theme_support('customize-selective-refresh-widgets');


		/**
		 * -----------------------------------------------------------------
		 * WPBAKERY COMPATIBILITY
		 * -----------------------------------------------------------------
		 */

		add_theme_support('post-formats', [
			'image',
			'gallery',
			'video',
		]);

		/**
		 * -----------------------------------------------------------------
		 * REMOVE THEME SUPPORT FOR GLOBAL STYLES
		 * -----------------------------------------------------------------
		 */
		//remove_theme_support('global-styles');
	}
}
