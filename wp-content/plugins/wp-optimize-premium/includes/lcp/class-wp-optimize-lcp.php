<?php

if (!defined('ABSPATH')) die('No direct access allowed');

if (!class_exists('WP_Optimize_LCP')) :

/**
 * Class WP_Optimize_LCP
 *
 * Handles LCP (Largest Contentful Paint) optimization.
 * Injects PerformanceObserver JS to measure the real LCP element,
 * receives it via AJAX beacon, then modifies the cached HTML file
 * to inject a precise <link rel="preload"> tag for future visitors.
 */
class WP_Optimize_LCP {
	use WP_Optimize_LCP_Logger_Trait;

	/**
	 * Minimum LCP size threshold (approx width × height in pixels).
	 * Used to ignore small UI elements like icons or logos.
	 *
	 * @var int
	 */
	private $wpo_lcp_min_size = 50000;

	/**
	 * Maximum LCP preload element threshold.
	 *
	 * @var int
	 */
	private $wpo_lcp_max_preloads = 1;

	/**
	 * Class constructor
	 *
	 * @return void
	 */
	private function __construct() {

		$this->wpo_lcp_min_size = apply_filters('wpo_lcp_min_size', $this->wpo_lcp_min_size);
		$this->wpo_lcp_max_preloads = apply_filters('wpo_lcp_max_preloads', $this->wpo_lcp_max_preloads);

		add_action('wp_ajax_wpo_lcp_beacon', array($this, 'handle_lcp_beacon'));
		add_action('wp_ajax_nopriv_wpo_lcp_beacon', array($this, 'handle_lcp_beacon'));
		add_filter('wpo_cache_add_to_footer', array($this, 'inject_lcp_assets'), 10, 2);
	}

	/**
	 * Returns singleton instance object
	 *
	 * @return WP_Optimize_LCP
	 */
	public static function instance() {
		static $_instance = null;
		if (null === $_instance) {
			$_instance = new self();
		}
		return $_instance;
	}

	/**
	 * Get a logger instance.
	 * Uses static caching to initialize the logger only once per request.
	 *
	 * @return Updraft_PHP_Logger|null Logger instance implementing debug(string, array) or null if unavailable.
	 */
	protected function get_logger() {
		static $logger = null;

		if (null === $logger) {
			$logger = class_exists('Updraft_PHP_Logger') ? new Updraft_PHP_Logger() : null;
		}

		return $logger;
	}

	/**
	 * Inject the LCP observer script and its configuration directly into the cached HTML footer.
	 * Hooked to wpo_cache_add_to_footer at cache-write time, so the script and all config values
	 * Including the exact cache filename are baked into the cached HTML once and served statically.
	 *
	 * @param string $footer         Existing footer HTML to append to.
	 * @param string $cache_filename Filename of the cache file being written (e.g. index.webp.html).
	 * @return string
	 */
	public function inject_lcp_assets($footer, $cache_filename): string {
		$data = array(
			'ajax_url'         => admin_url('admin-ajax.php'),
			'nonce'            => wp_create_nonce('wpo_lcp_observer'),
			'min_lcp_size'     => $this->wpo_lcp_min_size,
			'max_lcp_preloads' => $this->wpo_lcp_max_preloads,
			'cache_file'       => $cache_filename,
			'debug'            => defined('WPO_LCP_DEBUG') && WPO_LCP_DEBUG ? '1' : '0',
		);

		$js_url = WPO_PLUGIN_URL . 'js/wpo-lcp' . WP_Optimize()->get_min_or_not_internal_string() . '.js';
		$version = WP_Optimize()->get_enqueue_version();

		$footer .= '<script id="wp-optimize-lcp-observer-js-config">var wpo_lcp_observer_data = ' . wp_json_encode($data) . ';</script>';
		$footer .= '<script id="wp-optimize-lcp-observer-js" src="' . esc_url($js_url) . '?ver=' . esc_attr($version) . '"></script>'; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript -- This is only needed in cached file html

		return $footer;
	}

	/**
	 * Process incoming LCP beacon request via AJAX.
	 * Validates nonce, sanitizes input, filters small elements,
	 * and attempts to update cached HTML with a preload tag.
	 *
	 * @return void Sends JSON response and exits.
	 */
	public function handle_lcp_beacon(): void {
		if (!TeamUpdraft\WP_Optimize\Includes\Fragments\verify_nonce('nonce', 'wpo_lcp_observer')) {
			wp_send_json_error(array('message' => __('Invalid nonce', 'wp-optimize')), 403);
		}

		$lcp_url    = TeamUpdraft\WP_Optimize\Includes\Fragments\fetch_superglobal('post', 'lcp_url', 'string', 'esc_url_raw', '');
		$page_url   = TeamUpdraft\WP_Optimize\Includes\Fragments\fetch_superglobal('post', 'page_url', 'string', 'esc_url_raw', '');
		$asset_type = TeamUpdraft\WP_Optimize\Includes\Fragments\fetch_superglobal('post', 'asset_type', 'string', 'sanitize_key', 'image');
		$size       = TeamUpdraft\WP_Optimize\Includes\Fragments\fetch_superglobal('post', 'size', 'string', 'sanitize_text_field', '0');
		$cache_file = TeamUpdraft\WP_Optimize\Includes\Fragments\fetch_superglobal('post', 'cache_file', 'string', 'sanitize_text_field', '');

		$lcp_url    = is_string($lcp_url) ? $lcp_url : '';
		$page_url   = is_string($page_url) ? $page_url : '';
		$asset_type = is_string($asset_type) ? $asset_type : '';
		// basename() prevents path traversal; regex allows only characters used in WPO cache filenames.
		$cache_file = is_string($cache_file) ? basename($cache_file) : '';
		if (!preg_match('/^[a-zA-Z0-9_\-\.=]+$/', $cache_file)) {
			$cache_file = '';
		}

		if (empty($cache_file)) {
			$this->log('Cache file name missing or invalid in beacon request.');
			wp_send_json_error(array('message' => __('Invalid cache file', 'wp-optimize')), 400);
		}

		$size = is_scalar($size) ? absint($size) : 0;

		if (empty($lcp_url) || empty($page_url)) {
			wp_send_json_error(array('message' => __('Missing required fields', 'wp-optimize')), 400);
		}

		// Validate asset_type
		if (!in_array($asset_type, array('image', 'font'), true)) {
			$asset_type = 'image';
		}

		if (!$this->is_allowed_lcp_extension($lcp_url)) {
			$this->log('LCP URL has disallowed extension, skipping LCP URL: {lcp_url}', array('lcp_url' => $lcp_url));
			wp_send_json_success(array('message' => __('Disallowed resource type', 'wp-optimize')));
		}

		if ($this->is_rate_limited(md5($page_url), 5, HOUR_IN_SECONDS)) {
			$this->log('Rate limit reached for page: {page_url}', array('page_url' => $page_url));
			wp_send_json_success(array('message' => __('Rate limit reached', 'wp-optimize')));
		}

		$this->log('LCP beacon received Page URL: {page_url}, LCP URL: {lcp_url}, As Type: {asset_type}, Size: {size} ', array(
			'page_url'   => $page_url,
			'lcp_url'    => $lcp_url,
			'asset_type' => $asset_type,
			'size'       => $size,
		));

		$lcp_min_size = $this->wpo_lcp_min_size;

		// Skip small elements (icons, small images, etc.)
		if ($size > 0 && $size < $lcp_min_size) {
			$this->log('LCP size too small, skipping Size: {size}', array('size' => $size));
			wp_send_json_success(array('message' => __('LCP size too small, skipped', 'wp-optimize')));
		}

		$result = $this->patch_cached_file($page_url, $lcp_url, $asset_type, $cache_file);

		if ($result) {
			wp_send_json_success(array('message' => __('Cache file patched', 'wp-optimize')));
		} else {
			// Not an error — a cache file may not exist yet, or preload already presents
			wp_send_json_success(array('message' => __('No patch needed', 'wp-optimize')));
		}
	}

	/**
	 * Write data to a file atomically by writing to a temporary file first, then renaming it into place.
	 * Avoids readers seeing a partially written file.
	 *
	 * @param string $target_file Destination file path.
	 * @param string $data        Data to write.
	 * @return bool True on success, false otherwise.
	 */
	private function atomic_write_file($target_file, $data): bool {
		$tmp = $target_file . '.tmp';

		if (false === file_put_contents($tmp, $data)) { // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents -- Cache directory is guaranteed writable; WP_Filesystem is not appropriate for non-interactive AJAX context as it may trigger FTP credential prompts.
			$this->log('Could not write tmp file: {file}', array('file' => $tmp));
			wp_delete_file($tmp);
			return false;
		}

		if (!rename($tmp, $target_file)) { // phpcs:ignore WordPress.WP.AlternativeFunctions.rename_rename -- Cache directory is guaranteed writable; WP_Filesystem is not appropriate for non-interactive AJAX context as it may trigger FTP credential prompts.
			$this->log('Could not move tmp to target file: {file}', array('file' => $target_file));
			wp_delete_file($tmp);
			return false;
		}

		return true;
	}

	/**
	 * Inject the LCP preload tag into a cached HTML file.
	 * Uses the exact filename sent by the JS beacon
	 *
	 * @param string $page_url   Page URL being processed.
	 * @param string $lcp_url    Detected LCP resource URL from PerformanceObserver.
	 * @param string $asset_type Preload as= value: image | font.
	 * @param string $filename   Exact cache filename from the marker div (e.g. index.webp.html).
	 * @return bool True if the cache was updated, false otherwise.
	 */
	private function patch_cached_file($page_url, $lcp_url, $asset_type, $filename): bool {

		$cache_folder = WPO_Page_Cache::get_full_path_from_url($page_url);
		$cache_file = $cache_folder . $filename;

		if (!file_exists($cache_file)) {
			$this->log('Cache file not found, skipping: {file}', array('file' => $cache_file));
			return false;
		}

		$this->log('Cache file found: {file}', array('file' => $cache_file));

		// Prevent concurrent cache patching for the same cache file
		$lock_name = 'wpo_lcp_' . md5($cache_file);
		$semaphore = new Updraft_Semaphore_3_0($lock_name, 30);
		if (!$semaphore->lock()) {
			$this->log('Could not acquire semaphore lock: {lock}', array('lock' => $lock_name));
			return false;
		}

		try {
			$html = file_get_contents($cache_file); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Cache directory is guaranteed writable; WP_Filesystem is not appropriate for non-interactive AJAX context as it may trigger FTP credential prompts.
			if (false === $html) {
				$this->log('Could not read cache file: {file}', array('file' => $cache_file));
				return false;
			}

			// Already have this check in js but added here again.
			if ($this->is_same_lcp_preload_present($html, $lcp_url)) {
				$this->log('Same LCP preload already present, skipping: {url}', array('url' => $lcp_url));
				return false;
			}

			$existing_count = preg_match_all('/data-wpo-lcp="1"/', $html);
			$max_preloads   = $this->wpo_lcp_max_preloads;

			if ($existing_count >= $max_preloads) {
				$this->log('Max LCP preloads ({max}) already in cache file, skipping: {url}', array('max' => $max_preloads, 'url' => $lcp_url));
				return false;
			}

			// If we can confirm it's in the HTML directly, great — skip deeper validation
			if ($this->is_lcp_url_present_in_cache($html, $lcp_url)) {
				// Definitely legitimate, proceed
				$this->log('LCP URL is present in cached file. LCP URL: {lcp_url}', array('lcp_url' => $lcp_url));
			} elseif (!$this->is_allowed_lcp_origin($lcp_url)) {
				// Not in HTML AND different-origin — reject
				$this->log('LCP URL failed origin validation. LCP URL: {lcp_url}', array('lcp_url' => $lcp_url));
				return false;
			}

			$tag     = $this->build_preload_tag($lcp_url, $asset_type);
			$patched = $this->inject_tag_before_head_close($html, $tag);

			if (false === $patched) {
				$this->log('Could not patch the preload tag in head tag of html');
				return false;
			}

			// Remove the observer script only when the preload slots are now full.
			// If max_preloads is not yet reached, the observer must remain so future page loads can detect
			// and report additional LCP candidates.
			if (($existing_count + 1) >= $max_preloads) {
				$patched = $this->remove_lcp_observer_script($patched);
				$this->log('Max preloads reached ({max}) — LCP observer script removed from cache.', array('max' => $max_preloads));
			}

			if (!$this->atomic_write_file($cache_file, $patched)) {
				return false;
			}

			$gz_file = $cache_file . '.gz';
			if (file_exists($gz_file)) {
				$gz_data = function_exists('gzencode') ? gzencode($patched, 9) : false;
				if (false === $gz_data) {
					$this->log('gzencode failed for: {file}, removing stale gzip cache', array('file' => $gz_file));
					wp_delete_file($gz_file);
				} elseif (!$this->atomic_write_file($gz_file, $gz_data)) {
					$this->log('Removing stale gzip cache after failed update: {file}', array('file' => $gz_file));
					wp_delete_file($gz_file);
				}
			}

			$this->log('Cache file patched: {file}', array('file' => $cache_file));

		} finally {
			$semaphore->release();
		}

		return true;
	}

	/**
	 * Inject the preload tag before the closing </head> tag.
	 *
	 * To avoid false matches on </head> inside inline JS strings (e.g., var x = '</head>'),
	 * the real </head> is identified by finding the </head>...<body> sequence first.
	 * If that pattern is not found, it falls back to the last </head> in the document.
	 *
	 * @param string $html Full HTML content.
	 * @param string $tag  Preload tag to inject.
	 *
	 * @return string|false Modified HTML or false if no valid injection point found.
	 */
	private function inject_tag_before_head_close($html, $tag) {

		// Find </head> that is followed by optional whitespace then <body — this is the real </head>.
		// Avoids false matches on </head> inside inline JS strings.
		if (preg_match('/(<\/head>)(\s*<body)/i', $html, $matches, PREG_OFFSET_CAPTURE)) {
			$head_close_pos = $matches[1][1]; // byte offset of the real </head>
			$this->log('Head closing tag before opening body tag found.');
			return substr($html, 0, $head_close_pos) . $tag . substr($html, $head_close_pos);
		}

		// FALLBACK: no <body> found after </head> — use the last </head> in the document.
		$last_head_close_pos = strripos($html, '</head>');
		if (false === $last_head_close_pos) {
			$this->log('No closing head tag found in cache file');
			return false;
		}
		
		$this->log('Falling back to last closing head tag found in cache file.');
		return substr($html, 0, $last_head_close_pos) . $tag . substr($html, $last_head_close_pos);
	}

	/**
	 * Check if the same LCP preload already exists in HTML.
	 * Only considers tags injected by wpo (data-wpo-lcp="1").
	 * Compares normalized URLs to avoid false mismatches.
	 *
	 * @param string $html    Cached HTML content.
	 * @param string $lcp_url New LCP resource URL.
	 *
	 * @return bool True if an identical preload already exists.
	 */
	private function is_same_lcp_preload_present($html, $lcp_url): bool {
		// Match ONLY preload tags with our marker
		$result = preg_match_all(
			'/<link(?=[^>]*\bdata-wpo-lcp="1")(?=[^>]*\bhref=["\']([^"\']+)["\'])[^>]*>/i',
			$html,
			$matches
		);

		// Validate regex didn't fail, and we have captures
		if (false === $result || 0 === $result) {
			return false;
		}

		foreach ($matches[1] as $href) {
			if (empty($href)) {
				continue;
			}
			if ($this->normalize_url($href) === $this->normalize_url($lcp_url)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Normalize a URL for comparison.
	 *
	 * Returns the URL in a normalized format consisting of the host, path,
	 * and query string (if present), with the scheme removed. If the URL
	 * cannot be parsed, the original value is returned.
	 *
	 * @param string $url Raw URL.
	 *
	 * @return string Normalized URL, or the original URL if parsing fails.
	 */
	private function normalize_url($url): string {
		$parsed = wp_parse_url($url);
		if (!$parsed) return $url;

		$site_host = (string) wp_parse_url(home_url(), PHP_URL_HOST);
		$host  = strtolower($parsed['host'] ?? $site_host);
		$path  = rtrim($parsed['path'] ?? '', '/');
		$query = isset($parsed['query']) ? '?' . $parsed['query'] : '';

		return $host . $path . $query;
	}

	/**
	 * Remove the LCP observer script tags from cached HTML after the preload tag has been injected.
	 * Future loads of these cached pages will skip the observer JS — one fewer file to fetch.
	 * Both the inline config block and the external script src tag are removed by their id= attribute.
	 *
	 * @param  string $html Patched HTML content.
	 * @return string HTML with observer script tags removed.
	 */
	private function remove_lcp_observer_script($html): string {
		$result = preg_replace('/<script\b[^>]*\bid="wp-optimize-lcp-observer-js-config"[^>]*>.*?<\/script>\s*/is', '', $html);
		if (null !== $result) {
			$html = $result;
		}

		$result = preg_replace('/<script\b[^>]*\bid="wp-optimize-lcp-observer-js"[^>]*>\s*<\/script>\s*/i', '', $html);
		if (null !== $result) {
			$html = $result;
		}

		return $html;
	}

	/**
	 * Build a single <link rel="preload"> tag string.
	 * Includes data-wpo-lcp="1" marker so we can identify and replace it later.
	 *
	 * @param  string $url        The LCP resource URL to preload.
	 * @param  string $asset_type Preload as= value: image | font.
	 * @return string
	 */
	private function build_preload_tag($url, $asset_type): string {
		$tag = sprintf(
			'<link rel="preload" href="%1$s" as="%2$s" fetchpriority="high" data-wpo-lcp="1"',
			esc_url($url),
			esc_attr($asset_type)
		);

		if ('font' === $asset_type) {
			$tag .= ' crossorigin="anonymous"';
		}

		return $tag . " />\n";
	}

	/**
	 * Verify that the reported LCP URL is actually referenced
	 * in the cached HTML for this page.
	 *
	 * This prevents an attacker with valid nonce from injecting
	 * arbitrary external URLs as preload hints.
	 *
	 * @param string $html    The cached HTML content for the page.
	 * @param string $lcp_url The URL reported by the beacon.
	 * @return bool True if the LCP URL is found in the cached HTML, false otherwise.
	 */
	private function is_lcp_url_present_in_cache($html, $lcp_url): bool {
		$normalized = $this->normalize_url($lcp_url);
		if (empty($normalized)) {
			return false;
		}

		// Match src/href/poster="...", srcset="...", url(...) — covers <img>, <link>, <video poster>, <picture> <source srcset>, background-image, etc.
		// xlink:href is matched implicitly by the unanchored `href=` alternative.
		preg_match_all(
			'/(?:src|href|poster)=["\']([^"\']+)["\']|srcset=["\']([^"\']+)["\']|url\(["\']?([^"\')\s]+)["\']?\)/i',
			$html,
			$matches
		);

		$candidates = array_filter(array_merge($matches[1], $matches[3]));

		// srcset values contain comma-separated entries like "img.jpg 1x, img@2x.jpg 2x"
		// or "small.jpg 480w, large.jpg 1024w". URL is the first whitespace-separated token of each entry.
		foreach ($matches[2] as $srcset) {
			if ('' === $srcset) {
				continue;
			}
			foreach (explode(',', $srcset) as $entry) {
				$entry = trim($entry);
				if ('' === $entry) {
					continue;
				}
				$parts = preg_split('/\s+/', $entry, 2);
				if (!empty($parts[0])) {
					$candidates[] = $parts[0];
				}
			}
		}

		foreach ($candidates as $candidate) {
			if ($this->normalize_url($candidate) === $normalized) {
				return true;
			}
		}

		$this->log('LCP URL is not present in cached file. LCP URL: {lcp_url}', array('lcp_url' => $lcp_url));

		return false;
	}

	/**
	 * Check if LCP URL is from the allowed origin
	 *
	 * @param string $lcp_url
	 *
	 * @return bool True if the URL origin is the site host or an explicitly allowed CDN host.
	 */
	private function is_allowed_lcp_origin($lcp_url): bool {
		$lcp_host = wp_parse_url($lcp_url, PHP_URL_HOST);

		// Relative URL — always safe
		if (empty($lcp_host)) {
			return true;
		}

		$site_host = wp_parse_url(home_url(), PHP_URL_HOST);

		// Same host as site
		if ($lcp_host === $site_host) {
			return true;
		}

		// Allow CDN/allowed domains via filter
		$allowed_hosts = apply_filters('wpo_lcp_allowed_preload_hosts', array());

		return in_array($lcp_host, $allowed_hosts, true);
	}

	/**
	 * Check if LCP URL has allowed extension
	 * if no extension, this means it could be CDN url, so we will return true in that case.
	 *
	 * @param  string $lcp_url
	 * @return bool True if the extension is allowed or absent (extensionless CDN URLs are permitted).
	 */
	private function is_allowed_lcp_extension($lcp_url): bool {
		$allowed = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'svg', 'woff', 'woff2', 'ttf', 'otf');

		$path = (string) wp_parse_url($lcp_url, PHP_URL_PATH);
		$ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));

		// No extension — could be a valid image CDN URL; defer to origin check
		if ('' === $ext) {
			return true;
		}

		return in_array($ext, $allowed, true);
	}

	/**
	 * Check if a rate limit has been reached for a given key.
	 * Uses get_transient/set_transient to persist hit counts across requests.
	 *
	 * @param string $key        Unique identifier for the rate limit (e.g. md5 of page URL).
	 * @param int    $max_hits   Maximum number of allowed hits within the window.
	 * @param int    $window_sec Time window in seconds.
	 *
	 * @return bool True if rate limit is reached, false if request is allowed.
	 */
	private function is_rate_limited(string $key, int $max_hits, int $window_sec): bool {
		$transient_key = 'wpo_lcp_rl_' . $key;

		$cached = get_transient($transient_key);
		$hits   = is_scalar($cached) ? (int) $cached : 0;

		if ($hits >= $max_hits) {
			return true;
		}

		set_transient($transient_key, $hits + 1, $window_sec);

		return false;
	}
}
endif;
