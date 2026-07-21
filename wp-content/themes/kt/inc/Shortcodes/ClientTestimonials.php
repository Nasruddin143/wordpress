<?php

namespace KT\Shortcodes;

use KT\Setup\SwiperAssets;
use WP_Query;

if (!defined('ABSPATH')) exit;

class ClientTestimonials
{
    public function __construct()
    {
        // Shortcode
        add_shortcode('client_testimonials', [$this, 'render']);

        // WPBakery mapping
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
            'name' => __('Client Testimonials', 'kt'),
            'base' => 'client_testimonials',
            'icon' => 'dashicons-testimonial',
            'category' => __('KT Elements', 'kt'),

            'params' => [

                [
                    'type' => 'textfield',
                    'heading' => 'Title',
                    'param_name' => 'title',
                ],

                [
                    'type' => 'textarea',
                    'heading' => 'Description',
                    'param_name' => 'desc',
                ],

                [
                    'type' => 'textfield',
                    'heading' => 'Number of Testimonials',
                    'param_name' => 'count',
                    'value' => 10,
                ],

                [
                    'type' => 'dropdown',
                    'heading' => 'Autoplay',
                    'param_name' => 'autoplay',
                    'value' => [
                        'Enable' => 'true',
                        'Disable' => 'false',
                    ],
                    'std' => 'true',
                ],

                [
                    'type' => 'textfield',
                    'heading' => 'Autoplay Speed (ms)',
                    'param_name' => 'speed',
                    'value' => 4000,
                ],

                [
                    'type' => 'dropdown',
                    'heading' => 'Loop',
                    'param_name' => 'loop',
                    'value' => [
                        'Enable' => 'true',
                        'Disable' => 'false',
                    ],
                    'std' => 'true',
                ],

                [
                    'type' => 'textfield',
                    'heading' => 'Google Review URL',
                    'param_name' => 'btn_google_review',
                ],
            ]
        ]);
    }

    /**
     * Shortcode Render
     */
    public function render($atts)
    {
        // Ensure swiper is loaded
        SwiperAssets::load_swiper();

        $atts = shortcode_atts([
            'title' => 'What our clients say?',
            'desc' => '',
            'count' => 10,
            'autoplay' => 'true',
            'speed' => 4000,
            'loop' => 'true',
            'btn_google_review' => '',
        ], $atts);

        $count = min((int) $atts['count'], 12);

        $query = new WP_Query([
            'post_type' => 'testimonial',
            'posts_per_page' => $count,
            'post_status' => 'publish',
            'no_found_rows' => true,
            'ignore_sticky_posts' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'cache_results' => true,
        ]);

        if (!$query->have_posts()) {
            return '';
        }

        $uid = 'kt_testimonial_' . wp_rand(1000, 9999);

        ob_start();
?>

        <section class="kt-slider-carousel kt-testimonials py-5">

            <div class="row justify-content-center mb-4">
                <div class="col-12 col-md-8">
                    <div class="d-grid row-gap-3 text-center">

                        <?php if ($atts['title']): ?>
                            <h2 class="fw-bold mb-0">
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

            <div class="position-relative px-4">
                <div class="kt-swiper swiper" data-autoplay="<?php echo esc_attr($atts['autoplay']); ?>"
                    data-loop="<?php echo esc_attr($atts['loop']); ?>" data-speed="<?php echo esc_attr($atts['speed']); ?>"
                    id="<?php echo esc_attr($uid); ?>">

                    <div class="swiper-wrapper">

                        <?php while ($query->have_posts()):
                            $query->the_post(); ?>

                            <div class="swiper-slide">
                                <div class="card h-100 kt-testimonial-card border-0 shadow-sm">
                                    <div class="card-body">

                                        <div class="d-flex mb-3 align-items-center">
                                            <?php
                                            if (has_post_thumbnail()) {
                                                echo wp_get_attachment_image(
                                                    get_post_thumbnail_id(),
                                                    'kt-thumb',
                                                    false,
                                                    [
                                                        'class' => 'rounded-circle img-fluid me-3 testimonial-avatar',
                                                        'alt' => esc_attr(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true) ?: get_the_title()),
                                                        'loading' => 'lazy',
                                                        'title' => esc_attr(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true) ?: get_the_title()),
                                                    ]
                                                );
                                            }
                                            ?>

                                            <div class="lh-1">
                                                <h3 class="h5 fw-bold mb-1"><?php the_title(); ?></h3>
                                                <small class="text-muted small">
                                                    <?php echo esc_html(get_the_date()); ?>
                                                </small>
                                            </div>

                                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/g.webp'); ?>"
                                                title="<?php esc_attr_e('Google Logo', 'kt'); ?>"
                                                alt="<?php esc_attr_e('Google Logo', 'kt'); ?>" class="ms-auto img-fluid mb-auto"
                                                width="32" height="32">
                                        </div>

                                        <?php
                                        /* Rating system */
                                        $rating = function_exists('get_field')
                                            ? floatval(get_field('rating', get_the_ID()))
                                            : floatval(get_post_meta(get_the_ID(), 'rating', true));

                                        $full_stars = floor($rating);
                                        $half_star = ($rating - $full_stars) >= 0.5;
                                        $unique = uniqid('star_');
                                        ?>

                                        <div class="mb-2" role="img"
                                            aria-label="<?php printf(esc_attr__('Customer rating: %s out of 5 stars', 'kt'), esc_attr($rating)); ?>">
                                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                                <?php if ($s <= $full_stars): ?>
                                                    <svg width="20" height="20" fill="#f7c000" viewBox="0 0 24 24">
                                                        <path d="M12 .587l3.668 7.568L24 9.748l-6 5.848L19.335 24 
                                                12 19.897 4.665 24 6 15.596 0 9.748l8.332-1.593z" />
                                                    </svg>

                                                <?php elseif ($half_star && $s == $full_stars + 1): ?>
                                                    <svg width="20" height="20" viewBox="0 0 24 24">
                                                        <defs>
                                                            <linearGradient id="<?php echo esc_attr($unique . '_' . $s); ?>">
                                                                <stop offset="50%" stop-color="#f7c000" />
                                                                <stop offset="50%" stop-color="#ccc" />
                                                            </linearGradient>
                                                        </defs>
                                                        <path fill="url(#<?php echo esc_attr($unique . '_' . $s); ?>)" d="M12 .587l3.668 7.568L24 9.748l-6 5.848L19.335 24 
                                                12 19.897 4.665 24 6 15.596 0 9.748l8.332-1.593z" />
                                                    </svg>

                                                <?php else: ?>
                                                    <svg width="20" height="20" fill="#ccc" viewBox="0 0 24 24">
                                                        <path d="M12 .587l3.668 7.568L24 9.748l-6 5.848L19.335 24 
                                                12 19.897 4.665 24 6 15.596 0 9.748l8.332-1.593z" />
                                                    </svg>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>

                                        <p class="m-0">
                                            <?php echo esc_html(wp_trim_words(strip_shortcodes(get_the_content()), 30)); ?>
                                        </p>


                                        <?php if ($role = get_post_meta(get_the_ID(), 'client_role', true)): ?>
                                            <small class="text-muted">
                                                <?php echo esc_html($role); ?>
                                            </small>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>

                        <?php endwhile; ?>
                    </div>
                </div>

                <div class="swiper-button-prev custom-nav"></div>
                <div class="swiper-button-next custom-nav"></div>
            </div>

            <div class="text-center mt-4">
                <div class="google-reviews">

                    <?php if ($atts['btn_google_review']): ?>
                        <a href="<?php echo esc_html($atts['btn_google_review']); ?>" target="_blank" rel="noopener nofollow"
                            title="Review us on Google" class="btn btn-outline-primary rounded-pill px-3 py-2" role="button">
                            Review us on <i class="bi bi-google ms-2"></i>
                        </a>
                        <!-- https://g.page/r/CZotEk8gycx6EAE/review -->
                    <?php endif; ?>

                </div>
            </div>
        </section>

<?php
        wp_reset_postdata();

        return ob_get_clean();
    }
}
