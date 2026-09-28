<?php

namespace Blocksy\Extensions\WoocommerceExtra;

if (! defined('ABSPATH')) {
	exit;
}

$statuses = StatusFilter::get_status_options();

$status_values = [];
$status_options = [];

foreach ($statuses as $key => $status) {
	$status_values[] = [
		'id' => $key,
		'enabled' => true,
	];

	$status_options[$key] = ['label' => $status];
}

$options = [
	'statuses' => [
		'label' => __('Statuses', 'blocksy-companion'),
		'type' => 'ct-layers',
		'divider' => 'top:full',
		'manageable' => true,
		'value' => $status_values,
		'settings' => $status_options
	],

	blocksy_rand_md5() => [
		'type' => 'ct-condition',
		'condition' => [
			'any' => [
				'statuses:array-ids:on_sale:enabled' => '!no',
				'statuses:array-ids:featured:enabled' => '!no',
			]
		],
		'options' => [

			'relation' => [
				'label' => __('Matching Logic', 'blocksy-companion'),
				'type' => 'ct-radio',
				'value' => 'or',
				'view' => 'text',
				'design' => 'block',
				'divider' => 'top:full',
				'choices' => [
					'or' => __('Any', 'blocksy-companion'),
					'and' => __('All', 'blocksy-companion'),
				],
				'desc' => __('Choose whether products match any status or satisfy all compatible conditions.', 'blocksy-companion'),
			],

		],
	],
];
