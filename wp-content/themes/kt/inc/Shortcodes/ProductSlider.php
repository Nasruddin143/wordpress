<?php

namespace KT\Shortcodes;

use WP_Query;
use KT\Helpers\Price;
use KT\Helpers\Badges;
use KT\Setup\SwiperAssets;

if (!defined('ABSPATH')) exit;

class ProductSlider
{
    public function __construct()
    {
        add_shortcode('vc_product_slider', [$this, 'render']);

        if (defined('WPB_VC_VERSION')) {
            add_action('vc_before_init', [$this, 'map']);
        }
    }

    /**
     * WPBakery Map
     */
    public function map()
    {
        if (!function_exists('vc_map')) return;

        vc_map([
            'name' => __('Product Slider', 'kt'),
            'base' => 'vc_product_slider',
            'category' => __('KT Elements', 'kt'),

            'params' => [

                ['type' => 'textfield', 'heading' => 'Title', 'param_name' => 'title'],

                [
                    'type' => 'dropdown',
                    'heading' => 'Heading Position',
                    'param_name' => 'heading_position',
                    'value' => [
                        'Left' => 'flex-row',
                        'Right' => 'flex-row-reverse',
                    ],
                    'std' => 'flex-row',
                ],

                ['type' => 'textarea', 'heading' => 'Description', 'param_name' => 'desc'],

                ['type' => 'textfield', 'heading' => 'Post Type', 'param_name' => 'post_type'],

                ['type' => 'textfield', 'heading' => 'Items Count', 'param_name' => 'limit', 'value' => 10],

                [
                    'type' => 'dropdown',
                    'heading' => 'Autoplay',
                    'param_name' => 'autoplay',
                    'value' => ['Enable' => 'true', 'Disable' => 'false'],
                ],

                [
                    'type' => 'dropdown',
                    'heading' => 'Loop',
                    'param_name' => 'loop',
                    'value' => ['Enable' => 'true', 'Disable' => 'false'],
                    'std' => 'true',
                ],

                ['type' => 'vc_link', 'heading' => 'View All Link', 'param_name' => 'view_all'],

                ['type' => 'el_id', 'heading' => 'Element ID', 'param_name' => 'el_id'],

                ['type' => 'textfield', 'heading' => 'Extra Class', 'param_name' => 'el_class'],

                ['type' => 'css_editor', 'heading' => 'CSS', 'param_name' => 'css', 'group' => 'Design Options'],
            ],
        ]);
    }

    /**
     * Shortcode Render
     */
    public function render($atts)
    {
        // Load swiper
        SwiperAssets::load_swiper();

        $atts = shortcode_atts([
            'title' => '',
            'heading_position' => 'flex-row',
            'desc' => '',
            'post_type' => 'post',
            'limit' => 10,
            'autoplay' => 'false',
            'loop' => 'true',
            'order' => 'DESC',
            'view_all' => '',
            'el_id' => '',
            'el_class' => '',
            'css' => '',
        ], $atts);

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
            'order' => $atts['order'],
            'no_found_rows' => true,
            'ignore_sticky_posts' => true,
        ]);

        if (!$query->have_posts()) return '';

        $uid = 'kt_slider_' . wp_rand(1000, 9999);
        $link = function_exists('vc_build_link') ? vc_build_link($atts['view_all']) : [];
        $url = $link['url'] ?? '';

        ob_start();
?>

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
                                    <?php if ($discount = Price::show_discount_percent(get_the_ID())): ?>
                                        <span class="discount-badge badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill">
                                            <?php echo esc_html(sprintf(__('%s%% OFF', 'kt'), $discount)); ?>
                                        </span>
                                    <?php endif; ?>

                                    <!-- <button class="wishlist-btn" aria-label="<?php //esc_attr_e('Add to wishlist', 'kt'); 
                                                                                    ?>">
                                        <i class="bi bi-heart mt-1"></i>
                                    </button> -->

                                    <div class="wishlist-btn">
                                        <?php echo Badges::get(get_the_ID()); ?>
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
                                        echo Price::render(get_the_ID(), [
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
        return ob_get_clean();
    }
}
