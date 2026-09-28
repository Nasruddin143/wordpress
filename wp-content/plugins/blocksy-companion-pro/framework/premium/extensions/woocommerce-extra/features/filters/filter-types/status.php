<?php

namespace Blocksy\Extensions\WoocommerceExtra;

use \Automattic\WooCommerce\Internal\ProductAttributesLookup\Filterer;
use \Automattic\WooCommerce\Internal\ProductAttributesLookup\DataRegenerator;

class StatusFilter extends BaseFilter {
	private $product_ids_cache = [];

	static private $filter_param = 'filter_status';

	static private $relation_param = 'filter_status_relation';

	public function get_filter_id() {
		return 'status_filter';
	}

	public function get_reset_url($attributes = []) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (isset($_GET[self::$filter_param]) ) {
			return remove_query_arg(self::get_query_params());
		}

		return false;
	}

	public function render($attributes = []) {
		$relation = blocksy_companion_akg('relation', $attributes, 'or');

		$counts = $this->get_status_counts($relation);
		$labels = self::get_status_options();

		$relation_to_add = [];

		if ($relation === 'and') {
			$relation_to_add = [self::$relation_param => $relation];
		}

		if (
			! $attributes['statuses']
			||
			empty($attributes['statuses'])
		) {
			return '';
		}

		$statuses_html = [];

		foreach ($attributes['statuses'] as $status) {
			if (
				! isset($counts[$status['id']])
				||
				intval($counts[$status['id']]) === 0
				||
				! $status['enabled']
			) {
				continue;
			}

			$is_active = FilterPresenter::is_filter_active(
				self::$filter_param,
				$status['id']
			);

			$api_url = FiltersUtils::get_link_url(
				self::$filter_param,
				$status['id'],
				[
					'is_multiple' => true,
					'to_add' => $relation_to_add,
				]
			);

			$statuses_html[] = blocksy_companion_html_tag(
				'li',
				['class' => 'ct-filter-item'],
				blocksy_companion_html_tag(
					'div',
					[
						'class' => 'ct-filter-item-inner',
					],
					blocksy_companion_html_tag(
						'a',
						array_merge(
							[
								'href' => $api_url,
								'rel' => 'nofollow',
								'aria-label' => $labels[$status['id']],
								'data-key' => 'filter_stock_status',
								'data-value' => $status['id'],
							],
							(
								$is_active ? ['class' => 'active'] : []
							)
						),
						($attributes['showCheckboxes'] ? blocksy_companion_html_tag(
							'input',
							array_merge(
								[
									'type' => 'checkbox',
									'class' => 'ct-checkbox',
									'tabindex' => '-1',
									'name' => 'filter_stock_status_' . $status['id'],
									'aria-label' => $labels[$status['id']],
								],
								$is_active ? ['checked' => 'checked'] : []
							)
						) : '') .
						blocksy_companion_html_tag(
							'span',
							[
								'class' => 'ct-filter-label',
							],
							$labels[$status['id']]
						) .
						(
							$attributes['showCounters'] ? blocksy_companion_html_tag(
								'span',
								[
									'class' => 'ct-filter-count',
								],
								$counts[$status['id']]
							) : ''
						)
					)
				)
			);
		}

		if (empty($statuses_html)) {
			return '';
		}

		return blocksy_companion_html_tag(
			'div',
			[
				'class' => 'ct-status-filter',
			],
			blocksy_companion_html_tag(
				'ul',
				[
					'class' => 'ct-filter-widget',
					'data-display-type' => $attributes['layout'],
				],
				implode(
					'',
					$statuses_html
				)
			)
		);
	}

	public static function get_query_params() {
		return [self::$filter_param, self::$relation_param];
	}

	private function get_picked_relation($query_string) {
		return blocksy_companion_akg(self::$relation_param, $query_string, 'or');
	}

	public static function get_status_options() {
		$stock_statuses = wc_get_product_stock_status_options();

		$stock_labels = [
			'instock' => _x('In stock', 'product status filter', 'blocksy-companion'),
			'outofstock' => _x('Out of stock', 'product status filter', 'blocksy-companion'),
			'onbackorder' => _x('On backorder', 'product status filter', 'blocksy-companion'),
		];

		foreach ($stock_labels as $key => $label) {
			if (! isset($stock_statuses[$key])) {
				continue;
			}

			$stock_statuses[$key] = $label;
		}

		$statuses = array_merge(
			$stock_statuses,
			[
				'on_sale' => _x('On sale', 'product status filter', 'blocksy-companion'),
				'featured' => _x('Featured', 'product status filter', 'blocksy-companion'),
			]
		);

		/**
		 * Filters the labels of the statuses available in the status filter.
		 *
		 * @since 2.1.54
		 *
		 * @param array $statuses Status ids mapped to their labels.
		 */
		return apply_filters(
			'blocksy:ext:woocommerce-extra:filters:status:labels',
			$statuses
		);
	}

	public function get_applied_filters() {
		if (! $this->get_reset_url()) {
			return [];
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$values = explode(',', blocksy_companion_akg(self::$filter_param, $_GET, ''));

		$items = [];

		$labels = self::get_status_options();

		foreach ($values as $value) {
			if (! isset($labels[$value])) {
				continue;
			}

			if (! isset($labels[$value])) {
				continue;
			}

			$items[] = [
				'name' => $labels[$value],
				'value' => $value,
				'href' => remove_query_arg(self::$filter_param, $this->get_reset_url())
			];
		}

		return [
			'name' => __('Status', 'blocksy-companion'),
			'items' => $items
		];
	}

	public function get_product_ids_for_picked_statuses(
		$picked_statuses,
		$relation = 'or'
	) {
		$groups = $this->get_groups_for_picked_statuses($picked_statuses);

		if (empty($groups)) {
			return [];
		}

		$final_product_ids = null;

		foreach ($groups as $group_id => $group_statuses) {
			$group_product_ids = $this->get_product_ids_for_status_group(
				$group_id,
				$group_statuses
			);

			if (is_null($final_product_ids)) {
				$final_product_ids = $group_product_ids;

				continue;
			}

			if ($relation !== 'and') {
				$final_product_ids = array_merge(
					$final_product_ids,
					$group_product_ids
				);

				continue;
			}

			$final_product_ids = array_intersect(
				$final_product_ids,
				$group_product_ids
			);

			if (empty($final_product_ids)) {
				return [];
			}
		}

		return array_values(array_unique($final_product_ids));
	}

	public function wp_query_arg($query_string, $query_args, $reason) {
		$picked_statuses = $this->get_picked_statuses($query_string);

		if (empty($picked_statuses)) {
			return $query_args;
		}

		$query_args['post__in'] = FiltersUtils::constrain_post_in(
			$query_args,
			$this->get_product_ids_for_picked_statuses(
				$picked_statuses,
				$this->get_picked_relation($query_string)
			)
		);

		return $query_args;
	}

	public function get_status_counts($relation = 'or') {
		$product_ids = $this->get_product_ids_for_current_query(self::$filter_param);

		$all_statuses = self::get_status_options();

		$params = FiltersUtils::get_query_params();

		$picked_groups = [];

		if ($relation === 'and') {
			$picked_groups = $this->get_groups_for_picked_statuses(
				$this->get_picked_statuses($params['params'])
			);
		}

		foreach ($all_statuses as $key => $value) {
			$scoped_ids = array_intersect(
				$product_ids,
				$this->get_product_ids_for_picked_statuses([$key])
			);

			foreach ($picked_groups as $group_id => $group_statuses) {
				if (empty($scoped_ids)) {
					break;
				}

				if ($group_id === self::get_group_id_for_status($key)) {
					continue;
				}

				$scoped_ids = array_intersect(
					$scoped_ids,
					$this->get_product_ids_for_status_group(
						$group_id,
						$group_statuses
					)
				);
			}

			$all_statuses[$key] = count($scoped_ids);
		}

		return $all_statuses;
	}

	private static function get_group_id_for_status($status) {
		if (array_key_exists($status, wc_get_product_stock_status_options())) {
			return 'stock';
		}

		if (array_key_exists($status, self::get_status_options())) {
			return $status;
		}

		return false;
	}

	private function get_picked_statuses($query_string) {
		$value = blocksy_companion_akg(self::$filter_param, $query_string, '');

		if (empty($value)) {
			return [];
		}

		$picked_statuses = [];

		foreach (explode(',', $value) as $status) {
			if (self::get_group_id_for_status($status)) {
				$picked_statuses[] = $status;
			}
		}

		return $picked_statuses;
	}

	private function get_groups_for_picked_statuses($picked_statuses) {
		$groups = [];

		foreach ($picked_statuses as $status) {
			$group_id = self::get_group_id_for_status($status);

			if (! $group_id) {
				continue;
			}

			if (! isset($groups[$group_id])) {
				$groups[$group_id] = [];
			}

			$groups[$group_id][] = $status;
		}

		return $groups;
	}

	private function get_product_ids_for_status_group($group_id, $group_statuses) {
		if ($group_id === 'stock') {
			sort($group_statuses);

			$cache_key = md5(implode(',', $group_statuses));

			if (! isset($this->product_ids_cache[$cache_key])) {
				$products = new \WP_Query([
					'post_type' => 'product',
					'fields' => 'ids',
					'posts_per_page' => -1,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'cache_results' => false,
					'no_found_rows' => true,
					'nopaging' => true, // prevent "offset" issues
					'blocksy-woocommerce-extra-filters' => false,
					// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					'meta_query' => [
						[
							'key' => '_stock_status',
							'value' => $group_statuses,
							'compare' => 'IN'
						]
					]
				]);

				$this->product_ids_cache[$cache_key] = $products->posts;
			}

			return $this->product_ids_cache[$cache_key];
		}

		if ($group_id === 'on_sale') {
			if (! isset($this->product_ids_cache['on_sale'])) {
				$this->product_ids_cache['on_sale'] = blocksy_companion_get_product_ids_on_sale();
			}

			return $this->product_ids_cache['on_sale'];
		}

		if ($group_id === 'featured') {
			if (! isset($this->product_ids_cache['featured'])) {
				$this->product_ids_cache['featured'] = wc_get_featured_product_ids();
			}

			return $this->product_ids_cache['featured'];
		}

		return [];
	}

	private function get_product_ids_for_current_query($param = '') {
		$apply_filters = new ApplyFilters();

		$params = FiltersUtils::get_query_params();
		$filter_params = $this->get_query_params();

		$params = $params['params'];

		foreach ($filter_params as $param) {
			unset($params[$param]);
		}

		$products_query = $apply_filters->get_custom_query_for($params);

		return $products_query->posts;
	}
}

