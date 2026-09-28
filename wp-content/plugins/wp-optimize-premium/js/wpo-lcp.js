(function() {
	'use strict';

	if ('undefined' === typeof PerformanceObserver) return;
	if ('undefined' === typeof wpo_lcp_observer_data) return;

	var debug = '1' === wpo_lcp_observer_data.debug;

	/**
	 * Log a prefixed message to the browser console when WPO_LCP_DEBUG is enabled.
	 * Mirrors the server-side WPO_LCP_DEBUG constant so client and server logs align.
	 *
	 * @param {string} message
	 * @param {*=}     context  Optional value to log alongside the message.
	 * @return void
	 */
	function log(message, context) {
		if (!debug) return;
		if ('undefined' !== typeof context) {
			console.log('[WPO-LCP] ' + message, context);
		} else {
			console.log('[WPO-LCP] ' + message);
		}
	}

	var lcp_entry = null;
	var reported  = false;
	var observer;

	/**
	 * Resolve the resource URL from a PerformanceObserver LCP entry.
	 *
	 * Tries entry.url first (set directly by the browser for image/video resources),
	 * then falls back to inspecting the associated DOM element for <img>, <video>,
	 * and CSS background-image sources.
	 *
	 * @param {PerformanceEntry} entry - The LCP PerformanceObserver entry.
	 * @return {string|null} The resolved resource URL, or null if not resolvable (e.g., text nodes, inline SVGs).
	 */
	function resolve_url(entry) {
		if (entry.url) return entry.url;
		const el = entry.element;
		if (!el) return null;
		const tag = el.tagName ? el.tagName.toLowerCase() : '';
		if ('img' === tag)   return el.currentSrc || el.src || null;
		if ('video' === tag) return el.poster || el.currentSrc || null;
		try {
			const bg = window.getComputedStyle(el).backgroundImage;
			if (bg && 'none' !== bg) {
				const m = bg.match(/url\(["']?([^"')]+)/);
				if (m) return m[1];
			}
		} catch(e) {}
		return null;
	}

	/**
	 * Resolve the preload `as` type from a resource URL.
	 *
	 * Inspects the file extension to distinguish font resources from images.
	 * Query strings are stripped before extension detection.
	 * Defaults to 'image' for unrecognized or extensionless URLs (e.g., CDN URLs).
	 *
	 * @param {string|null} url - The resource URL to inspect.
	 * @return {string} Preload type: 'font' or 'image'.
	 */
	function resolve_type(url) {
		if (!url) return 'image';
		const ext = url.split('?')[0].split('.').pop().toLowerCase();
		if (['woff','woff2','ttf','otf','eot'].indexOf(ext) !== -1) return 'font';
		return 'image';
	}

	/**
	 * Check if a resource URL is already declared as a preload in the page <head>.
	 *
	 * Compares pathname only to avoid false mismatches caused by scheme
	 * or host differences (e.g., http vs https, CDN vs origin host).
	 * Falls back to direct href comparison if URL parsing fails.
	 *
	 * @param {string} url - The LCP resource URL to check.
	 * @return {boolean} True if a matching <link rel="preload"> already exists.
	 */
	function is_already_preloaded(url) {
		const links = document.querySelectorAll('link[rel="preload"]');
		for (var i = 0; i < links.length; i++) {
			try {
				const linkPath = new URL(links[i].href).pathname;
				const lcpPath  = new URL(url, window.location.href).pathname;
				if (linkPath === lcpPath) return true;
			} catch(e) {
				if (links[i].href === url) return true;
			}
		}
		return false;
	}

	/**
	 * Send the detected LCP resource to the server via Beacon API or XHR fallback.
	 *
	 * Fires at most once per page load (guarded by the `reported` flag).
	 *
	 * @param {PerformanceEntry} entry - The LCP PerformanceObserver entry to report.
	 * @return void
	 */
	function send_to_server(entry) {
		if (reported) return;

		// cache_file is baked into the config at cache-write time.
		// If it is absent, then bail out.
		var cache_file = wpo_lcp_observer_data.cache_file;
		if (!cache_file) {
			log('Cache file name missing from config — skipping.');
			return;
		}

		log('Cache file: ' + cache_file);

		// Gate on max allowed preloads. Once the limit is reached, the cache file is stable.
		// Checking here prevents the loop when lcp changes.
		var wpo_preload_count = document.querySelectorAll('link[rel="preload"][data-wpo-lcp="1"]').length;
		var max_lcp_preloads  = parseInt(wpo_lcp_observer_data.max_lcp_preloads, 10) || 1;
		if (wpo_preload_count >= max_lcp_preloads) {
			log('Max LCP preloads (' + max_lcp_preloads + ') already present — cache file patched, skipping.');
			return;
		}

		var min_size = wpo_lcp_observer_data.min_lcp_size || 50000;
		if (entry.size > 0 && entry.size < min_size) {
			log('LCP size too small, skipping. Size: ' + entry.size + ', min: ' + min_size);
			return;
		}

		const url = resolve_url(entry);
		if (!url) {
			log('Could not resolve LCP resource URL, skipping.');
			return;
		}

		const blocked = ['google-analytics.com', 'googletagmanager.com', 'facebook.com/tr', 'doubleclick.net'];
		for (var i = 0; i < blocked.length; i++) {
			if (url.indexOf(blocked[i]) !== -1) {
				log('LCP URL is a blocked third-party domain, skipping: ' + url);
				return;
			}
		}

		if (is_already_preloaded(url)) {
			log('LCP URL already preloaded by another tag, skipping: ' + url);
			return;
		}

		log('Sending LCP beacon. URL: ' + url + ', cache file: ' + cache_file + ', size: ' + entry.size);

		reported = true;

		const data = {
			action:     'wpo_lcp_beacon',
			nonce:      wpo_lcp_observer_data.nonce,
			lcp_url:    url,
			asset_type: resolve_type(url),
			page_url:   window.location.href,
			cache_file: cache_file,
			size:       Math.round(entry.size || 0)
		};

		const payload = Object.keys(data).map(function(k) {
			return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]);
		}).join('&');

		var beacon_queued = false;

		if (navigator.sendBeacon) {
			const blob = new Blob([payload], {type: 'application/x-www-form-urlencoded'});
			if (navigator.sendBeacon(wpo_lcp_observer_data.ajax_url, blob)) {
				beacon_queued = true;
				log('Beacon sent via sendBeacon API.');
			}
		}

		if (!beacon_queued) {
			const xhr = new XMLHttpRequest();
			xhr.open('POST', wpo_lcp_observer_data.ajax_url, true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.send(payload);
			log('Beacon sent via XHR fallback.');
		}
	}

	/**
	 * Handle the first user interaction event to finalise LCP detection.
	 *
	 * Removes all interaction listeners to prevent duplicate handling,
	 * drains any buffered PerformanceObserver records, disconnects the
	 * observer, then sends the last recorded LCP entry to the server.
	 *
	 * Triggered by click, keydown, or pointerdown — whichever fires first.
	 *
	 * @return void
	 */
	function on_interaction() {
		document.removeEventListener('click',       on_interaction, true);
		document.removeEventListener('keydown',     on_interaction, true);
		document.removeEventListener('pointerdown', on_interaction, true);
		observer.takeRecords();
		observer.disconnect();
		log('User interaction detected — finalising LCP entry.');
		if (lcp_entry) send_to_server(lcp_entry);
	}

	// Observer must be assigned before interaction listeners are registered,
	// since on_interaction() references it directly.
	observer = new PerformanceObserver(function(list) {
		const entries = list.getEntries();
		log('LCP entries.', entries);
		
		// Always keep the latest entry — LCP can be updated multiple times
		// as larger elements are painted later in the page lifecycle.
		lcp_entry = entries[entries.length - 1];
		log('LCP latest entry.', {url: lcp_entry.url, size: lcp_entry.size, element: lcp_entry.element});
	});

	try {
		observer.observe({type: 'largest-contentful-paint', buffered: true});
	} catch(e) {
		// Browser does not support the largest-contentful-paint entry type.
		log('PerformanceObserver does not support largest-contentful-paint — skipping.');
		return;
	}

	// Also report on pagehide (tab close, navigation away) to capture
	// sessions where the user leaves without any interaction.
	window.addEventListener('pagehide', function() {
		observer.takeRecords();
		log('pagehide event — finalising LCP entry.');
		if (lcp_entry) send_to_server(lcp_entry);
	}, {capture: true});

	document.addEventListener('click',       on_interaction, {capture: true, once: true});
	document.addEventListener('keydown',     on_interaction, {capture: true, once: true});
	document.addEventListener('pointerdown', on_interaction, {capture: true, once: true});

}());
