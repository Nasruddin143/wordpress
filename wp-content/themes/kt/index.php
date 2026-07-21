<?php
/**
 * The main template file
 *
 * @package king_tailors
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="primary" class="site-main index-page">

	<?php get_template_part('template-parts/content', 'custom-header'); ?>

	<div class="page-content-wrapper py-5">
		<div class="container">

			<?php if (have_posts()) : ?>

				<?php if (is_home() && !is_front_page()) : ?>
					<header class="page-header mb-4">

					<!-- Breadcrumb -->
                <?php if (function_exists('bcn_display')) : ?>
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <nav class="breadcrumb-nav mb-0" aria-label="breadcrumb">
                        <?php bcn_display(); ?>
                    </nav>
                    </div>
                <?php endif; ?>
				
						<h1 class="page-title">
							<?php single_post_title(); ?>
						</h1>
					</header>
				<?php endif; ?>

				<div class="row g-4">

					<!-- Main Content -->
					<div class="col-lg-8">

						<?php
						while (have_posts()) :
							the_post();

							/*
							 * Loads:
							 * template-parts/content-{posttype}.php
							 * fallback: template-parts/content.php
							 */
							get_template_part('template-parts/content', get_post_type());

						endwhile;
						?>

						<!-- Pagination -->
						<div class="mt-5">
							<?php
							the_posts_pagination([
								'mid_size'  => 2,
								'prev_text' => __('← Previous', 'king_tailors'),
								'next_text' => __('Next →', 'king_tailors'),
								'class'     => 'pagination justify-content-center',
							]);
							?>
						</div>

					</div>

					<!-- Sidebar -->
					<aside class="col-lg-4">
						<?php get_sidebar(); ?>
					</aside>

				</div>

			<?php else : ?>

				<?php get_template_part('template-parts/content', 'none'); ?>

			<?php endif; ?>

		</div>
	</div>

</main>

<?php get_footer();
