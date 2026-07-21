<?php require_once get_template_directory() . '/vc-elements/swiper-assets.php';

/**
 * Product Slider – Swiper based (WPBakery)
 */

if (!defined('ABSPATH')) {
    exit;
}

/*--------------------------------------------------------------
 Register WPBakery Element
--------------------------------------------------------------*/
if (defined('WPB_VC_VERSION')) {

    add_action('vc_before_init', function () {

        vc_map([
            'name' => esc_html__('Product Slider', 'kt'),
            'base' => 'vc_product_slider',
            'category' => esc_html__('Custom Components', 'kt'),
            'description' => esc_html__('Lightweight product slider (Swiper)', 'kt'),
            'icon' => '',

            'params' => [

                [
                    'type' => 'textfield',
                    'heading' => esc_html__('Title', 'kt'),
                    'param_name' => 'title',
                ],

                [
                    'type' => 'dropdown',
                    'heading' => esc_html__('Heading Position', 'kt'),
                    'param_name' => 'heading_position',
                    'value' => [
                        esc_html__('Left', 'kt') => 'flex-row',
                        esc_html__('Right', 'kt') => 'flex-row-reverse',
                    ],
                    'std' => 'flex-row',
                    'description' => esc_html__('Select heading alignment.', 'kt'),
                ],

                [
                    'type' => 'textarea',
                    'heading' => esc_html__('Description', 'kt'),
                    'param_name' => 'desc',
                ],

                [
                    'type' => 'posttypes',
                    'heading' => esc_html__('Post Type', 'kt'),
                    'param_name' => 'post_type',
                ],

                [
                    'type' => 'textfield',
                    'heading' => esc_html__('Items Count', 'kt'),
                    'param_name' => 'limit',
                    'value' => '10',
                ],

                [
                    'type' => 'dropdown',
                    'heading' => esc_html__('Autoplay', 'kt'),
                    'param_name' => 'autoplay',
                    'value' => [
                        esc_html__('Enable', 'kt') => 'true',
                        esc_html__('Disable', 'kt') => 'false',
                    ],
                    'std' => 'false',
                ],

                [
                    'type' => 'dropdown',
                    'heading' => esc_html__('Loop', 'kt'),
                    'param_name' => 'loop',
                    'value' => [
                        esc_html__('Enable', 'kt') => 'true',
                        esc_html__('Disable', 'kt') => 'false',
                    ],
                    'std' => 'true',
                ],

                [
                    'type' => 'dropdown',
                    'heading' => esc_html__('Sort order', 'kt'),
                    'param_name' => 'order',
                    'value' => [
                        esc_html__('Descending', 'kt') => 'DESC',
                        esc_html__('Ascending', 'kt') => 'ASC',
                    ],
                    'std' => 'DESC',
                    'description' => sprintf(
                        wp_kses_post(
                            __('Select ascending or descending order. More at %s.', 'kt')
                        ),
                        '<a href="https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank" rel="noopener">WordPress Codex</a>'
                    ),
                ],

                [
                    'type' => 'vc_link',
                    'heading' => __('View All Link', 'kt'),
                    'param_name' => 'view_all',
                    'description' => __('Add button link (URL, title, target)', 'kt'),
                ],

                [
                    'type' => 'el_id',
                    'heading' => esc_html__('Element ID', 'kt'),
                    'param_name' => 'el_id',
                    'description' => sprintf(
                        wp_kses_post(
                            __('Enter element ID (Note: make sure it is unique and valid according to %1$sw3c specification%2$s).', 'kt')
                        ),
                        '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank" rel="noopener">',
                        '</a>'
                    ),
                ],

                [
                    'type' => 'textfield',
                    'heading' => esc_html__('Extra class name', 'kt'),
                    'param_name' => 'el_class',
                    'description' => esc_html__(
                        'Style particular content element differently - add a class name and refer to it in custom CSS.',
                        'kt'
                    ),
                ],

                [
                    'type' => 'css_editor',
                    'heading' => esc_html__('CSS box', 'kt'),
                    'param_name' => 'css',
                    'group' => esc_html__('Design Options', 'kt'),
                ],
            ],
        ]);
    });

    /*--------------------------------------------------------------
     Shortcode Output
    --------------------------------------------------------------*/
    add_shortcode('vc_product_slider', function ($atts) {

        kt_ensure_swiper_loaded();

        $atts = shortcode_atts([
            'title' => '',
            'heading_position' => 'flex-row',
            'desc' => '',
            'post_type' => '',
            'limit' => 10,
            'autoplay' => 'false',
            'loop' => 'true',
            'order' => 'DESC',
            'view_all' => '',
            'el_id' => '',
            'el_class' => '',
            'css' => '',
        ], $atts);

        $group = 'kt_product_slider';
        $cache_key = $group . md5(serialize($atts));
        $cached = KT_Cache::get($cache_key, $group);

        if ($cached !== false) {
            return $cached;
        }

        $limit = min(max((int) $atts['limit'], 1), 20);

        $css_class = apply_filters(
            VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
            vc_shortcode_custom_css_class($atts['css'], ' ')
        );
        $wrapper_class = trim($css_class . ' ' . esc_attr($atts['el_class']));

        $post_type = post_type_exists($atts['post_type']) ? $atts['post_type'] : 'post';

        $query = new WP_Query([
            'post_type' => $post_type,
            'posts_per_page' => $limit,
            'post_status' => 'publish',
            'order' => in_array($atts['order'], ['ASC', 'DESC'], true) ? $atts['order'] : 'DESC',
            'no_found_rows' => true,
            'ignore_sticky_posts' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => true,

        ]);

        if (!$query->have_posts()) {
            return '';
        }

        //$taxonomies = get_object_taxonomies($atts['post_type'], 'objects');
        $uid = 'wp_' . substr($cache_key, 0, 50);
        $link = vc_build_link($atts['view_all']);
        $url = $link['url'] ?? '';
        ob_start(); ?>


        <section class="kt-slider-carousel kt-prod-slider py-5 <?php echo esc_attr($wrapper_class); ?>" <?php if ($atts['el_id']) echo 'id="' . esc_attr($atts['el_id']) . '"'; ?>>
            <div class="row justify-content-center mb-4">
                <div class="col-12 col-md-8">
                    <div class="d-grid row-gap-3 text-center">

                        <?php if ($atts['title']): ?>
                            <h2 class="mb-0 fw-bold">
                                <?php echo esc_html($atts['title']); ?>
                            </h2>
                        <?php endif; ?>

                        <?php if ($atts['desc']): ?>
                            <p class="mb-0">
                                <?php echo esc_html($atts['desc']); ?>
                            </p>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <!-- Slider -->


            <div id="<?php echo esc_html($uid); ?>" class="swiper kt-swiper"
                data-autoplay="<?php echo esc_attr($atts['autoplay']); ?>"
                data-loop="<?php echo esc_attr($atts['loop']); ?>">

                <div class="swiper-wrapper">

                    <?php while ($query->have_posts()):
                        $query->the_post(); ?>

                        <div class="swiper-slide">

                            <article class="card bg-transparent border-0 h-100">

                                <div class="position-relative product-img-wrapper rounded-4">
                                    <a href="<?php the_permalink(); ?>" title="<?php esc_attr(get_the_title()); ?>">
                                        <?php
                                        $thumb_id = get_post_thumbnail_id();
                                        if ($thumb_id) {
                                            echo wp_get_attachment_image(
                                                $thumb_id,
                                                'kt-product-card',
                                                false,
                                                [
                                                    'class' => 'product-image img-fluid',
                                                    'alt' => esc_attr(get_the_title()),
                                                    'loading' => 'lazy',
                                                    'decoding' => 'async',
                                                    'fetchpriority' => 'low',
                                                    'title' => esc_attr(get_the_title()),
                                                ]
                                            );
                                        } else {
                                            echo '<img src="' . esc_url(get_template_directory_uri() . '/images/placeholder.webp') . '"
                                                    class="img-fluid" 
                                                    alt="' . esc_attr(get_the_title()) . '"
                                                    title="' . esc_attr(get_the_title()) . '"
                                                    loading="lazy" decoding="async">';
                                        }
                                        ?>
                                    </a>
                                    <?php if ($discount = kt_get_product_discount_percent()): ?>
                                        <span class="discount-badge badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill">
                                            <?php echo esc_html(sprintf(__('%s%% OFF', 'kt'), $discount)); ?>
                                        </span>
                                    <?php endif; ?>

                                    <!-- <button class="wishlist-btn" aria-label="<?php //esc_attr_e('Add to wishlist', 'kt'); 
                                                                                    ?>">
                                        <i class="bi bi-heart mt-1"></i>
                                    </button> -->

                                    <div class="wishlist-btn">
                                        <?php echo kt_trending_badges(); ?>
                                    </div>
                                </div>

                                <div class="card-body">

                                    <div class="d-grid row-gap-2">
                                        <!-- Category -->
                                        <!-- <div class="product-terms">
                                                <?php
                                                //$primary_term = kt_get_primary_term(get_the_ID(), get_post_type());

                                                //if ($primary_term): 
                                                ?>
                                                    <a href="<?php //echo esc_url(get_term_link($primary_term)); 
                                                                ?>"
                                                        class="badge bg-light-subtle border border-light-subtle text-light-emphasis rounded-pill">
                                                        <?php //echo esc_html($primary_term->name); 
                                                        ?>
                                                    </a>
                                                <?php //endif; 
                                                ?>
                                            </div> -->

                                        <h3 class="card-title h6 fw-bold mb-2">
                                            <a href="<?php the_permalink(); ?>" class="link-dark link-offset-2 link-underline link-underline-opacity-0"
                                                title="<?php echo esc_attr(the_title()); ?>">
                                                <?php echo esc_html(wp_trim_words(get_the_title(), 10)); ?>
                                            </a>
                                        </h3>

                                    </div>

                                    <div class="d-sm-flex align-items-center justify-content-between">
                                        <?php
                                        echo kt_acf_product_price_html(get_the_ID(), [
                                            'show_discount' => true,
                                            'show_diff' => false,
                                            'currency' => '₹',
                                        ]);
                                        ?>

                                        <?php

                                        $reviews = apply_filters('glsr_get_reviews', null, [
                                            'assigned_posts' => get_the_ID(),
                                            'status' => 'approved',
                                            'post_type' => $post_type,
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

                                        ?>


                                        <div class="kt-rating-stars">
                                            <span class="stars"><?php echo $stars_html; ?></span>
                                        </div>
                                    </div>
                            </article>

                        </div>

                    <?php endwhile; ?>

                </div>

                <div class="swiper-button-prev custom-nav" aria-label="Previous slide"></div>
                <div class="swiper-button-next custom-nav" aria-label="Next slide"></div>
            </div>



            <div class="my-3 text-center">
                <?php

                if (!empty($url)): ?>
                    <a href="<?php echo esc_url($url); ?>" class="btn btn-lg btn-outline-dark rounded-pill px-4 py-2" role="button">
                        <?php echo esc_html__('View All', 'kt'); ?>
                        <!-- <i class="bi bi-chevron-right"></i> -->
                    </a>
                <?php endif; ?>
            </div>
        </section>

<?php
        wp_reset_postdata();
        $output = ob_get_clean();
        if (!empty($output)) {
            KT_Cache::set($cache_key, $output, $group, 6 * HOUR_IN_SECONDS);
        }
        return $output;
    });
} else {
    // Admin notice if WPBakery is not active
    add_action('admin_notices', function () {

        echo '<div class="notice notice-warning is-dismissible">
		<p>
			<strong>' . esc_html__('WPBakery Page Builder is not installed or activated.', 'kt') . '</strong>
			' . esc_html__('The Slider Banner element will not work until WPBakery is active.', 'kt') . '
		</p>
	</div>';
    });
}
