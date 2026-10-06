<?php

namespace VirtualReviews\Inc;

use VirtualReviews\Inc\Background_Process\Add_Virtual_Reviews;

defined( 'ABSPATH' ) || exit;

class Schedules {
	protected static $instance = null;

	public static function instance() {
		return self::$instance == null ? self::$instance = new self : self::$instance;
	}

	public function __construct() {
		add_action( 'init', [ $this, 'init_background_processing' ] );
		add_action( 'admin_init', [ $this, 'save_schedules' ] );

		if ( ! wp_next_scheduled( 'faview_set_schedules_for_a_day' ) ) {
			$time = (int) get_option( 'gmt_offset' );
			wp_schedule_event( strtotime( "tomorrow -{$time}hour" ), 'daily', 'faview_set_schedules_for_a_day' );
		}

		add_action( 'faview_set_schedules_for_a_day', [ $this, 'set_schedules_for_a_day' ] );
		add_action( 'faview_add_review_via_schedule', [ $this, 'add_review_via_schedule' ] );

	}

	public function schedules_page() {
		?>
        <h1>
			<?php esc_html_e( 'Set schedules', 'faview-virtual-reviews-for-woocommerce' ); ?>
        </h1>

        <p><?php esc_html_e( 'Schedule after setting will be work at next day', 'faview-virtual-reviews-for-woocommerce' ); ?></p>

        <form method="post" class="vi-ui form small">
			<?php wp_nonce_field( 'wvr-schedules' ); ?>
            <div id="wvr-schedules-section"></div>
            <button type="submit" class="vi-ui button small labeled icon primary wvr-save-rules" name="wvr_save_rules" value="1">
                <i class="save icon"> </i>
				<?php esc_html_e( 'Save rules', 'faview-virtual-reviews-for-woocommerce' ); ?>
            </button>
            <div class="vi-ui button small labeled icon wvr-add-schedule-rule">
                <i class="icon plus"> </i>
				<?php esc_html_e( 'Add rule', 'faview-virtual-reviews-for-woocommerce' ); ?>
            </div>
        </form>

        <h1>
			<?php esc_html_e( 'Running schedules', 'faview-virtual-reviews-for-woocommerce' ); ?>
        </h1>
        <div class="vi-ui segments wvr-running-schedules">
			<?php
			$check_exist = false;
			$crons       = _get_cron_array();

			if ( ! empty( $crons ) && is_array( $crons ) ) {
				$hook = 'faview_add_review_via_schedule';

				foreach ( $crons as $timestamp => $cron ) {

					if ( ! isset( $cron[ $hook ] ) ) {
						continue;
					}

					$timestamp += (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );

					foreach ( $cron[ $hook ] as $key => $data ) {
						if ( empty( $data['args'][0] ) ) {
							continue;
						}
						$args        = $data['args'][0];
						$check_exist = true;
						?>
                        <div class="vi-ui segment wvr-schedule-row">
                            <div class="wvr-schedule-row-name">
								<?php
								printf( "<strong>%s</strong><small> %s %s</small>",
									esc_html( $args['rule_name'] ),
									esc_html__( 'at', 'faview-virtual-reviews-for-woocommerce' ),
									date( wc_date_format() . ' ' . wc_time_format(), $timestamp ) )
								?>
                            </div>
                            <div>
                                <i class="vi-ui x icon wvr-remove-schedule"
                                   data-timestamp="<?php echo esc_attr( $timestamp ) ?>"
                                   data-key="<?php echo esc_attr( $key ) ?>"
                                   title="<?php esc_html_e( 'Remove', 'faview-virtual-reviews-for-woocommerce' ); ?>"> </i>
                            </div>
                        </div>
						<?php
					}

				}
			}

			if ( ! $check_exist ) {
				?>
                <div class="vi-ui segment">
					<?php esc_html_e( 'No schedule now', 'faview-virtual-reviews-for-woocommerce' ); ?>
                </div>
				<?php
			}
			?>
        </div>

        <div class="vi-ui button labeled icon red small wvr-remove-all-schedule">
            <i class="trash icon"> </i>
			<?php esc_html_e( 'Remove all schedules', 'faview-virtual-reviews-for-woocommerce' ); ?>
        </div>

		<?php
	}

	public function save_schedules() {
		if ( isset( $_POST['wvr_save_rules'], $_POST['_wpnonce'] ) && $_POST['wvr_save_rules']
		     && wp_verify_nonce( $_POST['_wpnonce'], 'wvr-schedules' ) && current_user_can( 'manage_options' ) ) {

			$data = ! empty( $_POST['wvr_schedule_rules'] ) ? wc_clean( $_POST['wvr_schedule_rules'] ) : [];
			$data = array_values( $data );
			update_option( 'wvr_schedules_setting', $data );
		}
	}

	public function get_product_ids( $schedule ) {
		$args = [
			'post_type'      => 'product',
			'posts_per_page' => - 1,
			'fields'         => 'ids',
		];

		if ( ! empty( $schedule['products'] ) ) {
			$args['post__in'] = $schedule['products'];
		}

		if ( ! empty( $schedule['exclude_products'] ) ) {
			$args['post__not_in'] = $schedule['exclude_products'];
		}

		if ( ! empty( $schedule['categories'] ) ) {
			$args['tax_query'][] = [
				'taxonomy'         => 'product_cat',
				'terms'            => $schedule['categories'],
				'include_children' => false,
				'operator'         => 'IN'
			];
		}

		if ( ! empty( $schedule['exclude_categories'] ) ) {
			$args['tax_query'][] = [
				'taxonomy'         => 'product_cat',
				'terms'            => $schedule['exclude_categories'],
				'include_children' => false,
				'operator'         => 'NOT IN'
			];
		}

		if ( ! empty( $args['tax_query'] ) ) {
			$args['tax_query']['relation'] = 'AND';
		}

		if ( function_exists( 'icl_get_default_language' ) ) {
			$arg['lang'] = icl_get_default_language();
		}

		$query = new \WP_Query( $args );

		return $query->posts;
	}

	public function set_schedules_for_a_day() {
		$schedules = get_option( 'wvr_schedules_setting' );
		if ( empty( $schedules ) ) {
			return;
		}

		$current_time = intval( current_time( 'U' ) );
		$gmt_offset   = (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );

		foreach ( $schedules as $schedule ) {
			$qtys = [];

			if ( isset( $schedule['quantity_from'] ) && $schedule['quantity_from'] !== '' ) {
				$qtys['min'] = $schedule['quantity_from'];
			}

			if ( isset( $schedule['quantity_to'] ) && $schedule['quantity_to'] !== '' ) {
				$qtys['max'] = $schedule['quantity_to'];
			}

			$schedule['quantity'] = count( $qtys ) > 1 ? rand( $qtys['min'], $qtys['max'] ) : current( $qtys );

			if ( empty( $schedule['active'] ) || empty( $schedule['quantity'] ) ) {
				continue;
			}

			$date_from = ! empty( $schedule['date_from'] ) ? $schedule['date_from'] : 'today';
			$date_to   = ! empty( $schedule['date_to'] ) ? $schedule['date_to'] : 'today';

			$start_date = strtotime( $date_from );
			$end_date   = strtotime( "{$date_to} +24hour" ) - 1;

			if ( ( $current_time < $start_date ) || ( $current_time > $end_date ) ) {
				continue;
			}

			$time_from = ! empty( $schedule['time_from'] ) ? $schedule['time_from'] : '00:00:00';
			$time_to   = ! empty( $schedule['time_to'] ) ? $schedule['time_to'] : '23:59:59';

			$today      = date( 'Y-m-d', $current_time );
			$start_time = intval( strtotime( "{$today} {$time_from}" ) );
			$end_time   = intval( strtotime( "{$today} {$time_to}" ) );

			if ( ( $current_time < $start_time ) || ( $current_time > $end_time ) ) {
				continue;
			}

			$repeat_type = $schedule['repeat_type'] ?? '';
			$check_time  = false;

			switch ( $repeat_type ) {
				case '':
				case 'none':
				case 'daily':
					$check_time = true;
					break;

				case 'weekly':
					$date           = getdate();
					$weekday        = $date['wday'];
					$repeat_weekday = $schedule['repeat_weekday'] ?? [];
					$check_time     = in_array( $weekday, $repeat_weekday );
					break;

				case 'monthly':
					$date       = getdate();
					$mday       = $date['mday'];
					$repeat_day = $schedule['repeat_day'] ?? [];
					$check_time = in_array( $mday, $repeat_day );
					break;
			}

			if ( ! $check_time ) {
				continue;
			}

			$product_ids = $this->get_product_ids( $schedule );

			if ( empty( $product_ids ) ) {
				continue;
			}

			$time_array = Utils::generate_time_array( $schedule['quantity'], $current_time, $end_time );

			foreach ( $time_array as $time ) {
				$time = $time - $gmt_offset;
				wp_schedule_single_event( $time, 'faview_add_review_via_schedule', [ $schedule ] );
			}
		}
	}

	public function init_background_processing() {
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'wp_wvr_background_add_virtual_reviews' ) {
			$this->load_background_processing_class();
			Add_Virtual_Reviews::instance();
		}
	}

	public function load_background_processing_class() {
		if ( ! class_exists( 'WP_Async_Request' ) ) {
			include_once WVR_CONST['plugin_dir'] . 'inc/background-process/wp-async-request.php';
		}

		if ( ! class_exists( 'WP_Background_Process' ) ) {
			include_once WVR_CONST['plugin_dir'] . 'inc/background-process/wp-background-process.php';
		}
	}

	public function add_review_via_schedule( $schedule ) {
		$product_ids = $this->get_product_ids( $schedule );
		if ( empty( $product_ids ) ) {
			return;
		}

		$random = [];

		if ( isset( $schedule['product_limit_from'] ) && $schedule['product_limit_from'] !== '' ) {
			$random['min'] = $schedule['product_limit_from'];
		}

		if ( isset( $schedule['product_limit_to'] ) && $schedule['product_limit_to'] !== '' ) {
			$random['max'] = $schedule['product_limit_to'];
		}

		if ( count( $random ) ) {
			$count_all     = count( $product_ids );
			$product_limit = rand( $random['min'] ?? 0, $random['max'] ?? $count_all );
			$product_ids   = array_rand( array_flip( $product_ids ), $product_limit );
		}

		$this->load_background_processing_class();
		$review_processing = Add_Virtual_Reviews::instance();

		$parse_product_ids = array_chunk( $product_ids, 50 );

		$parse_langs  = [];
		$default_lang = '';

		if ( function_exists( 'icl_get_languages' ) ) {
			$settings       = Data::instance();
			$selected_langs = $settings->get_param( 'add_to_other_langs' );
			$icl_lang_list  = icl_get_languages();
			$default_lang   = icl_get_default_language();
			$parse_langs    = ! empty( $selected_langs ) ? $selected_langs : array_keys( $icl_lang_list );
		}

		$parse_langs[] = 'default';

		$has_item = false;

		foreach ( $parse_langs as $code ) {
			if ( $code == $default_lang ) {
				continue;
			}
			foreach ( $parse_product_ids as $ids ) {
				$review_processing->push_to_queue( [ 'ids' => $ids, 'lang' => $code ] );
				$has_item = true;
			}
		}

		if ( $has_item ) {
			$review_processing->save()->dispatch();
		}
	}
}
