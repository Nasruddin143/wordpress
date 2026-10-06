<?php

namespace VirtualReviews\Inc;

defined( 'ABSPATH' ) || exit;

class Reviews {
	protected static $instance = null;
	protected $review_counter = 0;
	protected $threshold;
	protected $time_array;
	protected $comment_list;
	protected $error_products = [];
	protected $lang = 'default';
	protected $icl_lang_list = [];
	protected $langs_array = [];

	/**
	 * @var Data
	 */
	protected $settings;

	public static function instance() {
		return self::$instance == null ? self::$instance = new self : self::$instance;
	}

	public function __construct() {
		$this->threshold = apply_filters( 'wvr_add_review_threshold', 50 );
		$this->settings  = Data::instance();

		add_filter( 'handle_bulk_actions-edit-comments', [ $this, 'bulk_add_reply' ], 10, 3 );
	}

	public function random_rating( $weights ) {
		$result = 5;

		if ( empty( $weights ) ) {
			return $result;
		}

		$random  = rand();
		$randmax = getrandmax();
		$rand    = ( (float) $random / (float) $randmax ) * 100;

		foreach ( $weights as $value => $weight ) {
			if ( $rand < $weight ) {
				$result = $value;
				break;
			}
			$rand -= $weight;
		}

		return $result;
	}

	public function set_current_lang( $lang ) {
		$this->lang = $lang;
	}

	public function add_multiple_reviews() {
		$this->lang = ! empty( $_POST['lang'] ) ? sanitize_text_field( wp_unslash( $_POST['lang'] ) ) : 'default';

		if ( function_exists( 'icl_get_languages' ) ) {
			$this->icl_lang_list = icl_get_languages();
			$default_lang        = icl_get_default_language();

			if ( ! isset( $_POST['langs_array'] ) ) {
				if ( isset( $_POST['next_lang'] ) && $_POST['next_lang'] ) {
					$this->langs_array = [];
				} else {
					$selected_langs = $this->settings->get_param( 'add_to_other_langs' );

					$parse_langs = [];
					if ( ! empty( $selected_langs ) ) {
						foreach ( $selected_langs as $code ) {
							$parse_langs[ $code ] = $this->icl_lang_list[ $code ]['native_name'] ?? '';
						}
					} else {
						$parse_langs = wp_list_pluck( $this->icl_lang_list, 'native_name', 'language_code' );
					}

					$this->langs_array = $parse_langs;
				}
			} else {
				$this->langs_array = wc_clean( $_POST['langs_array'] );
			}

			if ( isset( $this->langs_array[ $default_lang ] ) ) {
				unset( $this->langs_array[ $default_lang ] );
			}
		}

		$names = $this->settings->get_param( 'names' );

		if ( empty( $names[ $this->lang ] ) ) {
			$next_lang = array_key_first( $this->langs_array );
			unset( $this->langs_array[ $next_lang ] );
			$lang_name = $this->icl_lang_list[ $this->lang ]['native_name'] ?? esc_html__( 'your settings', 'faview-virtual-reviews-for-woocommerce' );
			wp_send_json_error( [
				'message'     => sprintf( esc_html__( 'Empty names list in %s', 'faview-virtual-reviews-for-woocommerce' ), $lang_name ),
				'lang'        => $next_lang,
				'langs_array' => $this->langs_array
			] );
		}


		$use_random_quantity = ! empty( $_POST['use_random_quantity'] ) && $_POST['use_random_quantity'] === 'true';
		$step                = ! empty( $_POST['step'] ) ? absint( $_POST['step'] ) : 1;
		$paged               = ! empty( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;
		$from                = ! empty( $_POST['from'] ) ? sanitize_text_field( $_POST['from'] ) : 'today';
		$to                  = ! empty( $_POST['to'] ) ? sanitize_text_field( $_POST['to'] ) : 'today';

		$from = strtotime( $from );
		$to   = strtotime( $to ) + DAY_IN_SECONDS - 1;

		if ( $use_random_quantity ) {
			$from_qty = absint( $_POST['from_qty'] );
			$to_qty   = absint( $_POST['to_qty'] );
			$cmt_qty  = rand( $from_qty, $to_qty );
			$this->handle_with_smaller_threshold( 1, $step, $cmt_qty, $from, $to );

		} else {
			if ( empty( $_POST['qty'] ) ) {
				wp_send_json_error( [ 'message' => esc_html__( 'Please input quantity of reviews per each product', 'faview-virtual-reviews-for-woocommerce' ) ] );
			}

			$cmt_qty = absint( $_POST['qty'] );

			if ( $cmt_qty > $this->threshold ) {
				$time_array = ! empty( $_POST['time_array'] ) ? wc_clean( $_POST['time_array'] ) : [];
				$this->handle_with_greater_threshold( $step, $paged, $cmt_qty, $time_array, $from, $to );
			} else {
				$product_limit = floor( $this->threshold / $cmt_qty );
				$this->handle_with_smaller_threshold( $product_limit, $step, $cmt_qty, $from, $to );
			}
		}

	}

	public function get_products( $step, $product_limit ) {
		$args = [
			'post_type'      => 'product',
			'paged'          => $step,
			'posts_per_page' => $product_limit,
		];

		if ( ! empty( $_POST['include_products'] ) ) {
			$args['post__in'] = wc_clean( $_POST['include_products'] );
		}

		if ( ! empty( $_POST['exclude_products'] ) ) {
			$args['post__not_in'] = wc_clean( $_POST['exclude_products'] );
		}

		if ( ! empty( $_POST['include_cats'] ) ) {
			$args['tax_query'][] = [
				'taxonomy'         => 'product_cat',
				'terms'            => wc_clean( $_POST['include_cats'] ),
				'include_children' => false,
				'operator'         => 'IN'
			];
		}

		if ( ! empty( $_POST['exclude_cats'] ) ) {
			$args['tax_query'][] = [
				'taxonomy'         => 'product_cat',
				'terms'            => wc_clean( $_POST['exclude_cats'] ),
				'include_children' => false,
				'operator'         => 'NOT IN'
			];
		}

		if ( ! empty( $args['tax_query'] ) ) {
			$args['tax_query']['relation'] = 'AND';
		}

		if ( function_exists( 'icl_get_default_language' ) ) {
			$args['lang'] = icl_get_default_language();
		}

		$query = new \WP_Query( $args );

		return [ 'count' => $query->found_posts, 'products' => $query->get_posts() ];
	}

	public function set_error_product( $pid, $name, $message = '' ) {

		$this->error_products[ $pid ] = sprintf( "<a href='%s' target='_blank'>%s %s</a>",
			esc_url( admin_url( "post.php?post={$pid}&action=edit" ) ), esc_html( $name ), $message ? "(" . esc_html( $message ) . ")" : '' );
	}

	public function handle_with_smaller_threshold( $product_limit, $step, $cmt_qty, $from, $to ) {
		$get_products   = $this->get_products( $step, $product_limit );
		$products       = $get_products['products'];
		$total_products = $get_products['count'];
		$response       = [
			'error_products' => [],
			'step'           => false,
			'percent'        => 100
		];

		if ( ! empty( $products ) && is_array( $products ) ) {
			foreach ( $products as $product ) {
				$pid = $origin_pid = $product->ID;

				if ( function_exists( 'pll_get_post' ) && $this->lang !== 'default' ) {
					$pid = pll_get_post( $pid, $this->lang );
					if ( ! $pid ) {
						continue;
					}
				}

				$comment_list = $this->find_comment_list( $origin_pid );

				if ( ! $comment_list && ! $this->settings->get_param( 'add_empty_comment' ) ) {
					$this->set_error_product( $pid, $product->post_title, esc_html__( 'No comment matched', 'faview-virtual-reviews-for-woocommerce' ) );
					continue;
				}

				$this->comment_list = $comment_list;

				$time_array = Utils::generate_time_array( $cmt_qty, $from, $to );

				for ( $i = 0; $i < $cmt_qty; $i ++ ) {
					$time = $time_array[ $i ];
					$this->add_single_review( $pid, $time );
				}

				\WC_Comments::clear_transients( $pid );
			}

			$response['error_products'] = $this->error_products;
			$next_step                  = $step + 1;

			if ( $next_step * $product_limit <= $total_products ) {
				$response['step']    = $next_step;
				$response['percent'] = floor( ( $step * $product_limit / $total_products ) * 100 );
			} else {
				if ( ! empty( $this->langs_array ) ) {
					$next_lang = array_key_first( $this->langs_array );
					unset( $this->langs_array[ $next_lang ] );
					$response['next_lang']   = true;
					$response['lang']        = $next_lang;
					$response['langs_array'] = $this->langs_array;
				}
			}

			wp_send_json_success( $response );
		} else {
			wp_send_json_success( [ 'step' => false ] );
		}
	}

	public function handle_with_greater_threshold( $step, $paged, $cmt_qty, $time_array, $from, $to ) {
		$get_products   = $this->get_products( $paged, 1 );
		$products       = $get_products['products'];
		$total_products = $get_products['count'];
		$response       = [
			'step'           => false,
			'paged'          => $paged,
			'time_array'     => [],
			'percent'        => 100,
			'message'        => '',
			'error_products' => [],
			'lang'           => $this->lang,
			'langs_array'    => $this->langs_array,
		];


		if ( ! empty( $products ) && is_array( $products ) ) {
			$product = $products[0];
			$pid     = $origin_pid = $product->ID;

			if ( function_exists( 'pll_get_post' ) && $this->lang !== 'default' ) {
				$pid = pll_get_post( $pid, $this->lang );
				if ( ! $pid ) {
					$this->end_of_product( $step, $paged, $cmt_qty, $from, $to, $total_products );
				}
			}

			$comment_list = $this->find_comment_list( $origin_pid );

			if ( ! $comment_list && ! $this->settings->get_param( 'add_empty_comment' ) ) {
				$this->set_error_product( $pid, $product->post_title, esc_html__( 'No comment matched', 'faview-virtual-reviews-for-woocommerce' ) );
				$this->end_of_product( $step, $paged, $cmt_qty, $from, $to, $total_products );
			}

			$this->comment_list = $comment_list;

			$this->time_array = empty( $time_array ) ? Utils::generate_time_array( $cmt_qty, $from, $to ) : $time_array;

			$this->add_reviews_for_single_product( $pid );

			$this->time_array = array_values( $this->time_array );

			if ( empty( $this->time_array ) ) {
				$this->end_of_product( $step, $paged, $cmt_qty, $from, $to, $total_products );
			} else {
				$percentage = ( $step * $this->threshold ) / ( $total_products * $cmt_qty );
				$percentage = $percentage >= 1 ? 100 : $percentage * 100;

				$response['step']           = $step + 1;
				$response['percent']        = floor( $percentage );
				$response['paged']          = $paged;
				$response['error_products'] = $this->error_products;
				$response['time_array']     = $this->time_array;

				wp_send_json_success( $response );
			}
		} else {
			$response['step']           = false;
			$response['percent']        = 100;
			$response['error_products'] = $this->error_products;

			if ( ! empty( $this->langs_array ) ) {
				$next_lang = array_key_first( $this->langs_array );
				unset( $this->langs_array[ $next_lang ] );
				$response['next_lang']   = true;
				$response['lang']        = $next_lang;
				$response['langs_array'] = $this->langs_array;
			}

			wp_send_json_success( $response );
		}
	}

	public function end_of_product( $step, $paged, $cmt_qty, $from, $to, $total_products ) {
		$paged ++;
		if ( $this->review_counter < $this->threshold ) {
			$this->handle_with_greater_threshold( $step, $paged, $cmt_qty, [], $from, $to );
		} else {
			$percentage = ( $step * $this->threshold ) / ( $total_products * $cmt_qty );
			$percentage = $percentage >= 1 ? 100 : $percentage * 100;
			$step       = $percentage >= 1 ? false : $step + 1;
			$paged      = $percentage >= 1 ? false : $paged;

			$res = [
				'step'           => $step,
				'paged'          => $paged,
				'time_array'     => [],
				'percent'        => floor( $percentage ),
				'error_products' => $this->error_products
			];

			if ( ! empty( $this->langs_array ) ) {
				$next_lang = array_key_first( $this->langs_array );
				unset( $this->langs_array[ $next_lang ] );
				$res['next_lang']   = true;
				$res['lang']        = $next_lang;
				$res['langs_array'] = $this->langs_array;
			}
			wp_send_json_success( $res );
		}
	}

	public function find_comment_list( $pid ) {
		$rules = $this->settings->get_param( 'review_rules' );

		if ( empty( $rules ) || ! is_array( $rules ) ) {
			return false;
		}

		$product = wc_get_product( $pid );
		if ( ! $product ) {
			return false;
		}

		$cat_ids = $product->get_category_ids();


		foreach ( $rules as $rule ) {
			$r = [];

			if ( ! empty( $rule['products'] ) ) {
				$r[] = in_array( $pid, $rule['products'] );
			}

			if ( ! empty( $rule['exclude_products'] ) ) {
				$r[] = ! in_array( $pid, $rule['exclude_products'] );
			}

			if ( ! empty( $rule['categories'] ) ) {
				$r[] = boolval( array_intersect( $cat_ids, $rule['categories'] ) );
			}

			if ( ! empty( $rule['exclude_categories'] ) ) {
				$r[] = ! boolval( array_intersect( $cat_ids, $rule['exclude_categories'] ) );
			}

			if ( count( $r ) === array_sum( $r ) ) {
				return $rule['comments'][ $this->lang ] ?? false;
			}
		}

		return false;
	}

	public function add_reviews_for_single_product( $pid ) {
		$loop = ! $this->review_counter ? $this->threshold : $this->threshold - $this->review_counter;

		for ( $i = 0; $i < $loop; $i ++ ) {
			if ( ! isset( $this->time_array[ $i ] ) ) {
				break;
			}

			$time = $this->time_array[ $i ];
			$this->add_single_review( $pid, $time );

			unset( $this->time_array[ $i ] );

			$this->review_counter ++;
		}

		\WC_Comments::clear_transients( $pid );
	}

	public function add_single_review( $pid, $time, $rating = '', $cmt = '', $author = '' ) {
		$product = wc_get_product( $pid );

		if ( ! $product ) {
			return;
		}

		if ( empty( $author ) ) {
			$unique_name = $this->settings->get_param( 'unique_name' );
			$names       = (array) $this->settings->get_param( 'names' );
			$names       = $names[ $this->lang ] ?? [];

			if ( empty( $names ) ) {
				$this->set_error_product( $pid, $product->get_name(), esc_html__( 'Author name is not exist', 'faview-virtual-reviews-for-woocommerce' ) );

				return;
			}

			if ( $unique_name ) {
				$cmts = get_comments( array(
					'post_id'    => $pid,
					'type'       => 'review',
					'meta_key'   => 'wvr_virtual_review',
					'meta_value' => 1,
					'number'     => count( $names ),
					'orderby'    => 'comment_ID',
					'order'      => 'DESC'
				) );

				$cmt_authors = wp_list_pluck( $cmts, 'comment_author' );
				$diff        = array_diff( $names, $cmt_authors );

				if ( empty( $diff ) ) {
					$diff = $names;
				}

				$author = $names[ array_rand( $diff, 1 ) ];
			} else {
				$author = $names[ array_rand( $names, 1 ) ];
			}
		}

		if ( empty( $rating ) ) {
			$rating_weights = $this->settings->get_param( 'rating_rate' );
			$rating         = $this->random_rating( $rating_weights );
		}

		if ( empty( $cmt ) ) {
			$comments = $this->comment_list[ $rating ] ?? [];
			$cmt      = '';

			if ( ! empty( $comments ) ) {
				$unique_cmt = $this->settings->get_param( 'unique_cmt' );
				if ( $unique_cmt ) {
					$cmts = get_comments( array(
						'post_id'    => $pid,
						'type'       => 'review',
						'meta_key'   => 'wvr_virtual_review',
						'meta_value' => 1,
						'number'     => count( $comments ),
						'orderby'    => 'comment_ID',
						'order'      => 'DESC'
					) );

					$cmt_contents = wp_list_pluck( $cmts, 'comment_content' );
					$diff         = array_diff( $comments, $cmt_contents );

					if ( empty( $diff ) ) {
						$diff = $comments;
					}

					$cmt = $comments[ array_rand( $diff, 1 ) ];
				} else {
					$cmt = $comments[ array_rand( $comments, 1 ) ];
				}
			}
		}

		if ( empty( $cmt ) && ! $this->settings->get_param( 'add_empty_comment' ) ) {
			$this->set_error_product( $pid, $product->get_name(), esc_html__( 'No comment matched', 'faview-virtual-reviews-for-woocommerce' ) );

			return;
		}

		$time = date( 'Y-m-d H:i:s', $time );

		$gmt_time = get_gmt_from_date( $time );

		$data = [
			'comment_post_ID'      => $pid,
			'comment_author'       => $author,
			'comment_author_email' => 'Virtual review',
			'comment_author_url'   => '',
			'comment_content'      => $cmt,
			'comment_type'         => 'review',
			'comment_parent'       => 0,
			'user_id'              => 0,
			'comment_agent'        => 'admin',
			'comment_date'         => $time,
			'comment_date_gmt'     => $gmt_time,
			'comment_approved'     => 1,
			'comment_meta'         => [
				'rating'             => $rating,
				'wvr_virtual_review' => 1
			]
		];

		if ( $this->settings->get_param( 'verified_owner' ) ) {
			$data['comment_meta']['verified'] = 1;
		}

		$comment_id = wp_insert_comment( $data );

		if ( ! $comment_id ) {
			$this->set_error_product( $pid, $product->get_name(), esc_html__( 'Can not insert comment', 'faview-virtual-reviews-for-woocommerce' ) );

			return;
		}

		if ( $this->settings->get_param( 'enable_reply_virtual_review' ) ) {
			$replies         = $this->settings->get_param( 'reply_content' );
			$reply_author_id = $this->settings->get_param( 'reply_author' );
			$reply_author    = get_user_by( 'id', $reply_author_id );
			if ( ! empty( $replies[ $this->lang ][ $rating ] ) && $reply_author ) {
				$cmts = $replies[ $this->lang ][ $rating ];
				$key  = array_rand( $cmts, 1 );
				$cmt  = $cmts[ $key ];

				$reply_data   = [
					'comment_post_ID'      => $pid,
					'comment_author'       => $reply_author->display_name,
					'comment_author_email' => $reply_author->user_email,
					'comment_author_url'   => $reply_author->user_url,
					'comment_content'      => $cmt,
					'comment_type'         => 'comment',
					'comment_parent'       => $comment_id,
					'user_ID'              => $reply_author_id,
					'user_id'              => $reply_author_id,
					'comment_approved'     => 1,
					'comment_date'         => $time,
					'comment_date_gmt'     => $gmt_time,
				];
				$reply_result = wp_insert_comment( $reply_data );
			}
		}

		$bought_quantity = (array) $this->settings->get_param( 'bought_quantity' );
		$from            = intval( $bought_quantity['from'] );
		$to              = intval( $bought_quantity['to'] );
		$qty             = rand( $from, $to );
		$qty             = apply_filters( 'faview_quantity_of_bought_product', $qty, $product );

		if ( ! $qty ) {
			return;
		}

		if ( $product->is_type( 'variable' ) ) {
			$variations        = $product->get_children();
			$parent_attributes = $product->get_attributes();

			if ( $variations ) {
				$bought = [];

				for ( $i = 0; $i < $qty; $i ++ ) {
					$bought[] = $variations[ array_rand( $variations, 1 ) ];
				}

				$bought = array_count_values( $bought );
				$result = [];

				foreach ( $bought as $variation_id => $quantity ) {
					$variation       = wc_get_product( $variation_id );
					$variation_attrs = $variation->get_attributes();
					$attributes      = [];

					if ( empty( $variation_attrs ) ) {
						continue;
					}

					foreach ( $variation_attrs as $attr_key => $attr_value ) {
						if ( ! $attr_value ) {
							$attr_object = $parent_attributes[ $attr_key ];
							$options     = $attr_object->get_options();
							$attr_id     = $attr_object->get_id();
							$key         = array_rand( $options );

							if ( $attr_id ) {
								$term_id    = $options[ $key ];
								$term       = get_term( $term_id );
								$attr_value = $term->slug;
							} else {
								$attr_value = $options[ $key ];
							}
						}

						$attributes[ 'attribute_' . sanitize_title( $attr_key ) ] = $attr_value;
					}

					$result[ $variation_id ] = [
						'attributes' => $attributes,
						'quantity'   => apply_filters( 'faview_quantity_of_bought_product_variation', $quantity ),
					];
				}

				if ( ! empty( $result ) ) {
					update_comment_meta( $comment_id, 'wvr_variation', $result );
				}

			} else {
				wp_delete_comment( $comment_id );
			}

		} elseif ( $product->is_type( 'simple' ) ) {
			update_comment_meta( $comment_id, 'wvr_bought_quantity', $qty );
		}

	}

	public function add_custom_reviews() {
		$pids   = isset( $_POST['pids'] ) ? wc_clean( $_POST['pids'] ) : '';
		$cmt    = isset( $_POST['cmt'] ) ? wp_kses_post( $_POST['cmt'] ) : '';
		$author = isset( $_POST['author'] ) ? sanitize_text_field( $_POST['author'] ) : '';
		$rating = isset( $_POST['rating'] ) ? sanitize_text_field( $_POST['rating'] ) : 5;
		$time   = isset( $_POST['time'] ) ? strtotime( sanitize_text_field( $_POST['time'] ) ) : current_time( 'U' );

		if ( ! ( $pids && $cmt && $author ) ) {
			wp_send_json_error();
		}

		foreach ( (array) $pids as $pid ) {
			$this->add_single_review( $pid, $time, $rating, $cmt, $author );
			\WC_Comments::clear_transients( $pid );
		}

		wp_send_json_success();
	}

	public function set_comment_list( $data ) {
		$this->comment_list = $data;
	}

//	Add review from Woo product list page

	public function add_review_from_product_page() {

		if ( empty( $_POST['pids'] ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Please select at least one product', 'faview-virtual-reviews-for-woocommerce' ) ] );
		}

		if ( empty( $_POST['quantity'] ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Please input quantity of reviews per each product', 'faview-virtual-reviews-for-woocommerce' ) ] );
		}

		$cmt_qty = sanitize_text_field( $_POST['quantity'] );
		$pids    = array_map( 'absint', $_POST['pids'] );
		$step    = ! empty( $_POST['step'] ) ? absint( $_POST['step'] ) : 1;
		$paged   = ! empty( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 0;
		$from    = ! empty( $_POST['from'] ) ? sanitize_text_field( $_POST['from'] ) : 'today';
		$to      = ! empty( $_POST['to'] ) ? sanitize_text_field( $_POST['to'] ) : 'today';

		$from = strtotime( $from );
		$to   = strtotime( $to ) + DAY_IN_SECONDS - 1;

		if ( $cmt_qty > $this->threshold ) {
			$time_array = ! empty( $_POST['time_array'] ) ? wc_clean( $_POST['time_array'] ) : [];
			$this->handle_with_greater_threshold2( $pids, $step, $paged, $cmt_qty, $time_array, $from, $to );
		} else {
			$product_limit = floor( $this->threshold / $cmt_qty );
			$this->handle_with_smaller_threshold2( $pids, $product_limit, $step, $cmt_qty, $from, $to );
		}
	}

	public function handle_with_greater_threshold2( $pids, $step, $paged, $cmt_qty, $time_array, $from, $to ) {
		if ( ! empty( $pids[ $paged ] ) ) {
			$total_products = count( (array) $pids );
			$pid            = $pids[ $paged ];
			$origin_pid     = '';

			if ( function_exists( 'pll_get_post_language' ) ) {
				$default_lang = icl_get_default_language();
				$lang         = pll_get_post_language( $pid );
				if ( ! $lang ) {
					$lang = 'default';
				}

				if ( $lang !== $default_lang ) {
					$this->lang = $lang;
					$origin_pid = pll_get_post( $pid, $default_lang );
				}
			}

			$comment_list = $this->find_comment_list( $origin_pid ? $origin_pid : $pid );


			$product = wc_get_product( $pid );

			if ( ! $comment_list && ! $this->settings->get_param( 'add_empty_comment' ) ) {
				$this->set_error_product( $pid, $product->get_name(), esc_html__( 'No comment matched', 'faview-virtual-reviews-for-woocommerce' ) );
				$this->end_of_product2( $pids, $step, $paged, $cmt_qty, $from, $to, $total_products );
			}

			$this->comment_list = $comment_list;

			$this->time_array = empty( $time_array ) ? Utils::generate_time_array( $cmt_qty, $from, $to ) : $time_array;

			$this->add_reviews_for_single_product( $pid );

			$this->time_array = array_values( $this->time_array );

			if ( empty( $this->time_array ) ) {
				$this->end_of_product2( $pids, $step, $paged, $cmt_qty, $from, $to, $total_products );
			} else {
				$percentage = ( $step * $this->threshold ) / ( $total_products * $cmt_qty );
				$percentage = $percentage >= 1 ? 100 : $percentage * 100;

				$response['step']           = $step + 1;
				$response['paged']          = $paged;
				$response['percent']        = floor( $percentage );
				$response['error_products'] = $this->error_products;
				$response['time_array']     = $this->time_array;

				wp_send_json_success( $response );
			}
		} else {
			$response['step']           = false;
			$response['percent']        = 100;
			$response['error_products'] = $this->error_products;

			wp_send_json_success( $response );
		}
	}

	public function handle_with_smaller_threshold2( $pids, $product_limit, $step, $cmt_qty, $from, $to ) {
		$chunked_pids = array_chunk( $pids, $product_limit );
		if ( ! empty( $chunked_pids[ $step - 1 ] ) ) {
			$products       = $chunked_pids[ $step - 1 ];
			$total_products = count( $pids );
			$response       = [
				'error_products' => [],
				'step'           => false,
				'percent'        => 100
			];

			foreach ( $products as $pid ) {
				$origin_pid = '';

				if ( function_exists( 'pll_get_post_language' ) ) {
					$default_lang = icl_get_default_language();
					$lang         = pll_get_post_language( $pid );
					if ( ! $lang ) {
						$lang = 'default';
					}

					if ( $lang !== $default_lang ) {
						$this->lang = $lang;
						$origin_pid = pll_get_post( $pid, $default_lang );
					}
				}

				$comment_list = $this->find_comment_list( $origin_pid ? $origin_pid : $pid );

				if ( ! $comment_list && ! $this->settings->get_param( 'add_empty_comment' ) ) {
					$product = wc_get_product( $pid );
					$this->set_error_product( $pid, $product->get_name(), esc_html__( 'No comment matched', 'faview-virtual-reviews-for-woocommerce' ) );
					continue;
				}

				$this->comment_list = $comment_list;

				$time_array = Utils::generate_time_array( $cmt_qty, $from, $to );

				for ( $i = 0; $i < $cmt_qty; $i ++ ) {
					$time = $time_array[ $i ];
					$this->add_single_review( $pid, $time );
				}

				\WC_Comments::clear_transients( $pid );
			}

			$response['error_products'] = $this->error_products;
			$next_step                  = $step + 1;

			if ( $next_step * $product_limit <= $total_products ) {
				$response['step']    = $next_step;
				$response['percent'] = floor( ( $step * $product_limit / $total_products ) * 100 );
			}

			wp_send_json_success( $response );
		} else {
			wp_send_json_success( [ 'step' => false ] );
		}
	}

	public function end_of_product2( $pids, $step, $paged, $cmt_qty, $from, $to, $total_products ) {
		$paged ++;
		if ( $this->review_counter < $this->threshold ) {
			$this->handle_with_greater_threshold2( $pids, $step, $paged, $cmt_qty, [], $from, $to );
		} else {
			$percentage = ( $step * $this->threshold ) / ( $total_products * $cmt_qty );
			$percentage = $percentage >= 1 ? 100 : $percentage * 100;
			$step       = $percentage >= 1 ? false : $step + 1;
			$paged      = $percentage >= 1 ? false : $paged;

			$res = [
				'step'           => $step,
				'paged'          => $paged,
				'time_array'     => [],
				'percent'        => floor( $percentage ),
				'error_products' => $this->error_products
			];

			wp_send_json_success( $res );
		}
	}

	public function bulk_add_reply( $redirect_to, $doaction, $comment_ids ) {
		$redirect_to = remove_query_arg( [ 'wvr_bulk_reply' ], $redirect_to );

		if ( $doaction !== 'wvr_add_reply' || empty( $comment_ids ) || ! is_array( $comment_ids ) ) {
			return $redirect_to;
		}

		$reply_author_id = $this->settings->get_param( 'reply_author' );
		$reply_author    = get_user_by( 'id', $reply_author_id );

		if ( empty( $reply_author ) ) {
			$redirect_to = add_query_arg( 'wvr_bulk_reply', 'no_author', $redirect_to );

			return $redirect_to;
		}

		$lang     = 'default';
		$replies  = $this->settings->get_param( 'reply_content' );
		$time     = date( 'Y-m-d H:i:s', current_time( 'U' ) );
		$gmt_time = get_gmt_from_date( $time );
		$count    = 0;

		foreach ( $comment_ids as $cmt_id ) {
			$cmt = get_comment( $cmt_id );
			if ( empty( $cmt ) || ! empty( $cmt->get_children() ) ) {
				continue;
			}

			$rating = get_comment_meta( $cmt_id, 'rating', true );
			if ( ! $rating ) {
				continue;
			}
			$reply_contents = $replies[ $lang ][ $rating ];

			if ( empty( $reply_contents ) ) {
				continue;
			}

			$key           = array_rand( $reply_contents, 1 );
			$reply_content = $reply_contents[ $key ];

			$reply_data = [
				'comment_post_ID'      => $cmt->comment_post_ID,
				'comment_author'       => $reply_author->display_name,
				'comment_author_email' => $reply_author->user_email,
				'comment_author_url'   => $reply_author->user_url,
				'comment_content'      => $reply_content,
				'comment_type'         => 'comment',
				'comment_parent'       => $cmt_id,
				'user_ID'              => $reply_author_id,
				'comment_approved'     => 1,
				'comment_date'         => $time,
				'comment_date_gmt'     => $gmt_time,
			];

			wp_new_comment( $reply_data );
			$count ++;
		}

		$redirect_to = add_query_arg( 'wvr_reply_added', $count, $redirect_to );

		return $redirect_to;
	}
}

