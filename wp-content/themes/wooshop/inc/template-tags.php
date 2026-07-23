<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WooShop
 */

if (!function_exists('wooshop_posted_on')):
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function wooshop_posted_on()
	{
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if (get_the_time('U') !== get_the_modified_time('U')) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr(get_the_date(DATE_W3C)),
			esc_html(get_the_date()),
			esc_attr(get_the_modified_date(DATE_W3C)),
			esc_html(get_the_modified_date())
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x('Posted on %s', 'post date', 'wooshop'),
			'<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if (!function_exists('wooshop_posted_by')):
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function wooshop_posted_by()
	{
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x('by %s', 'post author', 'wooshop'),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if (!function_exists('wooshop_entry_footer')):
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function wooshop_entry_footer()
	{
		// Hide category and tag text for pages.
		if ('post' === get_post_type()) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list(esc_html__(', ', 'wooshop'));
			if ($categories_list) {
				/* translators: 1: list of categories. */
				printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'wooshop') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'wooshop'));
			if ($tags_list) {
				/* translators: 1: list of tags. */
				printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'wooshop') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'wooshop'),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post(get_the_title())
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__('Edit <span class="screen-reader-text">%s</span>', 'wooshop'),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post(get_the_title())
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if (!function_exists('wooshop_post_thumbnail')):
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function wooshop_post_thumbnail()
	{
		if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
			return;
		}

		if (is_singular()):
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail('post-thumbnail', array('class' => 'attachment-post-thumbnail size-post-thumbnail wp-post-image img-fluid w-100')); ?>
			</div><!-- .post-thumbnail -->

		<?php else: ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
				the_post_thumbnail(
					'post-thumbnail',
					array(
						'alt' => the_title_attribute(
							array(
								'echo' => false,
							)
						),
						'class' => 'attachment-post-thumbnail size-post-thumbnail wp-post-image img-fluid w-100'
					)
				);
				?>
			</a>

			<?php
		endif; // End is_singular().
	}
endif;

if (!function_exists('wp_body_open')):
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open()
	{
		do_action('wp_body_open');
	}
endif;



/**
 * Display post meta.
 *
 * Date | Author | Comments | Reading Time | Categories
 */

if (!function_exists('wooshop_post_meta')):

	function wooshop_post_meta()
	{
		// Date
		$date = sprintf(
			'<a href="%1$s" class="text-body-tertiary text-decoration-none" rel="bookmark"><time class="entry-date published updated" datetime="%2$s">%3$s</time></a>',
			esc_url(get_permalink()),
			esc_attr(get_the_date(DATE_W3C)),
			esc_html(get_the_date())
		);

		// Author
		$author = sprintf(
			'<a href="%1$s" class="text-body-tertiary text-decoration-none">%2$s</a>',
			esc_url(get_author_posts_url(get_the_author_meta('ID'))),
			esc_html(get_the_author())
		);

		// Comments
		if (comments_open() || get_comments_number()) {

			ob_start();

			comments_popup_link(
				esc_html__('0 Comments', 'wooshop'),
				esc_html__('1 Comment', 'wooshop'),
				esc_html__('% Comments', 'wooshop'),
				'text-body-tertiary text-decoration-none',
				''
			);

			$comments = ob_get_clean();

		} else {
			$comments = '';
		}

		// Reading Time
		$content = wp_strip_all_tags(get_post_field('post_content', get_the_ID()));

		$words = str_word_count($content);

		$reading_time = max(1, ceil($words / 200));

		// Categories
		$categories = get_the_category_list(', ');

		?>

		<div class="entry-meta hstack gap-3 mb-2 text-body-tertiary">

			<span class="meta-date">

				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"
					class="main-grid-item-icon me-2" fill="none" stroke="currentColor" stroke-linecap="round"
					stroke-linejoin="round" stroke-width="2">
					<rect height="18" rx="2" ry="2" width="18" x="3" y="4" />
					<line x1="16" x2="16" y1="2" y2="6" />
					<line x1="8" x2="8" y1="2" y2="6" />
					<line x1="3" x2="21" y1="10" y2="10" />
				</svg>

				<?php echo $date; ?>

			</span>

			<span class="meta-author">

				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"
					class="main-grid-item-icon me-2" fill="none" stroke="currentColor" stroke-linecap="round"
					stroke-linejoin="round" stroke-width="2">
					<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
					<circle cx="12" cy="7" r="4" />
				</svg>

				<?php echo $author; ?>

			</span>

			<?php if ($comments): ?>

				<span class="meta-comments">

					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"
						class="main-grid-item-icon me-2" fill="none" stroke="currentColor" stroke-linecap="round"
						stroke-linejoin="round" stroke-width="2">
						<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
					</svg>

					<?php echo $comments; ?>

				</span>

			<?php endif; ?>

			<span class="meta-reading-time text-body-tertiary">


				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"
					class="main-grid-item-icon me-2" fill="none" stroke="currentColor" stroke-linecap="round"
					stroke-linejoin="round" stroke-width="2">
					<circle cx="12" cy="12" r="10" />
					<polyline points="12 6 12 12 16 14" />
				</svg>


				<?php
				printf(
					esc_html__('%d min read', 'wooshop'),
					absint($reading_time)
				);
				?>

			</span>

			<?php if ($categories): ?>

				<span class="meta-category">

					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"
						class="main-grid-item-icon me-2" fill="none" stroke="currentColor" stroke-linecap="round"
						stroke-linejoin="round" stroke-width="2">
						<circle cx="12" cy="8" r="7" />
						<polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88" />
					</svg>


					<?php 
					$categories = str_replace('<a ', '<a class="text-body-tertiary text-decoration-none" ', $categories);
					echo $categories; 
					?>

				</span>

			<?php endif; ?>

		</div>

		<?php

	}

endif;