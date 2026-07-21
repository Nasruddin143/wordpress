<?php

/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package kt
 */
?>

<section class="no-results not-found">

	<header class="page-header">
		<h2 class="page-title">
			<?php
			echo esc_html(
				function_exists('__')
					? __('Nothing Found')
					: __('Nothing Found', 'king_tailors')
			);
			?>
		</h2>
	</header>

	<div class="page-content">

		<?php if (is_home() && current_user_can('publish_posts')) : ?>

			<p>
				<?php
				printf(
					wp_kses(
						function_exists('__')
							? __('Ready to publish your first post? <a href="%1$s">Get started here</a>.')
							: __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'king_tailors'),
						[
							'a' => ['href' => []],
						]
					),
					esc_url(admin_url('post-new.php'))
				);
				?>
			</p>

		<?php elseif (is_search()) : ?>

			<p>
				<?php
				echo esc_html(
					function_exists('__')
						? __('Sorry, but nothing matched your search terms. Please try again with some different keywords.')
						: __('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'king_tailors')
				);
				?>
			</p>

			<?php get_search_form(); ?>

		<?php else : ?>

			<p>
				<?php
				echo esc_html(
					function_exists('__')
						? __('It seems we can’t find what you’re looking for. Perhaps searching can help.')
						: __('It seems we can’t find what you’re looking for. Perhaps searching can help.', 'king_tailors')
				);
				?>
			</p>

			<?php get_search_form(); ?>

		<?php endif; ?>

	</div>

</section>