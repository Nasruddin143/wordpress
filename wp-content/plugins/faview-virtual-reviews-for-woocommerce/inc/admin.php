<?php

namespace VirtualReviews\Inc;

defined( 'ABSPATH' ) || exit;

class Admin {
	protected static $instance = null;

	public static function instance() {
		return self::$instance == null ? self::$instance = new self : self::$instance;
	}

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		//Admin comment page
		add_filter( 'admin_comment_types_dropdown', array( $this, 'wvr_admin_comment_types_dropdown' ) );
		add_filter( 'comments_list_table_query_args', [ $this, 'filter_virtual_review' ] );
		add_filter( 'parse_comment_query', [ $this, 'parse_comment_query' ] );
		add_filter( 'bulk_actions-edit-comments', [ $this, 'add_action_to_action_list' ] );
		add_action( 'admin_notices', [ $this, 'bulk_action_notice' ] );
		add_filter( 'removable_query_args', [ $this, 'removable_query_args' ] );

//		Admin product page
		add_action( 'manage_posts_custom_column', array( $this, 'show_virtual_review_count' ), 10, 2 );
		add_filter( 'manage_edit-product_columns', array( $this, 'add_columns' ), 20 );
		add_filter( 'manage_edit-product_sortable_columns', array( $this, 'add_sortable_column' ) );
		add_action( 'pre_get_posts', array( $this, 'sort_function' ) );
		add_filter( 'bulk_actions-edit-product', array( $this, 'register_delete_virtual_reviews' ) );
		add_filter( 'handle_bulk_actions-edit-product', array( $this, 'delete_virtual_reviews' ), 10, 3 );
		add_action( 'manage_posts_extra_tablenav', array( $this, 'html_add_review_section_on_nav' ) );
		add_filter( 'woocommerce_products_admin_list_table_filters', array( $this, 'filter_no_review_product' ) );
		add_filter( 'request', array( $this, 'request_query' ) );

		/* Admin reviews page*/
		add_filter( 'woocommerce_product_reviews_list_table_item_types', [ $this, 'add_review_filter_type' ] );
		add_filter( 'woocommerce_product_reviews_list_table_prepare_items_args', [ $this, 'convert_virtual_to_reviews' ] );

	}

	public function admin_menu() {
		global $wvr_pages;

		$wvr_pages['add_review_manual'] = add_menu_page(
			esc_html__( 'Faview', 'faview-virtual-reviews-for-woocommerce' ),
			esc_html__( 'Faview', 'faview-virtual-reviews-for-woocommerce' ),
			apply_filters( 'vi_menu_page_capability', 'manage_woocommerce' ),
			'virtual-reviews',
			[ $this, 'add_reviews_page' ],
			'dashicons-star-filled',
			10
		);

		$wvr_pages['add_review_manual'] = add_submenu_page(
			'virtual-reviews',
			esc_html__( 'Manual', 'faview-virtual-reviews-for-woocommerce' ),
			esc_html__( 'Manual', 'faview-virtual-reviews-for-woocommerce' ),
			apply_filters( 'vi_menu_page_capability', 'manage_woocommerce' ),
			'virtual-reviews',
			[ $this, 'add_reviews_page' ]
		);

		$wvr_pages['add_review_schedule'] = add_submenu_page(
			'virtual-reviews',
			esc_html__( 'Schedule', 'faview-virtual-reviews-for-woocommerce' ),
			esc_html__( 'Schedule', 'faview-virtual-reviews-for-woocommerce' ),
			apply_filters( 'vi_menu_page_capability', 'manage_woocommerce' ),
			'virtual-reviews-schedule',
			[ $this, 'schedule_page' ]
		);

		$wvr_pages['settings'] = add_submenu_page(
			'virtual-reviews',
			esc_html__( 'Settings', 'faview-virtual-reviews-for-woocommerce' ),
			esc_html__( 'Settings', 'faview-virtual-reviews-for-woocommerce' ),
			apply_filters( 'vi_menu_page_capability', 'manage_woocommerce' ),
			'wvr-settings',
			[ $this, 'page_settings_content' ]
		);
	}

	public function add_reviews_page() {
		$timestamp          = current_time( 'U' );
		$use_quantity_range = get_option( 'wvr_use_quantity_range' );

		extract( [
			'current_time'       => date( 'Y-m-d', $timestamp ),
			'categories'         => Utils::get_product_categories( [ 'hide_empty' => true ] ),
			'use_quantity_range' => $use_quantity_range,
		] );

		include_once plugin_dir_path( __FILE__ ) . 'views/html-add-reviews-manual.php';
	}

	public function schedule_page() {
		Schedules::instance()->schedules_page();
	}

	public function page_settings_content() {
		Settings::instance()->settings_page();
	}

	public function filter_virtual_review( $args ) {
		if ( ! empty( $args['type'] ) ) {
			switch ( $args['type'] ) {
				case 'virtual_review':
					$args['type']       = 'review';
					$args['meta_key']   = 'wvr_virtual_review';
					$args['meta_value'] = 1;
					break;

				case 'review':
					$args['meta_query'] = [
						[
							'key'     => 'wvr_virtual_review',
							'compare' => 'NOT EXISTS'
						]
					];
					break;
			}
		}

		return $args;
	}

	public function wvr_admin_comment_types_dropdown( $types ) {
		$types['virtual_review'] = esc_html__( 'Virtual Review', 'faview-virtual-reviews-for-woocommerce' );

		return $types;
	}

	public function parse_comment_query( $comment_data ) {
		$query_vars = $comment_data->query_vars;
		if ( ! empty( $query_vars['type'] ) && $query_vars['type'] === 'virtual_review' ) {
			$comment_data->query_vars['type'] = 'review';
		}
	}

	public function show_virtual_review_count( $column_name, $pid ) {
		if ( $column_name == 'virtual_review' ) {
			$cmts = get_comments( array(
				'post_id'    => $pid,
				'type'       => 'review',
				'meta_key'   => 'wvr_virtual_review',
				'meta_value' => 1,
				'count'      => true
			) );
			echo esc_html( $cmts );
		} elseif ( $column_name == 'wvr_rating' ) {
			echo esc_html( get_post_meta( $pid, '_wc_average_rating', true ) );
		}
	}

	public function add_columns( $columns ) {
		$new_columns['virtual_review'] = esc_html__( 'Virtual review', 'faview-virtual-reviews-for-woocommerce' );
		$new_columns['wvr_rating']     = esc_html__( 'Rating', 'faview-virtual-reviews-for-woocommerce' );

		return $columns = array_merge( $columns, $new_columns );
	}

	public function add_sortable_column( $columns ) {
		$columns['wvr_rating'] = 'wvr_rating';

		return $columns;
	}

	public function sort_function( \WP_Query $query ) {
		if ( ! is_admin() ) {
			return;
		}
		$orderby = $query->get( 'orderby' );

		if ( 'wvr_rating' == $orderby ) {
			$query->set( 'meta_key', '_wc_average_rating' );
			$query->set( 'orderby', 'meta_value_num' );
		} elseif ( 'virtual_review' == $orderby ) {
			$query->set( 'orderby', 'meta_value_num' );
		}
	}

	public function register_delete_virtual_reviews( $bulk_actions ) {
		$bulk_actions['delete_virtual_reviews'] = esc_html__( 'Delete Virtual Reviews', 'faview-virtual-reviews-for-woocommerce' );

		return $bulk_actions;
	}

	public function delete_virtual_reviews( $redirect_to, $action_name, $post_ids ) {
		if ( 'delete_virtual_reviews' !== $action_name ) {
			return $redirect_to;
		}

		foreach ( $post_ids as $post_id ) {
			$arg  = array(
				'post_id'    => $post_id,
				'type'       => 'review',
				'meta_key'   => 'wvr_virtual_review',
				'meta_value' => 1
			);
			$cmts = get_comments( $arg );
			if ( ! empty( $cmts ) ) {
				foreach ( $cmts as $cmt ) {
					wp_delete_comment( $cmt->comment_ID, true );
				}
			}
		}

		return $redirect_to;
	}

	public function html_add_review_section_on_nav( $which ) {
		global $post_type;
		if ( $post_type !== 'product' || $which !== 'top' ) {
			return;
		}

		$current_date = date( "Y-m-d", current_time( 'U' ) );
		?>
        <div class='alignleft wvr-control-panel'>
            <input type="button" class="wvr-open-add-review-control-panel button" value="<?php esc_html_e( "Add virtual reviews", "woo-virtual-reviews" ); ?>">
            <button type="button" class="button secondary wvr-products-error-button"><i class="dashicons dashicons-info"> </i></button>

            <div class="wvr-products-error-section">
                <h3 class="wvr-products-error-label"><?php esc_html_e( 'Error', 'faview-virtual-reviews-for-woocommerce' ); ?></h3>
                <ul class="wvr-products-error-list">
                </ul>
            </div>

            <div class="wvr-add-review-control">
                <div class="wvr-row">
                    <div class="wvr-row-label">
						<?php esc_html_e( 'Quantity of review per product', 'faview-virtual-reviews-for-woocommerce' ); ?>
                    </div>
                    <input type="number" class='wvr-select-qty-cmt' value="1" min="1"/>
                </div>
                <div class="wvr-row">
                    <div class="wvr-row-label">
						<?php esc_html_e( 'From', 'faview-virtual-reviews-for-woocommerce' ); ?>
                    </div>
                    <input type="date" class="wvr-date-from" value="<?php echo esc_attr( $current_date ) ?>">
                </div>
                <div class="wvr-row">
                    <div class="wvr-row-label">
						<?php esc_html_e( 'To', 'faview-virtual-reviews-for-woocommerce' ); ?>
                    </div>
                    <input type="date" class="wvr-date-to" value="<?php echo esc_attr( $current_date ) ?>">
                </div>
                <button type="button" class='vi-ui button button-primary submit-add-reviews' id='add_multi_reviews'>
					<?php esc_html_e( "Add reviews", "woo-virtual-reviews" ); ?>
                </button>
            </div>
        </div>
		<?php
	}

	public function add_action_to_action_list( $actions ) {
		$actions['wvr_add_reply'] = esc_html__( 'Add reply (from Virtual reviews settings)', 'faview-virtual-reviews-for-woocommerce' );

		return $actions;
	}

	public function bulk_action_notice() {
		$screen = get_current_screen()->id;
		if ( $screen !== 'edit-comments' ) {
			return;
		}

		if ( isset( $_REQUEST['wvr_bulk_reply'] ) && $_REQUEST['wvr_bulk_reply'] == 'no_author' ) {
			printf( '<div class="error"><p>%s <a href="%s">%s</a></p></div>',
				esc_html__( 'No reply author was selected. Please select author before add reply.', 'faview-virtual-reviews-for-woocommerce' ),
				admin_url( 'admin.php?page=wvr-settings#/reply' ),
				esc_html__( 'Go to setting', 'faview-virtual-reviews-for-woocommerce' ) );
		}

		if ( ! empty( $_REQUEST['wvr_reply_added'] ) ) {
			$added = absint( $_REQUEST['wvr_reply_added'] );
			printf( '<div class="updated"><p>%s %s %s </p></div>',
				$added, _n( 'reply', 'replies', $added, 'faview-virtual-reviews-for-woocommerce' ),
				esc_html__( 'added', 'faview-virtual-reviews-for-woocommerce' ) );
		}
	}

	public function removable_query_args( $removable_query_args ) {
		$removable_query_args[] = 'wvr_bulk_reply';
		$removable_query_args[] = 'wvr_reply_added';

		return $removable_query_args;
	}

	public function filter_no_review_product( $filters ) {
		$filters['wvr_filter_no_review'] = [ $this, 'render_filter_dropdown' ];

		return $filters;
	}

	public function render_filter_dropdown() {
		$operator     = isset( $_REQUEST['wvr_filter_operator'] ) ? wc_clean( wp_unslash( $_REQUEST['wvr_filter_operator'] ) ) : ''; // WPCS: input var ok, sanitization ok.
		$filter_value = isset( $_REQUEST['wvr_filter_review_count_value'] ) ? wc_clean( wp_unslash( $_REQUEST['wvr_filter_review_count_value'] ) ) : ''; // WPCS: input var ok, sanitization ok.

		$operators = [
			''              => esc_html__( 'Filter by review count', 'faview-virtual-reviews-for-woocommerce' ),
			'less'          => 'Less than',
			'less_equal'    => 'Less than or equal',
			'equal'         => 'Equal',
			'greater'       => 'Greater than',
			'greater_equal' => 'Greater than or equal',
		];
		?>
        <span>
            <select name="wvr_filter_operator" id="">
				<?php
				foreach ( $operators as $op_value => $op_text ) {
					printf( '<option value="%s" %s>%s</option>',
						esc_attr( $op_value ), esc_attr( selected( $operator, $op_value, false ) ), esc_html( $op_text ) );
				}
				?>
            </select>
            <input type="number" name="wvr_filter_review_count_value" min="0" style="width: 120px;"
                   value="<?php echo esc_attr( $filter_value ) ?>"
                   placeholder="<?php esc_attr_e( 'Review count', 'faview-virtual-reviews-for-woocommerce' ); ?>"/>
        </span>
		<?php
	}

	public function request_query( $query_vars ) {
		if ( ! empty( $_GET['wvr_filter_operator'] ) && isset( $_REQUEST['wvr_filter_review_count_value'] ) && $_REQUEST['wvr_filter_review_count_value'] !== '' ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$filter_value = absint( wp_unslash( $_REQUEST['wvr_filter_review_count_value'] ) );
			$operator     = wc_clean( wp_unslash( $_GET['wvr_filter_operator'] ) );
			$compare      = '=';

			switch ( $operator ) {
				case 'less':
					$compare = '<';
					break;
				case 'less_equal':
					$compare = '<=';
					break;
				case 'equal':
					$compare = '=';
					break;
				case 'greater':
					$compare = '>';
					break;
				case 'greater_equal':
					$compare = '>=';
					break;
			}

			$query_vars['meta_query'][] = array(
				'key'     => '_wc_review_count',
				'value'   => $filter_value,
				'compare' => $compare,
			);
		}

		return $query_vars;
	}

	public function add_review_filter_type( $type ) {
		$type['virtual_review'] = esc_html__( 'Virtual reviews' );

		return $type;
	}

	public function convert_virtual_to_reviews( $args ) {
		if ( ! empty( $args['type'] ) && $args['type'] == 'virtual_review' ) {
			$args['type']       = 'review';
			$args['meta_key']   = 'wvr_virtual_review';
			$args['meta_value'] = 1;
		}

		return $args;
	}
}
