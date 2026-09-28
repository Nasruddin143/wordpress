<?php
if (!defined('ABSPATH')) die('No direct access allowed');

if (!class_exists('WP_Optimize_Host_Google_Fonts')) :
class WP_Optimize_Host_Google_Fonts {

	/**
	 * Directory to store cached fonts
	 *
	 * @var string
	 */
	const WPO_CACHE_FONTS_DIR = WP_CONTENT_DIR . '/cache/wpo-fonts';
	
	/**
	 * URL to the stored cached fonts
	 *
	 * @var string
	 */
	const WPO_CACHE_FONTS_URL = WP_CONTENT_URL . '/cache/wpo-fonts';

	/**
	 * WP_Optimize_Host_Google_Fonts constructor.
	 *
	 * @return void
	 */
	private function __construct() {
	}

	/**
	 * Instance of WP_Optimize_Host_Google_Fonts
	 *
	 * @return WP_Optimize_Host_Google_Fonts
	 */
	public static function instance() {
		static $_instance = null;
		if (null === $_instance) {
			$_instance = new self();
		}
		return $_instance;
	}

	/**
	 * Parses <link href=""> and <style> tags and updates Google Font URLs to local versions.
	 *
	 * @param string $html
	 * @return string
	 */
	public function update_gfont_urls_in_html(string $html): string {
		if (!WP_Optimize_Utils::is_valid_html($html)) return $html;

		$dom = WP_Optimize_Utils::get_simple_html_dom_object($html);

		if (false === $dom) return $html;

		foreach ($dom->find('link') as $link) {
			$href = $link->getAttribute('href');
			// clean encoded string from simple_html_dom
			$href = html_entity_decode($href);

			if (preg_match('/fonts\.googleapis\.com\/css/i', $href)) {
				$local_url = $this->get_local_gfont_css_url($href);
				$link->setAttribute('href', $local_url);
			}
		}

		foreach ($dom->find('style') as $style) {
			$style_text = $style->innertext;

			$new_style_text = preg_replace_callback(
				'/@import\s+(url\()?[\'"]?((https?\:)?\/\/fonts\.googleapis\.com\/css[^\'")]+)[\'"]?\)?/i',
				array($this, 'replace_css_gfonts_callback'),
				$style_text
			);

			$new_style_text = $this->replace_gfont_urls_with_local($new_style_text);

			if ($new_style_text !== $style_text) $style->innertext = $new_style_text;
		}

		return $dom->save();
	}

	/**
	 * Callback used with preg_replace_callback() to replace google fonts links to local versions.
	 *
	 * @param array $matches
	 * @return string
	 */
	private function replace_css_gfonts_callback(array $matches): string {
		if (empty($matches[2])) return $matches[0];
		$original_url = $matches[2];
		$local_url = $this->get_local_gfont_css_url($original_url);

		return "@import url('$local_url')";
	}

	/**
	 * For the provided Google Font URL, return the corresponding local Google Font URL.
	 *
	 * @param string $url remote google font css url
	 * @return string
	 */
	private function get_local_gfont_css_url(string $url): string {
		$cache_file_name = $this->get_local_gfont_css_filename($url);
		// Return the original URL when the cache file cannot be generated
		if (false === $cache_file_name) return $url;

		if (is_file(self::WPO_CACHE_FONTS_DIR . '/' . $cache_file_name) || $this->download_and_cache_gfont_css_file($url)) {
			return self::WPO_CACHE_FONTS_URL . '/' . $cache_file_name;
		}

		return $url;
	}

	/**
	 * Replaces Google Fonts CSS content and downloads the files locally.
	 *
	 * @param string $css_content remote google font css url
	 * @return string
	 */
	public function replace_gfont_urls_with_local($css_content): string {
		$pattern = '/url\(([\'"]?https?:\/\/fonts\.gstatic\.com\/[^\)]+\.(eot|woff2|woff|ttf)?)[\'"]?\)/i';
		preg_match_all($pattern, $css_content, $matches);
		if (!empty($matches[1])) {
			foreach ($matches[1] as $font_url) {
				$font_url = trim($font_url, '\'"');
				$local_url = $this->download_font($font_url);
				if ($local_url !== $font_url) {
					$css_content = str_replace($font_url, $local_url, $css_content);
				}
			}
		}
	
		return $css_content;
	}

	/**
	 * Generates a cache filename for a Google Font URL.
	 *
	 * @param string $url The Google Font URL.
	 * @return string|false The generated cache filename, or false if the URL has no query parameters.
	 */
	private function get_local_gfont_css_filename(string $url) {
		$parsed_query = wp_parse_url($url, PHP_URL_QUERY);

		if (!$parsed_query) return false;

		$query_parts = explode('&', $parsed_query);
		$params = array();
		foreach ($query_parts as $query_part) {
			$params[] = explode('=', $query_part, 2);
		}

		if (empty($params)) return false;
		
		usort($params, function($a, $b) {
			return strcmp($a[0], $b[0]);
		});

		$filename = '';
		foreach ($params as $item) {
			$name_value = $item[0];
			if (isset($item[1])) $name_value .= '-'.$item[1];
			$filename .= preg_replace('/[^0-9a-z\-]/i', '-', $name_value) . '-';
		}

		// Trimming filename if it exceeds 240 characters due to filesystem limitations
		if (strlen($filename) > 240) {
			$filename = substr($filename, 0, 199) . '-' . sha1($filename);
		}
		
		$filename = trim($filename, '-') . '.css';
		return $filename;
	}

	/**
	 * Downloads and cache font locally with updated fonts urls
	 *
	 * @param string $url remote google font css url
	 * @return bool
	 */
	private function download_and_cache_gfont_css_file(string $url): bool {
		$cache_file_name = $this->get_local_gfont_css_filename($url);
		// Return the original URL when the cache file cannot be generated
		if (false === $cache_file_name) return false;

		// Add protocol if missed
		if (!preg_match('/^https?/i', $url)) $url = 'https:' . $url;
		
		$css_content = WP_Optimize_Utils::get_remote_file_content($url, array(
			'user-agent'  => WP_Optimize_Utils::get_user_agent('gfont'),
			'httpversion' => '2.0',
		));

		if (false === $css_content) return false;

		// Don't process content without links to fonts
		if (false === $this->has_font_definitions($css_content)) return false;
		
		if (!wp_mkdir_p(self::WPO_CACHE_FONTS_DIR)) {
			return false;
		}

		$cache_file_path = self::WPO_CACHE_FONTS_DIR . '/' . $cache_file_name;
		$css_content = $this->replace_gfont_urls_with_local($css_content);
		if (!@file_put_contents($cache_file_path, $css_content)) return false; // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Fallback if we can't write the file
	
		$this->delete_cached_cache_stats();

		return true;
	}

	/**
	 * Checks if css content has fonts definitions
	 *
	 * @param string $css_content
	 * @return boolean
	 */
	private function has_font_definitions($css_content) {
		$pattern = '/url\([\'"]?[^\)]+\.(eot|woff2|woff|ttf)[\'"]?\)/i';
		return 1 === preg_match($pattern, $css_content);
	}

	/**
	 * Checks whether the font is already downloaded; if not, downloads it and returns the URL to the local file
	 *
	 * @param string $url
	 * @return string
	 */
	private function download_font(string $url): string {
		$url_path = wp_parse_url($url, PHP_URL_PATH);
		$relative_path = is_string($url_path) ? ltrim($url_path, '/') : '';
		$local_path = self::WPO_CACHE_FONTS_DIR . '/' . $relative_path;
		$local_url = preg_replace('/^https?\:/i', '', self::WPO_CACHE_FONTS_URL . '/' . $relative_path);

		// If already downloaded then just return local url
		if (is_file($local_path)) return $local_url;

		$semaphore_name = 'download_font_'.md5($url);
		$semaphore = new Updraft_Semaphore_3_0($semaphore_name, 30);
		if (false === $semaphore->lock(3)) return $url;

		// Double check after locking semaphore, if already downloaded then just return local url
		if (is_file($local_path)) {
			$semaphore->release();
			return $local_url;
		}

		$content = WP_Optimize_Utils::get_remote_file_content($url);

		if (false === $content) {
			$semaphore->release();
			return $url;
		}

		// Ensure the directory exists before saving the file
		$dir_path = dirname($local_path);
		if (!is_dir($dir_path) && !wp_mkdir_p($dir_path)) {
			$semaphore->release();
			return $url;
		}

		// Attempt to save the file locally
		if (false === @file_put_contents($local_path, $content)) { // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged -- Fallback if we can't write the file
			$semaphore->release();
			return $url;
		}

		$this->delete_cached_cache_stats();

		$semaphore->release();
		return $local_url;
	}

	/**
	 * Purge the cache
	 *
	 * @return bool
	 */
	public function purge() {

		$this->delete_cached_cache_stats();

		if (is_dir(self::WPO_CACHE_FONTS_DIR)) {
			return (wpo_delete_files(self::WPO_CACHE_FONTS_DIR));
		}

		return true;
	}

	/**
	 * Get the cache size and count
	 *
	 * @return string
	 */
	public function get_cache_stats(): string {
		$stats = $this->get_cached_cache_stats();
		if (false === $stats) {
			$stats = WP_Optimize_Utils::get_folder_stats(self::WPO_CACHE_FONTS_DIR);
			$this->set_cached_cache_stats($stats);
		}

		// Translators: %s: number of files.
		$translated_file_count = sprintf(_n('%s file', '%s files', $stats['file_count'], 'wp-optimize'), number_format_i18n($stats['file_count']));

		return WP_Optimize()->format_size($stats['size']) . ' ('.$translated_file_count.')';
	}

	/**
	 * Store cache stats in transients cache.
	 *
	 * @param array $stats
	 * @return void
	 */
	private function set_cached_cache_stats(array $stats) {
		set_transient('wpo_google_fonts_cache_stats', $stats, 3600);
	}

	/**
	 * Return cached cache stats.
	 *
	 * @return array|false
	 */
	private function get_cached_cache_stats() {
		return get_transient('wpo_google_fonts_cache_stats');
	}

	/**
	 * Delete cache stats from the cache.
	 *
	 * @return void
	 */
	private function delete_cached_cache_stats() {
		delete_transient('wpo_google_fonts_cache_stats');
	}
}

endif;
