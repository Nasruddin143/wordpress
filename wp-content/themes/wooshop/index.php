<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WooShop
 */

get_header();
?>

<main id="primary index" class="site-main">

	<div class="page-banner mb-5">
		<img class="img-fluid" src="<?php echo get_template_directory_uri() . '/assets/images/page-banner.webp' ?>"
			alt="Page Banner">
	</div>

	<div class="container">
		<?php
		if (have_posts()):

			if (is_home() && !is_front_page()):
				?>

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

				<header>
					<h1 class="h3 page-title screen-reader-text text-first fw-medium"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif;

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
</main><!-- #main -->

<?php
get_sidebar();
get_footer();
