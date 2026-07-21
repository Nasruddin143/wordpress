<?php
/**
 * Front Page Template
 *
 * Displays homepage content
 *
 * @package king_tailors
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main-content" class="site-main" role="main">

	<div class="container">

		<?php if (have_posts()): ?>

			<?php while (have_posts()):
				the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('front-page-content'); ?>>

					<!-- Title (optional, hide if using page builder) -->
					<!-- <?php //if (get_the_title()): ?>
						<header class="entry-header mb-3">

							<h1 class="entry-title h2" itemprop="headline">

								<?php the_title(); ?>

							</h1>

						</header>
					<?php //endif; ?> -->

					<!-- Content -->
					<div class="entry-content" itemprop="mainContentOfPage">

						<?php the_content(); ?>

					</div>

				</article>

			<?php endwhile; ?>

		<?php else: ?>

			<div class="alert alert-info">

				<?php echo esc_html__('No content found.', 'kt'); ?>

			</div>

		<?php endif; ?>

	</div>

</main>

<?php get_footer();