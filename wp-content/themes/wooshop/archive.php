<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WooShop
 */

get_header();
?>

<main id="primary" class="site-main woocommerce archive">

	<div class="page-banner mb-3">
		<img class="img-fluid" src="<?php echo get_template_directory_uri() . '/assets/images/page-banner.webp' ?>"
			alt="Page Banner">
	</div>

	<div class="py-5">

		<div class="container">

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

			<div class="row">
				<div class="col-12 col-sm-12 col-md-8 col-lg-9">

					<header class="page-header">
						<?php
						the_archive_title('<h1 class="page-title text-dark fw-normal mb-4">', '</h1>');
						the_archive_description('<div class="archive-description text-body-tertiary mb-4">', '</div>');
						?>
					</header><!-- .page-header -->

					<?php if (have_posts()): ?>
						<?php
						/* Start the Loop */
						while (have_posts()):
							the_post();

							/*
							 * Include the Post-Type-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
							 */
							get_template_part('template-parts/content', get_post_type());

						endwhile;

						the_posts_navigation();

					else:

						get_template_part('template-parts/content', 'none');

					endif;
					?>
				</div>

				<div class="col-12 col-sm-12 col-md-4 col-lg-3">
					<?php
					get_sidebar(); ?>
				</div>
			</div>

		</div>
	</div>
</main><!-- #main -->

<?php get_footer();
