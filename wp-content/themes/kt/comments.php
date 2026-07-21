<?php
/**
 * The template for displaying comments
 *
 * @package king_tailors
 */

defined('ABSPATH') || exit;

if (post_password_required()) {
	return;
}
?>

<div id="comments" class="comments-area mt-5">

	<?php if (have_comments()): ?>

		<h2 class="comments-title h4 mb-4">
			<?php
			$comment_count = get_comments_number();
			if ('1' === $comment_count) {
				printf(
					esc_html__('One thought on “%s”', 'kt'),
					'<span>' . esc_html(get_the_title()) . '</span>'
				);
			} else {
				printf(
					esc_html(_nx(
						'%1$s thought on “%2$s”',
						'%1$s thoughts on “%2$s”',
						$comment_count,
						'comments title',
						'kt'
					)),
					number_format_i18n($comment_count),
					'<span>' . esc_html(get_the_title()) . '</span>'
				);
			}
			?>
		</h2>

		<?php the_comments_navigation(); ?>

		<ol class="comment-list list-unstyled">
			<?php
			wp_list_comments([
				'style' => 'ol',
				'short_ping' => true,
				'avatar_size' => 64,
				'callback' => null,
			]);
			?>
		</ol>

		<?php the_comments_navigation(); ?>

		<?php if (!comments_open()): ?>
			<p class="no-comments text-muted">
				<?php esc_html_e('Comments are closed.', 'kt'); ?>
			</p>
		<?php endif; ?>

	<?php endif; ?>

	<?php
	//  COMMENT FORM 
	comment_form([
		'class_form' => 'comment-form mt-4',
		'class_submit' => 'btn btn-primary',
		'title_reply' => esc_html__('Leave a Comment', 'kt'),
		'title_reply_before' => '<h3 class="comment-reply-title h5 mb-3">',
		'title_reply_after' => '</h3>',
		'comment_notes_before' => '<p class="text-muted small">',
		'comment_notes_after' => '</p>',
		'fields' => [
			'author' =>
				'<div class="mb-3">
					<label class="form-label">' . esc_html__('Name', 'kt') . '</label>
					<input class="form-control" name="author" type="text" required>
				</div>',
			'email' =>
				'<div class="mb-3">
					<label class="form-label">' . esc_html__('Email', 'kt') . '</label>
					<input class="form-control" name="email" type="email" required>
				</div>',
		],
		'comment_field' =>
			'<div class="mb-3">
				<label class="form-label">' . esc_html__('Comment', 'kt') . '</label>
				<textarea class="form-control" name="comment" rows="4" required></textarea>
			</div>',
	]);
	?>

</div>