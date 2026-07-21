<?php

/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package king_tailors
 */
use KT\Helpers\UI;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('non-front-page'); ?>>

	<!-- Page Header -->
	<header class="entry-header mb-4">
		<div class="d-sm-flex align-items-center justify-content-between mb-4">

			<!-- Breadcrumb -->
			<?php if (function_exists('bcn_display')): ?>

				<nav class="breadcrumb-nav mb-0" aria-label="breadcrumb">
					<?php bcn_display(); ?>
				</nav>

			<?php endif; ?>

			<!-- Social Share Buttons -->
			<?php echo UI::social_share(); ?>
		</div>


		<!-- Page Title -->
		<h1 class="entry-title fw-bold"><?php the_title(); ?></h1>
	</header>

	<div class="entry-content">
		<div class="content-image mb-4">
			<?php if (has_post_thumbnail()): ?>
				<?php the_post_thumbnail(
					'kt-page-banner',
					[
						'class' => 'img-fluid wp-post-image rounded-4',
						'loading' => 'lazy',
						'alt' => esc_attr(get_the_title()),
						'fetchpriority' => 'low',
						'title' => esc_attr(get_the_title()),
					]
				); ?>

			<?php endif; ?>
		</div>

		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__('Pages:', 'kt'),
				'after' => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if (get_edit_post_link()): ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__('Edit <span class="screen-reader-text">%s</span>', 'kt'),
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
			?>
		</footer><!-- .entry-footer -->

	<?php endif; ?>
</article>