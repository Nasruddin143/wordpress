<?php

if (!defined('ABSPATH')) die('No direct access allowed');

add_filter('wpo_cache_page_force', 'wpo_cache_aelia_currency_support');

/**
 * Returns true if we need to cache the current POST request from Aelia Currency.
 *
 * @param bool $status
 * @return bool
 */
function wpo_cache_aelia_currency_support($status) {
	// When the Aelia Currency caching option is enabled, we check $_POST for the posted currency,
	// and if a value is posted, we put it into the $_COOKIE array to be handled later by
	// the page cache filename generator function.
	// We don't need to handle any other values because they are already in the $_COOKIE.
	if (!empty($GLOBALS['wpo_cache_config']['enable_cache_aelia_currency']) && $GLOBALS['wpo_cache_config']['enable_cache_aelia_currency']) {
		// phpcs:disable WordPress.Security.NonceVerification.Missing -- Not provided by Aelia Currency plugin
		// This is comment from their code base -- There is no nonce, because nonces expire (and thus are incompatible with front-end page-caching), and no state-changing actions are taken.
		if (isset($_POST['aelia_cs_currency']) && is_string($_POST['aelia_cs_currency'])) {
			$aelia_selected_currency = trim(strip_tags($_POST['aelia_cs_currency'])); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.WP.AlternativeFunctions.strip_tags_strip_tags, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- WP functions may not be available, so using php native functions.
			$_COOKIE['aelia_cs_selected_currency'] = $aelia_selected_currency;
			// True means that we cache the current POST request.
			return true;
		}
		// phpcs:enable
	}
	return $status;
}

add_filter('wpo_cache_modified_time', 'wpo_dont_browser_cache_wcml_currency_requests', 10, 2);

/**
 * Check if the current request is for a WCML currency page and if so, set the modified time to the current time to force a revalidation.
 *
 * @param int $modified_time
 * @param string $path
 * @return int
 */
function wpo_dont_browser_cache_wcml_currency_requests($modified_time, $path) {
	if (false !== strpos($path, 'wcml_client_currency=')) {
		$modified_time = time();
	}
	return $modified_time;
}
