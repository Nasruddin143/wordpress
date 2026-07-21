<?php

require get_template_directory() . '/inc/product-sections.php';

$new_products = wooshop_get_products(array(
	'orderby' => 'date',
	'order' => 'DESC'
));

$featured_products = wooshop_get_products(array(
	'featured' => true
));

$best_sellers = wooshop_get_products(array(
	'orderby' => 'popularity'
));

$top_rated = wooshop_get_products(array(
	'orderby' => 'rating'
));

$on_sale = wooshop_get_products(array(
	'include' => wc_get_product_ids_on_sale()
));

// WooCommerce doesn't have a built-in "Trending". A simple option is to reuse popularity:
$trending = wooshop_get_products(array(
	'orderby' => 'popularity'
));

?>


<div id="wcNewArriavals" class="new-arrivals py-5 bg-section-light">
	<!-- New Arrivals -->
	<div class="container text-center">
		<div class="embla mb-5">

			<div class="d-flex align-items-center justify-content-between section-title mb-4">
				<h2 class="fw-bold">New Arrivals</h2>

				<div class="slider-nav">
					<button class="embla__prev btn btn-primary">‹</button>
					<button class="embla__next btn btn-primary">›</button>
				</div>
			</div>

			<div class="embla__viewport">
				<div class="embla__container">
					<?php foreach ($new_products as $product): ?>
						<?php
						$image = $product->get_image('woocommerce_thumbnail');
						$title = $product->get_name();
						$price = $product->get_price_html();
						$link = $product->get_permalink();
						$rating = wc_get_rating_html($product->get_average_rating());
						?>

						<div class="embla__slide">

							<div class="product-card p-2 border rounded me-3 h-100 bg-white position-relative">
								<!-- Product Image -->
								<div class="product-image">

									<?php if ($product->is_on_sale()): ?>

										<span class="badge bg-first sale position-absolute top-0 start-0 small fw-light">
											Sale
										</span>

									<?php endif; ?>

									<?php if (!$product->is_in_stock()): ?>

										<span class="badge bg-first out-stock position-absolute top-0 start-0 small">
											Out of Stock
										</span>

									<?php endif; ?>

									<a href="<?php echo esc_url($link); ?>" class="product-image link-title"
										title="<?php echo esc_html($title); ?>">
										<?php echo $image; ?>
									</a>

									<!-- Overlay Icons -->
									<div class="product-overlay">

										<button type="button" class="wishlist-btn btn btn-outline-primary btn-sm rounded-circle p-2 lh-1"
											aria-label="Add to Wishlist" data-product="<?php echo $product->get_id(); ?>" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Add to Wishlist">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"
												height="16" class="main-grid-item-icon" fill="none" stroke="currentColor"
												stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
												<path
													d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
											</svg>
										</button>

										<button type="button" class="quickview-btn btn btn-outline-primary btn-sm rounded-circle p-2 lh-1"
											data-product-id="<?php echo esc_attr($product->get_id()); ?>" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Quick View"
											aria-label="Quick View">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"
												height="16" class="main-grid-item-icon" fill="none" stroke="currentColor"
												stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
												<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
												<circle cx="12" cy="12" r="3" />
											</svg>
										</button>

									</div>

								</div>

								<div class="product-content">

									<h3 class="product-title fs-6 fw-medium">

										<a href="<?php echo esc_url($link); ?>"
											class="link-offset-2 link-underline link-underline-opacity-0 link-title"
											title="<?php echo esc_html($title); ?>">

											<?php echo esc_html($title); ?>

										</a>

									</h3>

									<div
										class="product-rating d-flex gap-1 align-items-center justify-content-center mb-2 small">

										<?php if ($rating): ?>

											<?php echo $rating; ?><span
												class="text-muted">(<?php echo $product->get_review_count(); ?> Reviews)</span>

										<?php endif; ?>

									</div>

									<div class="product-price">

										<?php echo $price; ?>

									</div>

									<!-- <div class="product-cart">
										<?php
										// echo apply_filters(
										// 	'woocommerce_loop_add_to_cart_link',
										// 	sprintf(
										// 		'<a href="%s" class="button">%s</a>',
										// 		esc_url($product->add_to_cart_url()),
										// 		esc_html($product->add_to_cart_text())
										// 	),
										// 	$product
										// );
										?>
									</div> -->
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>



<div id="wcFeaturedProducts" class="featured-products py-5">
	<!-- Featured Products -->
	<div class="container text-center">
		<div class="embla mb-5">

			<div class="d-flex align-items-center justify-content-between section-title mb-4">
				<h2 class="fw-bold">Featured Products</h2>

				<div class="slider-nav">
					<button class="embla__prev btn btn-primary">‹</button>
					<button class="embla__next btn btn-primary">›</button>
				</div>
			</div>

			<div class="embla__viewport">
				<div class="embla__container">
					<?php foreach ($featured_products as $product): ?>
						<?php
						$image = $product->get_image('woocommerce_thumbnail');
						$title = $product->get_name();
						$price = $product->get_price_html();
						$link = $product->get_permalink();
						$rating = wc_get_rating_html($product->get_average_rating());
						?>

						<div class="embla__slide">

							<div class="product-card p-2 border rounded me-3 h-100 bg-white position-relative">
								<!-- Product Image -->
								<div class="product-image">

									<?php if ($product->is_on_sale()): ?>

										<span class="badge bg-first sale position-absolute top-0 start-0 small fw-light">
											Sale
										</span>

									<?php endif; ?>

									<?php if (!$product->is_in_stock()): ?>

										<span class="badge bg-first out-stock position-absolute top-0 start-0 small">
											Out of Stock
										</span>

									<?php endif; ?>

									<a href="<?php echo esc_url($link); ?>" class="product-image link-title"
										title="<?php echo esc_html($title); ?>">
										<?php echo $image; ?>
									</a>

									<!-- Overlay Icons -->
									<div class="product-overlay">

										<button class="wishlist-btn btn btn-outline-primary btn-sm rounded-circle p-2 lh-1" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Add to Wishlist"
											aria-label="Add to Wishlist" data-product="<?php echo $product->get_id(); ?>">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"
												height="16" class="main-grid-item-icon" fill="none" stroke="currentColor"
												stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
												<path
													d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
											</svg>
										</button>

										<button class="quickview-btn btn btn-outline-primary btn-sm rounded-circle p-2 lh-1" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Quick View"
											data-product-id="<?php echo esc_attr($product->get_id()); ?>"
											aria-label="Quick View">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"
												height="16" class="main-grid-item-icon" fill="none" stroke="currentColor"
												stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
												<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
												<circle cx="12" cy="12" r="3" />
											</svg>
										</button>

									</div>

								</div>

								<div class="product-content">

									<h3 class="product-title fs-6 fw-medium">

										<a href="<?php echo esc_url($link); ?>"
											class="link-offset-2 link-underline link-underline-opacity-0 link-title"
											title="<?php echo esc_html($title); ?>">

											<?php echo esc_html($title); ?>

										</a>

									</h3>

									<div
										class="product-rating d-flex gap-1 align-items-center justify-content-center mb-2 small">

										<?php if ($rating): ?>

											<?php echo $rating; ?><span
												class="text-muted">(<?php echo $product->get_review_count(); ?> Reviews)</span>

										<?php endif; ?>

									</div>

									<div class="product-price">

										<?php echo $price; ?>

									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>



<div id="wcBestSellers" class="best-sellers py-5 bg-section-light">
	<!-- Best Sellers -->
	<div class="container text-center">
		<div class="embla mb-5">

			<div class="d-flex align-items-center justify-content-between section-title mb-4">
				<h2 class="fw-bold">Best Sellers</h2>

				<div class="slider-nav">
					<button class="embla__prev btn btn-primary">‹</button>
					<button class="embla__next btn btn-primary">›</button>
				</div>
			</div>

			<div class="embla__viewport">
				<div class="embla__container">
					<?php foreach ($best_sellers as $product): ?>
						<?php
						$image = $product->get_image('woocommerce_thumbnail');
						$title = $product->get_name();
						$price = $product->get_price_html();
						$link = $product->get_permalink();
						$rating = wc_get_rating_html($product->get_average_rating());
						?>

						<div class="embla__slide">

							<div class="product-card p-2 border rounded me-3 h-100 bg-white position-relative">
								<!-- Product Image -->
								<div class="product-image">

									<?php if ($product->is_on_sale()): ?>

										<span class="badge bg-first sale position-absolute top-0 start-0 small fw-light">
											Sale
										</span>

									<?php endif; ?>

									<?php if (!$product->is_in_stock()): ?>

										<span class="badge bg-first out-stock position-absolute top-0 start-0 small">
											Out of Stock
										</span>

									<?php endif; ?>

									<a href="<?php echo esc_url($link); ?>" class="product-image link-title"
										title="<?php echo esc_html($title); ?>">
										<?php echo $image; ?>
									</a>

									<!-- Overlay Icons -->
									<div class="product-overlay">

										<button class="wishlist-btn btn btn-outline-primary btn-sm rounded-circle p-2 lh-1" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Add to Wishlist"
											aria-label="Add to Wishlist" data-product="<?php echo $product->get_id(); ?>">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"
												height="16" class="main-grid-item-icon" fill="none" stroke="currentColor"
												stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
												<path
													d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
											</svg>
										</button>

										<button class="quickview-btn btn btn-outline-primary btn-sm rounded-circle p-2 lh-1" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Quick View"
											data-product-id="<?php echo esc_attr($product->get_id()); ?>"
											aria-label="Quick View">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"
												height="16" class="main-grid-item-icon" fill="none" stroke="currentColor"
												stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
												<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
												<circle cx="12" cy="12" r="3" />
											</svg>
										</button>

									</div>

								</div>

								<div class="product-content">

									<h3 class="product-title fs-6 fw-medium">

										<a href="<?php echo esc_url($link); ?>"
											class="link-offset-2 link-underline link-underline-opacity-0 link-title"
											title="<?php echo esc_html($title); ?>">

											<?php echo esc_html($title); ?>

										</a>

									</h3>

									<div
										class="product-rating d-flex gap-1 align-items-center justify-content-center mb-2 small">

										<?php if ($rating): ?>

											<?php echo $rating; ?><span
												class="text-muted">(<?php echo $product->get_review_count(); ?> Reviews)</span>

										<?php endif; ?>

									</div>

									<div class="product-price">

										<?php echo $price; ?>

									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>