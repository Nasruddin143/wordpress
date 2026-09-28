<?php

if (! defined('ABSPATH')) {
	exit;
}

if (!isset($device)) {
	$device = 'desktop';
}

$class = 'ct-header-content-block';

$hook_id = blocksy_translate_post_id(blocksy_companion_akg('hook_id', $atts, ''));

$wrapper_attributes = [
	'class' => $class,
	'data-hook-id' => $hook_id,
];

if (isset($row_id) && $row_id === 'offcanvas') {
	$content_width = blocksy_companion_akg('content_width', $atts, 'auto');

	if ($content_width !== 'full') {
		$content_width = 'auto';
	}

	$wrapper_attributes['data-content-width'] = $content_width;
}

$content = '';

if (
	$hook_id
	&&
	\Blocksy\Plugin::instance()
		->premium
		->content_blocks
		->is_hook_eligible_for_display($hook_id, [
			'match_conditions' => false
		])
) {
	$content = \Blocksy\Plugin::instance()
		->premium
		->content_blocks
		->output_hook($hook_id, [
			'layout' => false
		]);
}

if (! empty($content)) {
	blocksy_companion_html_tag_e(
		'div',
		array_merge(
			$wrapper_attributes,
			$attr
		),
		$content
	);
}
