<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WooShop
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-5'); ?>>

	<div class="card border">

		<?php wooshop_post_thumbnail(); ?>

		<div class="card-body">
			<header class="entry-header">

				<?php

				if (is_singular()):
					the_title('<h1 class="entry-title card-title">', '</h1>');
				else:
					the_title('<h2 class="fw-normal entry-title card-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark" class="link-dark text-decoration-none">', '</a></h2>');
				endif;

				if ('post' === get_post_type()): ?>

					<?php wooshop_post_meta(); ?>

				<?php endif; ?>

			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php

				if (is_singular()):

					the_content(
						sprintf(
							wp_kses(
								/* translators: %s: Name of current post. Only visible to screen readers */
								__('Continue reading<span class="screen-reader-text"> "%s"</span>', 'wooshop'),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							),
							wp_kses_post(get_the_title())
						)

					);
					

				else:

					the_excerpt();

				endif;

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__('Pages:', 'wooshop'),
							'after' => '</div>',
						)
					);
				
				?>
			</div><!-- .entry-content -->

			<footer class="entry-footer">
				<?php wooshop_entry_footer(); ?>
			</footer>
			<!-- .entry-footer -->
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->