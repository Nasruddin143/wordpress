<?php

namespace Blocksy;

if (! defined('ABSPATH')) {
	exit;
}

class MediaVideo {
	public function __construct() {
		add_action(
			'admin_enqueue_scripts',
			function () {
				$options = blocksy_companion_get_options(
					dirname(__FILE__) . '/options.php',
					[],
					false
				);

				wp_localize_script(
					'blocksy-admin-scripts',
					'videoOptions',
					[
						'options' => $options,
					]
				);
			},
			999
		);

		add_action('wp_ajax_blocksy_update_video_meta_fields', function () {
			if (
				// phpcs:ignore WordPress.Security.NonceVerification.Missing
				! isset($_POST['attachment_id'])
				||
				// phpcs:ignore WordPress.Security.NonceVerification.Missing
				! isset($_POST['attachment_video'])
			) {
				wp_send_json_error();
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$attachment_id = absint($_POST['attachment_id']);

			// Per object check: the blanket 'edit_posts' capability would let
			// any contributor write meta on attachments (and posts) they don't
			// own.
			if (! current_user_can('edit_post', $attachment_id)) {
				wp_send_json_error();
			}

			check_ajax_referer('ct-ajax-nonce', 'nonce');

			$value = json_decode(
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				wp_unslash($_POST['attachment_video']),
				true
			);

			if (! is_array($value)) {
				$value = [];
			}

			// update_post_meta() never runs kses on its own, and these values
			// are read back by blocksy_companion_theme_functions()->blocksy_get_post_options() across the theme
			// views and the dynamic CSS generator.
			$value = blocksy_companion_theme_functions()->blocksy_sanitize_post_meta_options($value);

			// The theme helper wasn't there to process the value, so there is
			// nothing safe to store. Refusing beats writing the raw input, and
			// beats writing the sentinel over whatever is there already.
			if ($value === ThemeFunctions::$NON_EXISTING_FUNCTION) {
				wp_send_json_error();
			}

			update_post_meta(
				$attachment_id,
				'blocksy_post_meta_options',
				$value
			);

			delete_post_meta($attachment_id, 'blocksy_media_video');

			$maybe_new_meta = blocksy_companion_theme_functions()->blocksy_get_post_options($attachment_id);

			if ($maybe_new_meta) {
				wp_send_json_success(
					[
						'meta' => $maybe_new_meta
					]
				);
			}
		});

		add_action('wp_ajax_blocksy_get_video_meta_fields', function () {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if (! isset($_GET['attachment_id'])) {
				wp_send_json_error();
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$attachment_id = absint($_GET['attachment_id']);

			// Without this any logged in user could read the meta options of
			// any post id, not just of the attachments they can edit.
			if (! current_user_can('edit_post', $attachment_id)) {
				wp_send_json_error();
			}

			check_ajax_referer('ct-ajax-nonce', 'nonce');

			$maybe_old_meta = get_post_meta(
				$attachment_id,
				'blocksy_media_video',
				true
			);

			if ($maybe_old_meta) {
				if (
					strpos($maybe_old_meta, 'youtube') !== false
					||
					strpos($maybe_old_meta, 'youtu.be') !== false
				) {
					wp_send_json_success([
						'meta' => [
							'media_video_youtube_url' => $maybe_old_meta,
							'media_video_source' => 'youtube'
						],
					]);

					return;
				}

				if (strpos($maybe_old_meta, 'vimeo') !== false) {
					wp_send_json_success([
						'meta' => [
							'media_video_vimeo_url' => $maybe_old_meta,
							'media_video_source' => 'vimeo'
						]
					]);

					return;
				}

				$maybe_old_attachment = attachment_url_to_postid($maybe_old_meta);

				if ($maybe_old_attachment) {
					wp_send_json_success( [
						'meta' => [
							'media_video_upload' => $maybe_old_meta,
							'media_video_source' => 'upload'
						],
					]);

					return;
				}
			}

			$maybe_new_meta = blocksy_companion_theme_functions()->blocksy_get_post_options($attachment_id);

			if ($maybe_new_meta) {
				wp_send_json_success([
					'meta' => $maybe_new_meta,
				]);
			}

			return;
		});
	}
}
