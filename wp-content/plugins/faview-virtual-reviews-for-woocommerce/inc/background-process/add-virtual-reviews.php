<?php

namespace VirtualReviews\Inc\Background_Process;

use VirtualReviews\Inc\Reviews;

defined( 'ABSPATH' ) || exit;


class Add_Virtual_Reviews extends \WP_Background_Process {
	protected static $instance = null;
	/**
	 * @var string
	 */
	protected $action = 'wvr_background_add_virtual_reviews';

	public static function instance() {
		return self::$instance == null ? self::$instance = new self : self::$instance;
	}

	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param mixed $item Queue item to iterate over
	 *
	 * @return mixed
	 */
	protected function task( $item ) {
		try {
			if ( ! empty( $item['ids'] ) ) {
				$lang = $item['lang'] ?? 'default';
				foreach ( $item['ids'] as $pid ) {
					$reviews = Reviews::instance();
					$reviews->set_current_lang( $lang );
					$reviews->set_comment_list( $reviews->find_comment_list( $pid ) );

					if ( function_exists( 'pll_get_post' ) && $lang !== 'default' ) {
						$pid = pll_get_post( $pid, $lang );
						if ( ! $pid ) {
							continue;
						}
					}

					$reviews->add_single_review( $pid, current_time( 'U' ) );
					\WC_Comments::clear_transients( $pid );
				}
			}

		} catch ( \Exception $e ) {
			return false;
		}

		return false;
	}

	/**
	 * Is the updater running?
	 *
	 * @return boolean
	 */
	public function is_process_running() {
		return parent::is_process_running();
	}

	/**
	 * Is the queue empty
	 *
	 * @return boolean
	 */
	public function is_queue_empty() {
		return parent::is_queue_empty();
	}

	/**
	 * Complete
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	protected function complete() {
		if ( $this->is_queue_empty() && ! $this->is_process_running() ) {
			set_transient( $this->action . '_complete', time() );
		}
		// Show notice to user or perform some other arbitrary task...
		parent::complete();
	}

	/**
	 * Delete all batches.
	 *
	 * @return Download_Images
	 */
	public function delete_all_batches() {
		global $wpdb;

		$table  = $wpdb->options;
		$column = 'option_name';

		if ( is_multisite() ) {
			$table  = $wpdb->sitemeta;
			$column = 'meta_key';
		}

		$key = $wpdb->esc_like( $this->identifier . '_batch_' ) . '%';

		$wpdb->query( $wpdb->prepare( "DELETE FROM {$table} WHERE {$column} LIKE %s", $key ) ); // @codingStandardsIgnoreLine.

		return $this;
	}

	/**
	 * Kill process.
	 *
	 * Stop processing queue items, clear cronjob and delete all batches.
	 */
	public function kill_process() {
		if ( ! $this->is_queue_empty() ) {
			$this->delete_all_batches();
			wp_clear_scheduled_hook( $this->cron_hook_identifier );
		}
	}
}