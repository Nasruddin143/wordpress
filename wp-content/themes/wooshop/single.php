<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WooShop
 */

get_header();
?>

<main id="primary" class="site-main woocommerce single">

	<div class="page-banner mb-3">
		<img class="img-fluid" src="<?php echo get_template_directory_uri() . '/assets/images/page-banner.webp' ?>"
			alt="Page Banner">
	</div>

	<div class="container py-5">

		<div class="row">
			<div class="col col-md-9">
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

				<?php
				while (have_posts()):
					the_post();

					get_template_part('template-parts/content', get_post_type());

					the_post_navigation(
						array(
							'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'wooshop') . '</span> <span class="nav-title">%title</span>',
							'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'wooshop') . '</span> <span class="nav-title">%title</span>',
						)
					);

					// If comments are open or we have at least one comment, load up the comment template.
					if (comments_open() || get_comments_number()):
						comments_template();
					endif;

				endwhile; // End of the loop.
				?>
			</div>
			<div class="col col-md-3">
				<?php
				get_sidebar(); ?>
			</div>
		</div>

	</div>

</main><!-- #main -->

<?php get_footer();
