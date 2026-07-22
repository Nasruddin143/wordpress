<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WooShop
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('py-5'); ?>>

	<?php if (!is_front_page()): ?>

		<?php
		if (function_exists('woocommerce_breadcrumb')) {
			woocommerce_breadcrumb(array(
				'delimiter' => ' / ',
				'wrap_before' => '<nav class="woocommerce-breadcrumb" aria-label="Breadcrumb">',
				'wrap_after' => '</nav>',
				'home' => _x('Home', 'breadcrumb', 'woocommerce'),
			));
		}
		?>

		<header class="entry-header mb-4">
			<?php the_title('<h1 class="text-dark fw-normal entry-title">', '</h1>'); ?>
		</header><!-- .entry-header -->
	<?php endif; ?>

	<div class="mb-4">
		<?php wooshop_post_thumbnail(); ?>
	</div>

	<div class="entry-content text-body-secondary fw-medium lh-base text-justify">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__('Pages:', 'wooshop'),
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
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->