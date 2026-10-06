<?php
/**
 * Plugin Name: Faview - Virtual Reviews for WooCommerce Premium
 * Plugin URI: https://villatheme.com/extensions/faview-virtual-reviews-for-woocommerce/
 * Description: Faview - Virtual Reviews for WooCommerce creates virtual reviews, display canned reviews to increase your conversion rate.
 * Author: VillaTheme
 * Version: 1.0.5
 * Author URI: http://villatheme.com
 * Text Domain: faview-virtual-reviews-for-woocommerce
 * Domain Path: /languages
 * Copyright 2022 - 2023 VillaTheme.com. All rights reserved.
 * Requires at least: 5.0
 * Tested up to: 6.1
 * WC requires at least: 5.0
 * WC tested up to: 7.3
 * Requires PHP: 7.0
 */

namespace VirtualReviews;

use VirtualReviews\Inc\Admin;
use VirtualReviews\Inc\Ajax;
use VirtualReviews\Inc\Data;
use VirtualReviews\Inc\Enqueue;
use VirtualReviews\Inc\Review_Form;
use VirtualReviews\Inc\Reviews;
use VirtualReviews\Inc\Schedules;
use VirtualReviews\Inc\Settings;
use VirtualReviews\Inc\Utils;

defined( 'ABSPATH' ) || exit;

define( 'WVR_CONST', [
	'version'        => '1.0.5',
	'plugin_name'    => 'Faview - Virtual Reviews for WooCommerce Premium',
	'slug'           => 'faview-virtual-reviews-for-woocommerce',
	'assets_slug'    => 'wvr-',
	'file'           => __FILE__,
	'basename'       => plugin_basename( __FILE__ ),
	'plugin_dir'     => plugin_dir_path( __FILE__ ),
	'templates_dir'  => plugin_dir_path( __FILE__ ) . 'templates/',
	'js_url'         => plugins_url( 'assets/js/', __FILE__ ),
	'css_url'        => plugins_url( 'assets/css/', __FILE__ ),
	'img_url'        => plugins_url( 'assets/img/', __FILE__ ),
	'libs_url'       => plugins_url( 'assets/libs/', __FILE__ ),
	'codecanyon_pid' => 38520992,
] );

require_once plugin_dir_path( __FILE__ ) . 'autoload.php';

class VirtualReviews {

	protected $checker;

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'init' ] );
		add_action( 'admin_notices', [ $this, 'plugin_require_notice' ] );
		register_activation_hook( __FILE__, [ $this, 'active' ] );
	}

	public function init() {
		$this->checker = new \WP_Error();
		global $wp_version;
		$php_require = '7.0';
		$wp_require  = '5.0';
		$wc_require  = '5.0';

		if ( version_compare( phpversion(), $php_require, '<' ) ) {
			$this->checker->add( '', sprintf( '%s %s', esc_html__( 'require PHP version at least', 'faview-virtual-reviews-for-woocommerce' ), $php_require ) );
		}

		if ( version_compare( $wp_version, $wp_require, '<' ) ) {
			$this->checker->add( '', sprintf( '%s %s', esc_html__( 'require WordPress version at least', 'faview-virtual-reviews-for-woocommerce' ), $wp_require ) );
		}

		if ( ! class_exists( 'WooCommerce' ) ) {
			$this->checker->add( '', esc_html__( 'require WooCommerce installed and activated', 'faview-virtual-reviews-for-woocommerce' ) );
		}

		$wc_version = get_option( 'woocommerce_version' );
		if ( version_compare( $wc_version, $wc_require, '<' ) ) {
			$this->checker->add( '', sprintf( '%s %s', esc_html__( 'require WooCommerce version at least', 'faview-virtual-reviews-for-woocommerce' ), $wc_require ) );
		}

		if ( $this->checker->has_errors() ) {
			return;
		}

		add_filter( 'plugin_action_links_' . WVR_CONST['basename'], [ $this, 'setting_link' ] );

		$this->load_text_domain();

		$this->load_class();
	}

	protected function load_class() {
		Enqueue::instance();
		Reviews::instance();
		Schedules::instance();
		Ajax::instance();
		Review_Form::instance();

		if ( is_admin() ) {
			Admin::instance();
			Settings::instance();
			$this->support();
		}
	}

	public function plugin_require_notice() {
		if ( ! $this->checker->has_errors() ) {
			return;
		}

		$messages = $this->checker->get_error_messages();
		foreach ( $messages as $message ) {
			printf( "<div id='message' class='error'><p>%s %s</p></div>", WVR_CONST['plugin_name'], wp_kses_post( $message ) );
		}
	}

	public function setting_link( $links ) {
		$settings_link = [
			sprintf( "<a href='%1s' >%2s</a>", esc_url( admin_url( 'admin.php?page=wvr-settings' ) ), esc_html__( 'Settings', 'faview-virtual-reviews-for-woocommerce' ) )
		];

		return array_merge( $settings_link, $links );
	}

	public function load_text_domain() {
		$locale   = determine_locale();
		$locale   = apply_filters( 'plugin_locale', $locale, 'faview-virtual-reviews-for-woocommerce' );
		$basename = plugin_basename( dirname( __FILE__ ) );

		unload_textdomain( 'faview-virtual-reviews-for-woocommerce' );

		load_textdomain( 'faview-virtual-reviews-for-woocommerce', WP_LANG_DIR . "/{$basename}/{$basename}-{$locale}.mo" );
		load_plugin_textdomain( 'faview-virtual-reviews-for-woocommerce', false, $basename . '/languages' );
	}

	/*Fix for upgrade from free version*/
	public function active() {
		$option = get_option( 'wvr_data' );

		$all_products_rule = [
			'rule_id'   => 1653528212978,
			'rule_name' => 'All products',
			'comments'  => [
				'default' => Utils::get_sample_reviews()
			],
		];

		if ( ! empty( $option ) && isset( $option['cmt'] ) ) {
			$rating_rate    = [ 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, ];
			$current_rating = $option['rating'];

			if ( in_array( $current_rating, [ '5-5', '4-4', '3-3', '2-2', '1-1' ] ) ) {
				$i                 = substr( $current_rating, 0, 1 );
				$rating_rate[ $i ] = 100;

				$all_products_rule['comments']['default'][ $i ] = $option['cmt'];
			} else {
				$all_products_rule['comments']['default'][5] = $option['cmt'];

				switch ( $current_rating ) {
					case '1-5':
						$rating_rate[1] = $rating_rate[2] = $rating_rate[3] = $rating_rate[4] = $rating_rate[5] = 20;
						break;
					case '2-5':
						$rating_rate[2] = $rating_rate[3] = $rating_rate[4] = $rating_rate[5] = 25;
						break;
					case '3-5':
						$rating_rate[3] = $rating_rate[4] = 33;
						$rating_rate[5] = 34;
						break;
					case '4-5':
						$rating_rate[4] = $rating_rate[5] = 50;
						break;
				}
			}

			$option['rating_rate']             = $rating_rate;
			$option['review_rules']            = [ 1653528212978 ];
			$names                             = $option['names'];
			$option['names']                   = [];
			$option['names']['default']        = $names;
			$option['cmt_frontend']['default'] = $option['cmt_frontend'];

			unset( $option['cmt'] );
			unset( $option['rating'] );

			update_option( 'wvr_cmt_rule_1653528212978', $all_products_rule );
			update_option( 'wvr_data', $option );

		} elseif ( empty( $option ) ) {
			update_option( 'wvr_cmt_rule_1653528212978', $all_products_rule );
		}
	}

	public function support() {
		if ( ! class_exists( 'VillaTheme_Plugin_Check_Update' ) ) {
			include_once WVR_CONST['plugin_dir'] . 'support/check_update.php';
		}

		if ( ! class_exists( 'VillaTheme_Plugin_Updater' ) ) {
			include_once WVR_CONST['plugin_dir'] . 'support/update.php';
		}

		if ( ! class_exists( 'VillaTheme_Support_Pro' ) ) {
			include_once WVR_CONST['plugin_dir'] . 'support/support.php';
		}

		new \VillaTheme_Support_Pro(
			array(
				'support'   => 'https://villatheme.com/supports/forum/plugins/faview-virtual-reviews-for-woocommerce',
				'docs'      => 'http://docs.villatheme.com/?item=faview',
				'review'    => 'https://codecanyon.net/downloads',
				'css'       => WVR_CONST['css_url'],
				'image'     => WVR_CONST['img_url'],
				'slug'      => 'faview-virtual-reviews-for-woocommerce',
				'menu_slug' => 'virtual-reviews',
				'version'   => WVR_CONST['version']
			)
		);

		$key         = Data::instance()->get_param( 'auto_update_key' );
		$setting_url = admin_url( 'admin.php?page=wvr-settings' );

		new \VillaTheme_Plugin_Check_Update (
			WVR_CONST['version'],                    // current version
			'https://villatheme.com/wp-json/downloads/v3',  // update path
			WVR_CONST['basename'],                  // plugin file slug
			WVR_CONST['slug'],
			21055, //Pro id on VillaTheme
			$key,
			$setting_url
		);

		new \VillaTheme_Plugin_Updater( WVR_CONST['basename'], WVR_CONST['slug'], $setting_url );
	}

}

new VirtualReviews();








