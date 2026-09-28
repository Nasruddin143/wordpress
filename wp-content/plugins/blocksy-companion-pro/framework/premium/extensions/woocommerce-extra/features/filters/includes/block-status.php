<?php

namespace Blocksy\Extensions\WoocommerceExtra;

if (! defined('ABSPATH')) {
	exit;
}

class StatusBlock {
	public function __construct() {
		add_action('init', [$this, 'blocksy_status_filter_block']);

		add_filter(
			'blocksy:block-editor:localized_data',
			function ($data) {
				$options = blocksy_companion_akg(
					'options',
					blocksy_companion_get_variables_from_file(
						dirname(__FILE__) . '/options/status.php',
						['options' => []]
					)
				);

				$options_name = 'status_filter';

				$data[$options_name] = $options;

				return $data;
			}
		);
    }

	public function blocksy_status_filter_block() {
		register_block_type(BLOCKSY_PATH . '/framework/premium/extensions/woocommerce-extra/static/js/blocks/woocommerce-filters/status-filter/block.json', [
			'render_callback' => function ($attributes, $content, $block) {
				if (
					! is_woocommerce()
					&&
					! wp_doing_ajax()
					||
					is_singular()
				) {
					return '';
				}

				$status_values = [];

				foreach (StatusFilter::get_status_options() as $key => $status) {
					$status_values[] = [
						'id' => $key,
						'enabled' => true,
					];
				}

				$attributes = wp_parse_args($attributes, [
                    'layout' => 'list',
					'showCounters' => true,
                    'showCheckboxes' => true,
					'showResetButton' => false,
                    'statuses' => $status_values,
					'relation' => 'or',
				]);

				$filter = Filters::get_filter_instance('status_filter');

				$presenter = new FilterPresenter($filter);
				return $presenter->render($attributes);
			},
		]);
	}
}
