<?php

if (! defined('ABSPATH')) {
	exit;
}

function blocksy_companion_get_waitlist_slug() {
	/**
	 * Filters the waitlist My Account endpoint slug.
	 *
	 * @since 2.0.70
	 *
	 * @param string $slug The endpoint slug. Default 'woo-waitlist-list'.
	 */
	return apply_filters(
		'blocksy:pro:woocommerce-extra:waitlist-list:slug',
		'woo-waitlist-list'
	);
}
