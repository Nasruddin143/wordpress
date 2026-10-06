<?php

namespace VirtualReviews\Inc;

defined( 'ABSPATH' ) || exit;

class Ajax {
	protected static $instance = null;

	public static function instance() {
		return self::$instance == null ? self::$instance = new self : self::$instance;
	}

	public function __construct() {
		add_action( 'wp_ajax_wvr_action', [ $this, 'ajax' ] );
	}

	public function ajax() {
		check_ajax_referer( 'wvr_security', 'security' );

		if ( ! current_user_can( 'manage_woocommerce' ) || empty( $_POST['sub_action'] ) ) {
			wp_send_json_error();
		}

		$func = sanitize_text_field( wp_unslash( $_POST['sub_action'] ) );
		if ( ! class_exists( __CLASS__, $func ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'function not exist', 'faview-virtual-reviews-for-woocommerce' ) ] );
		}

		$this->$func();

		wp_die();
	}

	public function add_multiple_reviews() {
		Reviews::instance()->add_multiple_reviews();
	}

	public function add_custom_reviews() {
		Reviews::instance()->add_custom_reviews();
	}

	public function search_product() {
		$keyword = isset( $_POST['keyword'] ) ? sanitize_text_field( $_POST['keyword'] ) : '';

		if ( ! $keyword ) {
			wp_die();
		}

		$arg = array(
			'post_status'    => 'publish',
			'post_type'      => 'product',
			'posts_per_page' => 50,
			's'              => $keyword
		);

		if ( function_exists( 'icl_get_default_language' ) ) {
			$arg['lang'] = icl_get_default_language();
		}

		$result         = [];
		$found_products = get_posts( $arg );

		foreach ( $found_products as $product ) {
			$result[] = [ 'id' => $product->ID, 'text' => $product->post_title ];
		}
		wp_send_json( $result );
	}

	public function search_user() {
		$keyword = isset( $_POST['keyword'] ) ? sanitize_text_field( $_POST['keyword'] ) : '';

		if ( ! $keyword ) {
			wp_die();
		}

		$arg = array(
			'posts_per_page' => 50,
			'search'         => "*{$keyword}*",
			'capability'     => 'edit_posts'
		);


		$result     = [];
		$found_user = get_users( $arg );

		foreach ( $found_user as $user ) {
			$result[] = [ 'id' => $user->ID, 'text' => $user->display_name ];
		}
		wp_send_json( $result );
	}

	public function add_review_from_product_page() {
		Reviews::instance()->add_review_from_product_page();
	}

	public function remove_single_schedule() {
		if ( empty( $_POST['timestamp'] ) || empty( $_POST['key'] ) ) {
			wp_send_json_error();
		}

		$timestamp = sanitize_text_field( $_POST['timestamp'] );
		$key       = sanitize_text_field( $_POST['key'] );
		$crons     = _get_cron_array();
		$hook      = 'faview_add_review_via_schedule';

		unset( $crons[ $timestamp ][ $hook ][ $key ] );

		if ( empty( $crons[ $timestamp ][ $hook ] ) ) {
			unset( $crons[ $timestamp ][ $hook ] );
		}

		if ( empty( $crons[ $timestamp ] ) ) {
			unset( $crons[ $timestamp ] );
		}

		$result = _set_cron_array( $crons, false );

		wp_send_json_success( [ 'result' => $result ] );
	}

	public function remove_all_schedule() {
		$crons = _get_cron_array();

		if ( ! empty( $crons ) && is_array( $crons ) ) {
			$hook = 'faview_add_review_via_schedule';

			foreach ( $crons as $timestamp => $cron ) {

				if ( ! isset( $cron[ $hook ] ) ) {
					continue;
				}

				unset( $crons[ $timestamp ] );
			}
		}

		$result = _set_cron_array( $crons, false );
		wp_send_json_success( [ 'result' => $result ] );
	}

	public function use_quantity_range() {
		$stt = isset( $_POST['stt'] ) ? sanitize_text_field( wp_unslash( $_POST['stt'] ) ) : '';
		$stt = $stt === 'true';
		update_option( 'wvr_use_quantity_range', $stt );
		wp_send_json_success( $stt );
	}
}
