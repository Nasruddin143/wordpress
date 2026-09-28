<?php

if (! defined('ABSPATH')) {
	exit;
}

$class = 'ct-header-divider';

blocksy_companion_html_tag_e(
	'div',
	array_merge(
		[
			'class' => $class
		],
		$attr
	),
	''
);
