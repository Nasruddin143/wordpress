<?php

/**
 * Reusable page banner template
 *
 * @package king_tailors
 */

// Exclude front page and blog page
// if (is_front_page() || is_home()) {
// 	return;
// }

// Exclude FrontPage Only
if (is_front_page()) {
	return;
}

$custom_header = get_custom_header();

if (empty($custom_header->attachment_id)) {
	return;
}

$header_id = $custom_header->attachment_id;
$image_src = wp_get_attachment_image_url($header_id, 'kt-site-banner');

if (!$image_src) {
	return;
}
?>

<div id="main-content" class="kt-page-banner" role="banner">

	<div class="banner-container page-banner">

		<span class="banner-overlay" aria-hidden="true"></span>

		<?php
		echo wp_get_attachment_image(
			$header_id,
			'kt-site-banner',
			false,
			array(
				'class' => 'page-banner-img img-fluid',
				'alt' => __('Custom header image', 'kt'),
				'title' => __('Custom header image', 'kt'),
				'fetchpriority' => 'high',
				'loading' => 'eager',
			)
		);
		?>

	</div>

</div>