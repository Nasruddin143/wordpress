<?php

/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package king_tailors
 */

use KT\Helpers\UI;

defined('ABSPATH') || exit;

get_header(); ?>

<main id="primary" class="site-main archive-page">

	<?php get_template_part('template-parts/content', 'custom-header'); ?>

	<div class="page-content-wrapper py-5">
		<div class="container">

			<!-- Header -->
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

				<h1 class="entry-title fw-bold">
					<?php the_archive_title(); ?>
				</h1>

				<?php if (get_the_archive_description()) : ?>
					<div class="archive-description">
						<?php echo wp_kses_post(get_the_archive_description()); ?>
					</div>
				<?php endif; ?>

			</header>

			<!--  ARCHIVE LOOP  -->
			<?php if (have_posts()): ?>

				<div class="row">

					<?php
					while (have_posts()):
						the_post();

						get_template_part('template-parts/content', get_post_type());

					endwhile; ?>

					<!-- Pagination -->
					<div class="mt-5 d-flex justify-content-center">
						<?php
						the_posts_pagination([
							'mid_size'  => 2,
							'screen_reader_text' => ' ',
							'prev_text' => __('Prev', 'kt'),
							'next_text' => __('Next', 'kt'),
							'class' => 'kt-pagination',
						]);
						// get_template_part('template-parts/navigation/navigation', 'pagination'); 
						?>
					</div>

				<?php else: ?>

					<div class="col-12">
						<?php get_template_part('template-parts/content', 'none'); ?>
					</div>

				<?php endif; ?>
				</div>
		</div>
	</div>
</main>

<?php get_footer();
