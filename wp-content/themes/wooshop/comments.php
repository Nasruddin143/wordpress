<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WooShop
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
	return;
}
?>

<div id="comments" class="comments-area mt-5 bg-light p-5">

	<?php if (have_comments()): ?>

		<h3 class="comments-title mb-4">

			<?php

			$count = get_comments_number();

			printf(

				_n(
					'%s Comment',
					'%s Comments',
					$count,
					'wooshop'
				),

				number_format_i18n($count)

			);

			?>

		</h3>

		<ol class="comment-list list-unstyled">

			<?php

			wp_list_comments(array(

				'style' => 'ol',
				'avatar_size' => 70,
				'short_ping' => true,
				'callback' => 'wooshop_comment_callback',

			));

			?>

		</ol>

		<?php the_comments_navigation(); ?>

		<?php if (!comments_open()): ?>

			<div class="alert alert-warning mt-4">

				<?php esc_html_e('Comments are closed.', 'wooshop'); ?>

			</div>

		<?php endif; ?>

	<?php endif; ?>


	<?php

	comment_form(array(

		'class_form' => 'comment-form row g-3 mt-4',

		'class_submit' => 'btn btn-primary',

		'title_reply' => __('Leave a Comment', 'wooshop'),

		'title_reply_before' => '<h3 class="mb-4">',

		'title_reply_after' => '</h3>',

		'label_submit' => __('Post Comment', 'wooshop'),

		'comment_notes_before' => '',

		'comment_notes_after' => '',

		'fields' => array(

			'author' => '

            <div class="col-md-6">

                <label class="form-label">

                    ' . esc_html__('Name', 'wooshop') . '

                </label>

                <input

                    id="author"

                    name="author"

                    type="text"

                    class="form-control"

                    required>

            </div>

            ',

			'email' => '

            <div class="col-md-6">

                <label class="form-label">

                    ' . esc_html__('Email', 'wooshop') . '

                </label>

                <input

                    id="email"

                    name="email"

                    type="email"

                    class="form-control"

                    required>

            </div>

            ',

			'url' => '

            <div class="col-12">

                <label class="form-label">

                    ' . esc_html__('Website', 'wooshop') . '

                </label>

                <input

                    id="url"

                    name="url"

                    type="url"

                    class="form-control">

            </div>

            ',

		),

		'comment_field' => '

        <div class="col-12">

            <label class="form-label">

                ' . esc_html__('Comment', 'wooshop') . '

            </label>

            <textarea

                id="comment"

                name="comment"

                rows="6"

                class="form-control"

                required></textarea>

        </div>

        ',

	));

	?>

</div>