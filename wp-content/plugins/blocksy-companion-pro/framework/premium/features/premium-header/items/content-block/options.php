<?php

if (! defined('ABSPATH')) {
	exit;
}

$options = [
	blocksy_rand_md5() => [
		'title' => __('General', 'blocksy-companion'),
		'type' => 'tab',
		'options' => [
			empty(blocksy_companion_get_content_blocks())
				? [

					blocksy_rand_md5() => [
						'type' => 'html',
						'label' => __('Select Content Block', 'blocksy-companion'),
						'value' => '',
						'design' => 'block',
						'html' => '<a href="' . admin_url('/edit.php?post_type=ct_content_block') .'" target="_blank" class="button" style="width: 100%; text-align: center;">' . __('Create a new content Block/Hook', 'blocksy-companion') . '</a>',
					],

				] : [
					'hook_id' => [
						'label' => __('Select Content Block', 'blocksy-companion'),
						'type' => 'ct-select',
						'value' => '',
						'search' => true,
						'defaultToFirstItem' => false,
						'placeholder' => __('None', 'blocksy-companion'),
						'choices' => blocksy_ordered_keys(
							blocksy_companion_get_content_blocks()
						),
					],
				],

			blocksy_rand_md5() => [
				'type' => 'ct-condition',
				'condition' => [ 'row' => 'offcanvas' ],
				'options' => [
					'content_width' => [
						'label' => __( 'Content Width', 'blocksy-companion' ),
						'type' => 'ct-radio',
						'view' => 'text',
						'design' => 'block',
						'divider' => 'top',
						'value' => 'auto',
						'setting' => [ 'transport' => 'postMessage' ],
						'choices' => [
							'auto' => __( 'Auto', 'blocksy-companion' ),
							'full' => __( 'Full Width', 'blocksy-companion' ),
						],
					],
				],
			],
		]
	],

	blocksy_rand_md5() => [
		'title' => __('Design', 'blocksy-companion'),
		'type' => 'tab',
		'options' => [
			'margin' => [
				'label' => __( 'Margin', 'blocksy-companion' ),
				'type' => 'ct-spacing',
				'divider' => 'top',
				'value' => blocksy_spacing_value(),
				'responsive' => true
			],
		],
	],
];
