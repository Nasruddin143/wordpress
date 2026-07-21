<?php 

function kt_related_posts($post_id = null, $post_type = 'post', $limit = 4)
{

	if (!$post_id) {
		$post_id = get_the_ID();
	}

	$taxonomies = get_object_taxonomies($post_type);
	if (empty($taxonomies))
		return;

	$tax_query = array('relation' => 'OR');

	foreach ($taxonomies as $taxonomy) {

		$terms = wp_get_post_terms($post_id, $taxonomy, ['fields' => 'ids']);

		if (!empty($terms) && !is_wp_error($terms)) {

			$tax_query[] = [
				'taxonomy' => $taxonomy,
				'field' => 'term_id',
				'terms' => $terms
			];
		}
	}

	if (count($tax_query) <= 1)
		return;

	$args = [
		'post_type' => $post_type,
		'exclude' => [$post_id],
		'posts_per_page' => $limit,
		'orderby' => 'comment_count',
		'tax_query' => $tax_query,
		'no_found_rows' => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	];

	$related_posts = get_posts($args);

	if ($related_posts) {

		echo '<div class="related-posts">';
		echo '<h3 class="fw-bold mb-3">Featured items you may like</h3>';
		echo '<div class="row mb-4">';

		foreach ($related_posts as $related) {

			echo '<div class="col-md-3">';
			echo '<div class="card h-100 rounded-0 border-0">';

			if (has_post_thumbnail($related->ID)) {

				echo '<a href="' . get_permalink($related->ID) . '" class="bg-light">';
				echo get_the_post_thumbnail(
					$related->ID,
					'kt-product-main',
					[
						'class' => 'img-fluid related-prodcut-image',
						'title' => get_the_title($related->ID),
						'alt' => get_the_title($related->ID)
					]
				);
				echo '</a>';
			}

			echo '<div class="card-body">';

			echo '<h4 class="h6 fw-bold card-title">';
			echo '<a href="' . get_permalink($related->ID) . '" class="link-offset-2 link-underline link-underline-opacity-0 link-dark" title="'.get_the_title($related->ID).'">';
			echo get_the_title($related->ID);
			echo '</a></h5>';
			echo '<div class="d-flex justify-content-between">';
			echo kt_acf_product_price_html($related->ID, [
				'show_discount' => true,
				'show_diff' => false,
				'currency' => '₹',
			]);
			
            

                                        $reviews = apply_filters('glsr_get_reviews', null, [
                                            'assigned_posts' => $related->ID,
                                            'status' => 'approved',
                                            //'post_type' => $post_type,
                                        ]);

                                        $total_reviews = $reviews->total ?? 0;
                                        $reviews_list  = $reviews->reviews ?? [];

                                        $total_rating = 0;

                                        if (!empty($reviews_list)) {
                                            foreach ($reviews_list as $review) {
                                                $total_rating += (int) $review->rating;
                                            }
                                        }

                                        $average = $total_reviews > 0 ? round($total_rating / $total_reviews, 1) : 0;

                                        // Generate stars
                                        //$stars_html = apply_filters('glsr_star_rating', '', $average);
                                        $full_stars = floor($average);
                                        $stars_html = str_repeat('★', $full_stars);
                                        $stars_html .= str_repeat('☆', 5 - $full_stars);

                                    


                                       echo '<div class="kt-rating-stars">';
                                            echo '<span class="stars">'.$stars_html .'</span>';
                                        echo '</div>';
        
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}

		echo '</div>';
		echo '</div>';
	}
}