<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/11/2018
 * Time: 11:00 SA
 */

namespace VirtualReviews\Inc;

defined( 'ABSPATH' ) || exit;

class Review_Form {

	protected static $instance = null;

	public static function instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function __construct() {
		add_filter( 'woocommerce_product_review_comment_form_args', array( $this, 'sv_add_wc_review_notes' ) );
		add_action( 'woocommerce_review_after_comment_text', array( $this, 'show_comments' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'set_comment_cookies', array( $this, 'add_auto_reply' ) );
	}

	public function enqueue_scripts() {
		if ( is_product() && is_single() ) {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style( "wvr-review-form", WVR_CONST['css_url'] . "review-form{$suffix}.css", '', WVR_CONST['version'] );

			$data                    = Data::instance();
			$canned_text_color       = $data->get_param( 'canned_text_color' );
			$canned_bg_color         = $data->get_param( 'canned_bg_color' );
			$canned_text_hover_color = $data->get_param( 'canned_text_hover_color' );
			$canned_hover_color      = $data->get_param( 'canned_hover_color' );
			$purchased_text_color    = $data->get_param( 'purchased_text_color' );
			$purchased_bg_color      = $data->get_param( 'purchased_bg_color' );
			$purchased_icon_color    = $data->get_param( 'purchased_icon_color' );
			$purchased_label_icon    = $data->get_param( 'purchased_label_icon' );

			$custom_css = $data->get_param( 'custom_css' );

			if ( is_array( $custom_css ) ) {
				$custom_css = implode( '', $custom_css );
			}

			$custom_css .= ".wvr-customer-pick .wvr-select-sample-cmt{color: {$canned_text_color}; background-color:{$canned_bg_color};}";
			$custom_css .= ".wvr-customer-pick .wvr-select-sample-cmt:hover{color: {$canned_text_hover_color}; background-color:{$canned_hover_color};}";
			$custom_css .= ".wvr-product-purchased{color: {$purchased_text_color}; background-color:{$purchased_bg_color};}";
			$custom_css .= ".wvr-icon-purchased{color: {$purchased_icon_color};}";
			$custom_css .= ".wvr-icon-purchased:before{content:'\\" . $purchased_label_icon . "'; margin-right:5px}";

			wp_add_inline_style( 'wvr-review-form', $custom_css );

			wp_enqueue_script( 'wvr-review-form', WVR_CONST['js_url'] . "review-form{$suffix}.js", array( 'jquery' ), WVR_CONST['version'] );
			$auto_rating   = $data->get_param( 'auto_rating' );
			$first_comment = $data->get_param( 'auto_fill_review' );

			wp_localize_script( 'wvr-review-form', 'wvrParams',
				[
					'auto_rating'   => $auto_rating,
					'first_comment' => $first_comment
				] );

		}
	}

	public function sv_add_wc_review_notes( $review_form ) {
		// Shown to all reviewers below "Your Review" field
		$lang = 'default';

		if ( function_exists( 'icl_get_current_language' ) ) {
			$default_lang = icl_get_default_language();
			$current_lang = icl_get_current_language();
			if ( $current_lang !== $default_lang ) {
				$lang = $current_lang;
			}
		}

		$data                 = Data::instance();
		$sample_cmts          = (array) $data->get_param( 'cmt_frontend' );
		$sample_cmts          = $sample_cmts[ $lang ] ?? [];
		$show_canned          = $data->get_param( 'show_canned' );
		$canned_style_desktop = $data->get_param( 'canned_style_desktop' );
		$canned_style_mobile  = $data->get_param( 'canned_style_mobile' );
		$sample_cmts          = array_filter( $sample_cmts );

		if ( $show_canned && ! empty( $sample_cmts ) ) {
			ob_start();

			Utils::get_template( 'reviews-canned', [
				'sample_cmts'          => $sample_cmts,
				'canned_style_desktop' => $canned_style_desktop,
				'canned_style_mobile'  => $canned_style_mobile,
			] );

			$canned = ob_get_clean();

			if ( ! empty( $canned ) ) {
				$review_form['comment_notes_after'] .= $canned;
			}
		}

		return $review_form;
	}

	public function show_comments( $comment ) {
		if ( $comment->comment_type !== 'review' || $comment->comment_parent > 0 ) {
			return;
		}

		$show_purchased_label = Data::instance()->get_param( 'show_purchased_label' );

		if ( ! $show_purchased_label ) {
			return;
		}

		global $product;
		$current_id        = $product->get_id();
		$comment_author_id = $comment->user_id;
		$string            = '';

		if ( $comment_author_id > 0 ) {
			//real cmt

			$arg = array(
				'limit'      => - 1,
				'meta_key'   => '_customer_user',
				'meta_value' => 1,
				'post_type'  => wc_get_order_types(),
				'status'     => array_keys( wc_get_is_paid_statuses() ),
			);

			$orders = wc_get_orders( $arg );

			if ( empty( $orders ) ) {
				return;
			}

			$result = array();

			foreach ( $orders as $order ) {
				foreach ( $order->get_items() as $item ) {
					$data = $item->get_data();
					if ( $current_id == $data['product_id'] ) {
						if ( $product->is_type( 'variable' ) && $data['variation_id'] != 0 ) {
							if ( ! isset( $result[ $data['variation_id'] ] ) ) {
								$result[ $data['variation_id'] ] = 0;
							}
							$result[ $data['variation_id'] ] += $data['quantity'];
						} else {
							if ( ! isset( $result[ $data['product_id'] ] ) ) {
								$result[ $data['product_id'] ] = 0;
							}
							$result[ $data['product_id'] ] += $data['quantity'];
						}
					}
				}
			}

			if ( $product->is_type( 'variable' ) ) {
				foreach ( $result as $var_id => $qty ) {
					$var    = wc_get_product( $var_id );
					$attrs  = wc_get_formatted_variation( $var->get_variation_attributes(), true, false );
					$attrs  = str_replace( ', ', '-', $attrs );
					$attrs  = apply_filters( 'wvr-variation-label', $attrs );
					$string .= sprintf( "<span class='wvr-product-purchased'>%s x%s</span>", $attrs, $qty );
				}
			} else {
				foreach ( $result as $qty ) {
					$unit   = _n( 'product', 'products', $qty, 'faview-virtual-reviews-for-woocommerce' );
					$string .= sprintf( "<span class='wvr-product-purchased'>%s %s</span>", $qty, $unit );
				}
			}

		} else {
			//virtual cmt

			$comment_id = $comment->comment_ID;

			if ( $product->is_type( 'variable' ) ) {
				$bought_data = get_comment_meta( $comment_id, 'wvr_variation', true );

				if ( ! empty( $bought_data ) ) {
					if ( is_array( $bought_data ) ) {

						foreach ( $bought_data as $vid => $data ) {
							$attrs  = wc_get_formatted_variation( $data['attributes'], true, false );
							$attrs  = str_replace( ', ', '-', $attrs );
							$string .= sprintf( "<span class='wvr-product-purchased'>%s %s</span>", $attrs, $data['quantity'] ? 'x' . $data['quantity'] : '' );
						}

					} else {
						$var = wc_get_product( $bought_data );
						if ( is_object( $var ) ) {
							$attrs  = wc_get_formatted_variation( $var->get_variation_attributes(), true, false );
							$attrs  = str_replace( ', ', '-', $attrs );
							$attrs  = apply_filters( 'wvr-variation-label', $attrs );
							$string .= sprintf( "<span class='wvr-product-purchased'>%s x1</span>", $attrs );
						}
					}
				}

			} else {
				$bought_quantity = get_comment_meta( $comment_id, 'wvr_bought_quantity', true );
				$bought_quantity = max( $bought_quantity, 1 );
				$string          .= sprintf( "<span class='wvr-product-purchased'>%s %s</span>", $bought_quantity, _n( 'product', 'products', $bought_quantity, 'faview-virtual-reviews-for-woocommerce' ) );
			}
		}

		Utils::get_template( 'purchased-products', [ 'string' => $string ] );
	}

	public function add_auto_reply( \WP_Comment $comment ) {
		$settings = Data::instance();

		if ( $settings->get_param( 'enable_reply_real_review' ) && ! empty( $_POST['rating'] ) ) {
			$rating          = absint( $_POST['rating'] );
			$lang            = 'default';
			$replies         = $settings->get_param( 'reply_content' );
			$reply_author_id = $settings->get_param( 'reply_author' );
			$reply_author    = get_user_by( 'id', $reply_author_id );

			if ( ! empty( $replies[ $lang ][ $rating ] ) && $reply_author ) {
				$cmts     = $replies[ $lang ][ $rating ];
				$key      = array_rand( $cmts, 1 );
				$cmt      = $cmts[ $key ];
				$time     = date( 'Y-m-d H:i:s', current_time( 'U' ) );
				$gmt_time = get_gmt_from_date( $time );
				$pid      = $comment->comment_post_ID;

				$reply_data = [
					'comment_post_ID'      => $pid,
					'comment_author'       => $reply_author->display_name,
					'comment_author_email' => $reply_author->user_email,
					'comment_author_url'   => $reply_author->user_url,
					'comment_content'      => $cmt,
					'comment_type'         => 'comment',
					'comment_parent'       => $comment->comment_ID,
					'user_ID'              => $reply_author_id,
					'user_id'              => $reply_author_id,
					'comment_approved'     => 1,
					'comment_date'         => $time,
					'comment_date_gmt'     => $gmt_time,
				];

				wp_insert_comment( $reply_data );
			}
		}
	}
}
