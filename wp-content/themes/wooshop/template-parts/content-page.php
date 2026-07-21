<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WooShop
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if (!is_front_page()): ?>

		<?php
		if (function_exists('woocommerce_breadcrumb')) {
			woocommerce_breadcrumb(array(
				'delimiter' => ' <span class="breadcrumb-separator"><!-- https://feathericons.dev/?search=chevron-right&iconset=feather -->
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="15" height="15" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
  <polyline points="9 18 15 12 9 6" />
</svg>
</span> ',
				'wrap_before' => '<nav class="woocommerce-breadcrumb" aria-label="Breadcrumb">',
				'wrap_after' => '</nav>',
				'home' => _x('Home', 'breadcrumb', 'woocommerce'),
			));
		}
		?>

		<header class="entry-header">
			<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
		</header><!-- .entry-header -->
	<?php endif; ?>

	<?php wooshop_post_thumbnail(); ?>

	<div class="entry-content">
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