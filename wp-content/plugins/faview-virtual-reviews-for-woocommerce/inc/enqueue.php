<?php

namespace VirtualReviews\Inc;

defined( 'ABSPATH' ) || exit;

class Enqueue {
	protected static $instance = null;
	protected $slug = 'wvr-';

	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
	}

	public static function instance() {
		return self::$instance == null ? self::$instance = new self : self::$instance;
	}

	public function register_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$lib_styles  = [ 'select2', 'popup', 'calendar', 'table', 'segment', 'progress', 'input', 'icon', 'transition', 'dropdown', 'message', 'form', 'button', 'label', 'tab', 'checkbox', 'menu', 'accordion', 'header' ];
		$lib_scripts = [ 'select2', 'popup', 'calendar', 'transition', 'tab', 'progress', 'accordion', 'modal', 'dropdown', 'jqColorPicker', 'jquery.address' ];

		$styles  = [ 'settings', 'admin-review', 'product-list-page', 'schedule' ];
		$scripts = [
			'settings'          => [ 'jquery' ],
			'admin-review'      => [ 'jquery' ],
			'product-list-page' => [ 'jquery' ],
			'schedule'          => [ 'jquery' ],
		];

		foreach ( $lib_styles as $style ) {
			wp_register_style( $this->slug . $style, WVR_CONST['libs_url'] . $style . '.min.css', [], WVR_CONST['version'] );
		}

		foreach ( $styles as $style ) {
			wp_register_style( $this->slug . $style, WVR_CONST['css_url'] . $style . $suffix . '.css', '', WVR_CONST['version'] );
		}

		foreach ( $lib_scripts as $script ) {
			wp_register_script( $this->slug . $script, WVR_CONST['libs_url'] . $script . '.min.js', [ 'jquery' ], WVR_CONST['version'] );
		}

		foreach ( $scripts as $script => $depend ) {
			wp_register_script( $this->slug . $script, WVR_CONST['js_url'] . $script . $suffix . '.js', $depend, WVR_CONST['version'] );
		}
	}

	public function admin_enqueue_scripts() {
		global $wvr_pages;
		$wvr_pages[] = 'edit-product';
		$screen_id   = get_current_screen()->id;

		if ( ! in_array( $screen_id, (array) $wvr_pages ) ) {
			return;
		}

		$this->register_scripts();

		$settings = Data::instance();
		$scripts  = $styles = [];
		$localize = '';
		$params   = [
			'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
			'security' => wp_create_nonce( 'wvr_security' )
		];

		switch ( $screen_id ) {
			case $wvr_pages['settings']:
				require_once ABSPATH . 'wp-admin/includes/translation-install.php';

				wp_enqueue_script( 'jquery-ui-sortable' );

				$styles    = [ 'select2', 'segment', 'input', 'icon', 'transition', 'dropdown', 'form', 'button', 'label', 'tab', 'checkbox', 'menu', 'accordion', 'header', 'settings' ];
				$scripts   = [ 'select2', 'transition', 'dropdown', 'tab', 'accordion', 'jqColorPicker', 'jquery.address', 'settings' ];
				$localize  = 'settings';
				$languages = [];

				if ( function_exists( 'icl_get_default_language' ) ) {
					$translations = icl_get_languages();
					$default_lang = icl_get_default_language();
					foreach ( $translations as $code => $data ) {
						if ( $code == $default_lang ) {
							continue;
						}
						$languages[ $code ] = $data['native_name'];
					}
				}

				$saved    = $settings->get_params();
				$products = $parsed_products = [];

				if ( ! empty( $saved['review_rules'] ) && is_array( $saved['review_rules'] ) ) {
					foreach ( $saved['review_rules'] as $rule ) {
						$products = array_merge( $products, $rule['products'] ?? [], $rule['exclude_products'] ?? [] );
					}
					$saved['review_rules'] = array_filter( $saved['review_rules'] );
				}

				if ( ! empty( $products ) ) {
					$products = array_values( $products );
					$products = get_posts( [
						'post_type'   => 'product',
						'numberposts' => - 1,
						'include'     => $products
					] );

					if ( ! empty( $products ) ) {
						foreach ( $products as $product ) {
							$parsed_products[ $product->ID ] = $product->post_title;
						}
					}
				}

				$params['languages']   = $languages;
				$params['categories']  = Utils::get_product_categories();
				$params['settings']    = $saved;
				$params['productList'] = $parsed_products;

				break;

			case $wvr_pages['add_review_schedule']:
				global $wp_locale;
				wp_enqueue_script( 'jquery-ui-sortable' );

				$styles   = [ 'select2', 'segment', 'input', 'icon', 'transition', 'dropdown', 'form', 'button', 'label', 'tab', 'checkbox', 'menu', 'accordion', 'header', 'calendar', 'table', 'popup', 'schedule' ];
				$scripts  = [ 'select2', 'transition', 'dropdown', 'accordion', 'popup', 'calendar', 'schedule' ];
				$localize = 'schedule';

				$saved    = get_option( 'wvr_schedules_setting', [] );
				$products = $parsed_products = [];
				if ( ! empty( $saved ) && is_array( $saved ) ) {
					foreach ( $saved as $rule ) {
						$products = array_merge( $products, $rule['products'] ?? [], $rule['exclude_products'] ?? [] );
					}
				}

				if ( ! empty( $products ) ) {
					$products = array_values( $products );
					$products = get_posts( [
						'post_type'   => 'product',
						'numberposts' => - 1,
						'include'     => $products
					] );

					if ( ! empty( $products ) ) {
						foreach ( $products as $product ) {
							$parsed_products[ $product->ID ] = $product->post_title;
						}
					}
				}

				$params['categories']  = Utils::get_product_categories();
				$params['schedules']   = $saved;
				$params['productList'] = $parsed_products;
				$params['weekday']     = $wp_locale->weekday;

				$params['repeatTypes'] = [
					'daily'   => esc_html__( 'Daily', 'wepoi-woocommerce-point-reward' ),
					'weekly'  => esc_html__( 'Weekly', 'wepoi-woocommerce-point-reward' ),
					'monthly' => esc_html__( 'Monthly', 'wepoi-woocommerce-point-reward' ),
//					'none'    => esc_html__( 'No repeat', 'wepoi-woocommerce-point-reward' ),
				];;

				break;

			case $wvr_pages['add_review_manual']:
				$styles   = [ 'select2', 'popup', 'table', 'checkbox', 'segment', 'input', 'icon', 'transition', 'dropdown', 'form', 'button', 'calendar', 'label', 'progress', 'message', 'admin-review' ];
				$scripts  = [ 'select2', 'progress', 'transition', 'dropdown', 'popup', 'calendar', 'admin-review' ];
				$localize = 'admin-review';

				$params['adminUrl'] = admin_url();
				break;

			case 'edit-product':
				$styles   = [ 'product-list-page' ];
				$scripts  = [ 'product-list-page' ];
				$localize = 'product-list-page';

				break;
		}

		foreach ( $scripts as $script ) {
			wp_enqueue_script( $this->slug . $script );
		}

		foreach ( $styles as $style ) {
			wp_enqueue_style( $this->slug . $style );
		}

		if ( $localize ) {
			wp_localize_script( $this->slug . $localize, 'wvrParams', $params );
		}
	}

}
