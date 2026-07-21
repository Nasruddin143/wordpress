<?php

/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.7.0
 */

defined('ABSPATH') || exit;

global $product;

if (! comments_open()) {
	return;
}

?>
<div id="reviews" class="woocommerce-Reviews">

	<div class="row g-4">

		<!-- LEFT SIDE -->
		<div class="col-lg-4">

			<div class="sticky-top" style="top:20px;">

			<h2 class="h4 fw-semibold mb-3">Customer Reviews</h2>
				<!-- Rating Summary -->
				<div class="card border-0 shadow mb-4">

					<div class="card-body">

						<?php
						$average = $product->get_average_rating();
						$count   = $product->get_review_count();
						?>

						<div class="text-center mb-4">

							<div class="display-4 fw-bold">
							<?php echo esc_html(number_format($average, 1)); ?>
							</div>

							<div class="mb-2 d-flex justify-content-center">
								<?php echo wc_get_rating_html($average); ?>
							</div>

							<div class="text-muted small">
								Based on <?php echo esc_html($count); ?> reviews
							</div>

						</div>

						<!-- Rating Bars -->
						<?php for ($i = 5; $i >= 1; $i--) : ?>

							<?php
							$star_count = get_comments(array(
								'post_id' => $product->get_id(),
								'count'   => true,
								'meta_query' => array(
									array(
										'key'   => 'rating',
										'value' => $i,
									),
								),
							));

							$percentage = ($count > 0)
								? ($star_count / $count) * 100
								: 0;
							?>

							<div class="d-flex align-items-center mb-2">

								<div class="small me-2" style="width:40px;">
									<?php echo esc_html($i); ?> <i class="bi bi-star-fill ms-1 text-warning"></i> 
								</div>

								<div class="progress flex-grow-1"
									style="height:8px;">

									<div class="progress-bar bg-warning"
										role="progressbar"
										style="width: <?php echo esc_attr($percentage); ?>%">
									</div>

								</div>

								<div class="small text-muted ms-2"
									style="width:30px;">

									<?php echo esc_html($star_count); ?> %

								</div>

							</div>

						<?php endfor; ?>

					</div>

				</div>

				<!-- Review Form -->
				<?php if (
					get_option('woocommerce_review_rating_verification_required') === 'no'
					|| wc_customer_bought_product('', get_current_user_id(), $product->get_id())
				) : ?>

					<div class="card border-0 shadow">

						<div class="card-body">

							<?php
							$commenter = wp_get_current_commenter();

							$comment_form = array(

								'title_reply' => '<h2 class="fw-semibold h4 mb-3">Review this product</h2>',

								'title_reply_before' => '',
								'title_reply_after'  => '',

								'class_form' => 'row g-3',

								'label_submit' => esc_html__('Submit Review', 'woocommerce'),

								'class_submit' => 'btn btn-primary w-100',

								'logged_in_as' => '',

								'comment_notes_after' => '',

								'fields' => array(

									'author' => '
									<div class="col-12">
										<label class="form-label">Name</label>

										<input
											id="author"
											name="author"
											type="text"
											class="form-control"
											value="' . esc_attr($commenter['comment_author']) . '"
											required>
									</div>
								',

									'email' => '
									<div class="col-12">
										<label class="form-label">Email</label>

										<input
											id="email"
											name="email"
											type="email"
											class="form-control"
											value="' . esc_attr($commenter['comment_author_email']) . '"
											required>
									</div>
								',
								),

								'comment_field' => '',
							);

							// Rating Select
							if (wc_review_ratings_enabled()) {

								$comment_form['comment_field'] .= '

								<div class="col-12">

									<label class="form-label">
										Your Rating
									</label>

									<select
										name="rating"
										id="rating"
										class="form-select"
										required>

										<option value="">
											Rate…
										</option>

										<option value="5">
											★★★★★ Perfect
										</option>

										<option value="4">
											★★★★☆ Good
										</option>

										<option value="3">
											★★★☆☆ Average
										</option>

										<option value="2">
											★★☆☆☆ Poor
										</option>

										<option value="1">
											★☆☆☆☆ Very Poor
										</option>

									</select>

								</div>
							';
							}

							$comment_form['comment_field'] .= '

							<div class="col-12">

								<label class="form-label">
									Your Review
								</label>

								<textarea
									id="comment"
									name="comment"
									class="form-control"
									rows="5"
									required></textarea>

							</div>
						';

							comment_form(
								apply_filters(
									'woocommerce_product_review_comment_form_args',
									$comment_form
								)
							);
							?>

						</div>

					</div>

				<?php endif; ?>

			</div>

		</div>

		<!-- RIGHT SIDE -->
		<div class="col-lg-8">

			<div id="comments">

				<h2 class="woocommerce-Reviews-title fw-semibold h4 mb-3">

					<?php
					$count = $product->get_review_count();

					printf(
						esc_html(_n('%s Reviews', '%s Reviews', $count, 'woocommerce')),
						esc_html($count)
					);
					?>

				</h2>

				<?php if (have_comments()) : ?>

					<ol class="commentlist list-unstyled">

						<?php
						wp_list_comments(
							apply_filters(
								'woocommerce_product_review_list_args',
								array(
									'callback' => 'woocommerce_comments'
								)
							)
						);
						?>

					</ol>

				<?php else : ?>

					<div class="alert alert-light border">
						<?php esc_html_e('There are no reviews yet.', 'woocommerce'); ?>
					</div>

				<?php endif; ?>

			</div>

		</div>


	</div>

</div>