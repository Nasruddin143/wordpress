<?php

namespace Blocksy\Extensions\WoocommerceExtra;

if (! defined('ABSPATH')) {
	exit;
}

class FiltersCrawlProtection {
	public function __construct() {
		add_filter('robots_txt', [$this, 'append_rules'], 100000);
		add_action('wp_ajax_blocksy_filters_crawl_status', [$this, 'ajax_status']);
	}

	public function get_rules() {
		/**
		 * Filters whether Shop Filters adds automatic crawl restrictions.
		 *
		 * Returning false also disables the dashboard check and notice.
		 *
		 * @since 2.1.58
		 *
		 * @param bool $enabled Whether automatic crawl restrictions are enabled. Default true.
		 */
		$enabled = apply_filters('blocksy:ext:woocommerce-extra:filters:has-crawl-protection', true);

		if (! $enabled) {
			return [];
		}

		$params = [];

		foreach (Filters::get_filter_instance() as $filter) {
			$params = array_merge($params, $filter->get_query_params());
		}

		$params = array_unique(array_map(function ($param) {
			return strpos($param, AttributesFilter::$prefix) === 0
				? AttributesFilter::$prefix
				: $param . '=';
		}, $params));

		$rules = [];

		foreach ($params as $param) {
			$rules[] = '/*?' . $param;
			$rules[] = '/*?*&' . $param;
		}

		return $rules;
	}

	public function append_rules($output) {
		$missing = $this->get_missing_rules($output);

		if (! $missing) {
			return $output;
		}

		return rtrim($output) . "\n\n" . $missing . "\n";
	}

	public function get_missing_rules($output) {
		$groups = $this->parse_groups($output);
		$rules = $this->get_rules();
		$grouped_rules = [];

		foreach ($groups as $agent => $directives) {
			$group_rules = [];

			foreach ($rules as $rule) {
				if ($this->is_covered($rule, $directives)) {
					continue;
				}

				$group_rules[] = 'Disallow: ' . $rule;
			}

			if ($group_rules) {
				$grouped_rules[implode("\n", $group_rules)][] = 'User-agent: ' . $agent;
			}
		}

		$missing = [];

		foreach ($grouped_rules as $group_rules => $agents) {
			$missing[] = implode("\n", $agents) . "\n" . $group_rules;
		}

		return $missing
			? "# Blocksy - prevent crawling of URLs from Shop Filters extension\n" . implode("\n\n", $missing)
			: '';
	}

	public function get_status() {
		$home = wp_parse_url(home_url('/'));
		$url = $home['scheme'] . '://' . $home['host'];

		if (isset($home['port'])) {
			$url .= ':' . $home['port'];
		}

		$url .= '/robots.txt';

		$result = [
			'status' => 'unavailable',
			'url' => $url,
			'rules' => ''
		];

		if (! $this->get_rules()) {
			$result['status'] = 'disabled';
			return $result;
		}

		$response = wp_safe_remote_get($url, [
			'timeout' => 8,
			'redirection' => 3,
			'limit_response_size' => 512001,
			'headers' => ['Cache-Control' => 'no-cache']
		]);

		if (is_wp_error($response)) {
			return $result;
		}

		$code = wp_remote_retrieve_response_code($response);
		$body = wp_remote_retrieve_body($response);

		if ($code === 404) {
			$body = '';
		} elseif (
			$code !== 200
			|| strlen($body) > 512000
			|| stripos($body, '<html') !== false
			|| stripos($body, '<!doctype') !== false
		) {
			return $result;
		}

		$result['rules'] = $this->get_missing_rules($body);
		$result['status'] = $result['rules'] ? 'missing' : 'protected';

		return $result;
	}

	public function ajax_status() {
		$capability = blocksy_companion_get_capabilities()->get_wp_capability_by('dashboard');

		if (! current_user_can($capability)) {
			wp_send_json_error([], 403);
		}

		if (! check_ajax_referer('ct-dashboard', 'nonce', false)) {
			wp_send_json_error([], 403);
		}

		wp_send_json_success($this->get_status());
	}

	private function parse_groups($output) {
		$groups = ['*' => ['allow' => [], 'disallow' => []]];
		$agents = [];
		$has_rules = false;

		foreach (preg_split('/\r\n|\r|\n/', $output) as $line) {
			$line = trim(explode('#', $line, 2)[0]);
			$parts = explode(':', $line, 2);

			if (count($parts) !== 2) {
				continue;
			}

			$field = strtolower(trim($parts[0]));
			$value = trim($parts[1]);

			if ($field === 'user-agent') {
				if ($has_rules) {
					$agents = [];
					$has_rules = false;
				}

				$value = strtolower($value);

				if ($value === '') {
					continue;
				}

				$agents[] = $value;

				if (! isset($groups[$value])) {
					$groups[$value] = ['allow' => [], 'disallow' => []];
				}

				continue;
			}

			if (! in_array($field, ['allow', 'disallow'], true)) {
				continue;
			}

			$has_rules = true;

			foreach ($agents as $agent) {
				if ($value !== '') {
					$groups[$agent][$field][] = $value;
				}
			}
		}

		return $groups;
	}

	private function is_covered($rule, $directives) {
		if (in_array($rule, $directives['disallow'], true)) {
			return true;
		}

		if ($directives['allow']) {
			return false;
		}

		foreach ($directives['disallow'] as $existing) {
			if (
				strpos($existing, '$') === false
				&& preg_match(
					'~^' . str_replace('\\*', '.*', preg_quote($existing, '~')) . '~',
					$rule
				)
			) {
				return true;
			}
		}

		return false;
	}
}
