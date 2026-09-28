<?php
if (!defined('ABSPATH')) exit;

if (!trait_exists('WP_Optimize_LCP_Logger_Trait')) :

/**
* Trait WP_Optimize_LCP_Logger_Trait
*
* Use to debug LCP issues via logs
*/
trait WP_Optimize_LCP_Logger_Trait {

	/**
	 * Log message into PHP log.
	 *
	 * @param string $message Log message.
	 * @param array<string, mixed> $context Optional context data for debugging.
	 */
	protected function log(string $message, array $context = array()): void {
		if (!defined('WPO_LCP_DEBUG') || !WPO_LCP_DEBUG) return;

		$logger = $this->get_logger();

		if (is_object($logger) && method_exists($logger, 'debug')) {
			$logger->debug('[WPO-LCP] ' . $message, $context);
		} else {
			error_log('[WPO-LCP] ' . $message . ' - ' . wp_json_encode($context)); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- Using for debugging purpose
		}
	}

	/**
	 * Return a logger instance supporting ->debug($message, $context)
	 * or null if not available.
	 *
	 * @return object|null
	 */
	abstract protected function get_logger();
}
endif;
