<?php
defined('ABSPATH') || exit;

global $comment;
?>

<li <?php comment_class('mb-4'); ?> id="li-comment-<?php comment_ID(); ?>">

	<div id="comment-<?php comment_ID(); ?>" class="card border-0 shadow">

		<div class="card-body">

			<div class="d-flex">

				<!-- Avatar -->
				<div class="flex-shrink-0 me-3">

					<?php echo get_avatar($comment,	60, '', 'User Image', ['class' => 'img-fluid']); ?>

				</div>

				<!-- Review Content -->
				<div class="flex-grow-1">

					<!-- Header -->
					<div class="mb-2">
						<h6 class="mb-0 fw-bold">							<?php comment_author(); ?>						</h6>

						<small class="text-muted">							<?php							echo esc_html(								get_comment_date()							);							?></small>
					</div>

					<!-- Rating -->
					<div class="kt-review-rating d-flex mb-2">

						<?php						$rating = intval(							get_comment_meta(								$comment->comment_ID,								'rating',								true							)						);						echo wc_get_rating_html($rating);						?>

					</div>

					<!-- Review Text -->
					<div class="description text-muted">

						<?php comment_text(); ?>

					</div>

				</div>

			</div>

		</div>

	</div>