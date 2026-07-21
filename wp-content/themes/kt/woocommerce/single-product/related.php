<?php

use KT\Helpers\Ratings;

defined('ABSPATH') || exit;

if (!$related_products) {
	return;
}
?>

<section class="related-products py-5">

	<div class="d-flex align-items-center justify-content-between mb-4">

		<h2 class="h4 fw-bold mb-0">

			<?php echo esc_html(apply_filters('woocommerce_product_related_products_heading', __('Related Products', 'woocommerce'))); ?>

		</h2>

	</div>

	<div class="row g-4">

		<?php foreach ($related_products as $related_product) : ?>

			<?php
			$post_object = get_post($related_product->get_id());

			setup_postdata($GLOBALS['post'] = $post_object);

			global $product;
			?>

			<div class="col-6 col-md-4 col-lg-3">

				<div class="border-0 shadow px-3 py-2 rounded-3 related-product bg-white">

					<!-- Thumbnail -->
					<a href="<?php the_permalink(); ?>" class="product-image d-block position-relative">

						<!-- Sale Badge -->
						<?php if ($product->is_on_sale()) : ?>

							<span class="badge bg-danger position-absolute top-0 start-0 m-2">

								<?php esc_html_e('Sale', 'kt'); ?>

							</span>

						<?php endif; ?>

						<?php if (has_post_thumbnail()) : ?>

							<?php the_post_thumbnail(
								'woocommerce_thumbnail',
								array(
									'class' => 'img-fluid'
								)
							); ?>

						<?php endif; ?>
					</a>

					<div class="kt-product-body py-2">

						<!-- Title -->
						<h3 class="product-title h6 mb-2">

							<a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none"> <?php the_title(); ?></a>

						</h3>

						<!-- Rating -->
						<?php if (wc_review_ratings_enabled()) : ?>

							<div class="mb-2">

								<?php Ratings::render($product); ?>

							</div>

						<?php endif; ?>

						<!-- Price -->
						<div class="kt-price d-flex gap-2 mb-2">

							<?php echo $product->get_price_html(); ?>

						</div>

						<!-- Add To Cart -->
						<div class="mt-auto">

							<?php woocommerce_template_loop_add_to_cart(); ?>

						</div>

					</div>

				</div>

			</div>

		<?php endforeach; ?>

	</div>

</section>

<?php wp_reset_postdata();
