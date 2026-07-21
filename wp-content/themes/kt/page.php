<?php
/**
 * The template for displaying all pages
 *
 * @package king_tailors
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="primary" class="site-main page-template">

	<?php get_template_part('template-parts/content', 'custom-header'); ?>

	<div class="page-content-wrapper py-5">
		<div class="container">

			<?php
			while (have_posts()) :
				the_post();

				get_template_part('template-parts/content', 'page');

				// Load comments only if enabled or existing
				// if (comments_open() || get_comments_number()) :
				// 	echo '<div class="mt-5">';
				// 	comments_template();
				// 	echo '</div>';
				// endif;

			endwhile;
			?>

		</div>
	</div>

</main>

<?php get_footer();
