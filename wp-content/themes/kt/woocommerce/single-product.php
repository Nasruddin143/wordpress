<?php
defined('ABSPATH') || exit;

get_header('shop');

global $product; ?>

<?php get_template_part('template-parts/content', 'custom-header'); ?>

<main id="primary" class="site-main py-5 wc-single-product bg-light" tabindex="-1">

	<?php while (have_posts()): ?>

		<?php the_post(); ?>

		<div class="container">

			<?php woocommerce_output_all_notices(); ?>

			<!-- Breadcrumb -->
			<div class="mb-4">
				<?php woocommerce_breadcrumb(); ?>
			</div>

			<div class="card border-0 shadow-sm">
				<div class="card-body">
					<div class="row">

						<!-- Product Gallery -->
						<div class="col-lg-5">

							<div class="position-relative">

								<?php if ($product->is_on_sale()): ?>
									<span class="badge px-2 py-2 align-items-center bg-success position-absolute top-0 start-0">
										<?php esc_html_e('SALE', 'kt'); ?>
									</span>
								<?php endif; ?>

								<div class="text-center">
									<?php if (has_post_thumbnail()): ?>
										<img id="mainProductImage"
											src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>"
											class="img-fluid rounded" alt="<?php the_title(); ?>">
									<?php else: ?>
										<div class="bg-light d-flex align-items-center justify-content-center">
											<span class="text-muted"><?php esc_html_e('No Image', 'kt'); ?></span>
										</div>
									<?php endif; ?>
								</div>
							</div>

							<!-- Thumbnails -->
							<?php $attachment_ids = $product->get_gallery_image_ids(); ?>
							<?php if ($attachment_ids): ?>
								<div class="row mt-3 g-2">

									<?php if (has_post_thumbnail()): ?>
										<div class="col-3">
											<img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'thumbnail')); ?>"
												class="img-thumbnail product-thumb border border-primary"
												data-large="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>">
										</div>
									<?php endif; ?>

									<?php foreach ($attachment_ids as $attachment_id): ?>
										<div class="col-3">
											<img src="<?php echo esc_url(wp_get_attachment_image_url($attachment_id, 'thumbnail')); ?>"
												class="img-thumbnail product-thumb"
												data-large="<?php echo esc_url(wp_get_attachment_image_url($attachment_id, 'large')); ?>">
										</div>
									<?php endforeach; ?>

								</div>
							<?php endif; ?>

						</div>

						<!-- Product Details -->
						<div class="col-lg-7">

							<h1 class="my-3 fw-bold h4">
								<?php the_title(); ?>
							</h1>

							<!-- Rating -->
							<?php if (wc_review_ratings_enabled()): ?>
								<div class="my-3">
									<?php woocommerce_template_single_rating(); ?>
								</div>
							<?php endif; ?>

							<!-- Price -->
							<div class="h4 text-primary my-3">
								<?php woocommerce_template_single_price(); ?>
							</div>

							<!-- Description -->
							<div class="my-4 text-muted">
								<?php woocommerce_template_single_excerpt(); ?>
							</div>

							<!-- Add to Cart -->
							<div class="my-4">
								<?php woocommerce_template_single_add_to_cart(); ?>
							</div>

							<!-- Meta -->
							<div class="small text-muted">
								<?php woocommerce_template_single_meta(); ?>
							</div>

						</div>
					</div>
				</div>
			</div>


			<!-- Tabs / Related -->
			<div class="mt-5">
				<?php do_action('woocommerce_after_single_product_summary'); ?>
			</div>
		</div>

	<?php endwhile; ?>

</main>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const thumbs = document.querySelectorAll('.product-thumb');
		const mainImage = document.getElementById('mainProductImage');

		thumbs.forEach(function(thumb) {
			thumb.addEventListener('click', function() {

				// Remove active border
				thumbs.forEach(t => t.classList.remove('border-primary'));

				// Add active border
				this.classList.add('border-primary');

				// Change image
				const newSrc = this.getAttribute('data-large');
				if (newSrc) {
					mainImage.src = newSrc;
				}
			});
		});
	});
</script>

<?php get_footer('shop');
