<?php

namespace KT\Shortcodes;

use WP_Query;

if (!defined('ABSPATH')) exit;

class SliderBanner
{
    public function __construct()
    {
        // Shortcode
        add_shortcode('slider_banner', [$this, 'render']);

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
            'name' => __('Slider Banner', 'kt'),
            'base' => 'slider_banner',
            'icon' => 'dashicons-format-gallery',
            'category' => __('KT Elements', 'kt'),

            'params' => [
                [
                    'type' => 'textfield',
                    'heading' => 'Number of Slides',
                    'param_name' => 'count',
                    'value' => 5
                ],
                [
                    'type' => 'dropdown',
                    'heading' => 'Autoplay',
                    'param_name' => 'autoplay',
                    'value' => [
                        'Enable' => 'true',
                        'Disable' => 'false'
                    ],
                    'std' => 'true'
                ],
                [
                    'type' => 'textfield',
                    'heading' => 'Autoplay Speed (ms)',
                    'param_name' => 'speed',
                    'value' => 4000
                ],
                [
                    'type' => 'dropdown',
                    'heading' => 'Show Dots',
                    'param_name' => 'dots',
                    'value' => [
                        'Yes' => 'true',
                        'No' => 'false'
                    ],
                    'std' => 'true'
                ],
                [
                    'type' => 'dropdown',
                    'heading' => 'Show Arrows',
                    'param_name' => 'arrows',
                    'value' => [
                        'Yes' => 'true',
                        'No' => 'false'
                    ],
                    'std' => 'true'
                ],
            ]
        ]);
    }

    /**
     * Shortcode Render
     */
    public function render($atts)
    {
        $atts = shortcode_atts([
            'count' => 5,
            'autoplay' => 'true',
            'speed' => 4000,
            'dots' => 'true',
            'arrows' => 'true',
            'loop' => 'true',
            'pause' => 'true',
        ], $atts, 'slider_banner');

        $query = new WP_Query([
            'post_type' => 'slider',
            'posts_per_page' => (int) $atts['count'],
            'post_status' => 'publish',
            'no_found_rows' => true,
            'ignore_sticky_posts' => true,

            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,

            'cache_results' => true,
        ]);

        ob_start();

        if ($query->have_posts()) :

            $slider_id = 'kt_slider_' . wp_rand(1000, 9999);
?>

            <section id="<?php echo esc_attr($slider_id); ?>" class="carousel slide" data-carousel-options='<?php echo esc_attr(wp_json_encode([
                                                                                                                'interval' => (int) $atts['speed'],
                                                                                                                'ride' => ($atts['autoplay'] === 'true') ? 'carousel' : false,
                                                                                                                'wrap' => ($atts['loop'] === 'true'),
                                                                                                                'pause' => ($atts['pause'] === 'true') ? 'hover' : false,
                                                                                                            ])); ?>'>

                <div class="carousel-inner">

                    <?php
                    $i = 0;
                    while ($query->have_posts()):
                        $query->the_post();

                        $image_id = get_post_thumbnail_id();
                        $title = get_the_title();
                        $excerpt = wp_trim_words(get_the_excerpt(), 20);
                        $slide_url = function_exists('get_field')
                            ? get_field('slide_url')
                            : get_post_meta(get_the_ID(), 'slide_url', true);

                    ?>

                        <div class="carousel-item <?php echo ($i === 0) ? 'active' : ''; ?>">

                            <?php
                            if ($image_id) {
                                $image = wp_get_attachment_image(
                                    $image_id,
                                    'kt-hero',
                                    false,
                                    [
                                        'class' => 'img-fluid',
                                        'alt' => esc_attr($title),
                                        'title' => esc_attr($title),
                                        'loading' => ($i === 0) ? 'eager' : 'lazy',
                                        'decoding' => 'async',
                                        'fetchpriority' => ($i === 0) ? 'high' : 'auto',
                                    ]
                                );

                                if (!empty($slide_url)) {
                                    echo '<a href="' . esc_url($slide_url) . '" class="kt-slide-link" aria-label="' . esc_attr($title) . '">';
                                    echo $image;
                                    echo '</a>';
                                } else {
                                    echo $image;
                                }
                            }
                            ?>

                            <!-- <div class="w-50 h-100 mini-blog float-left position-absolute top-0 start-0 d-flex align-items-center"> -->
                            <div class="w-100 h-100 float-left position-absolute top-0 start-0 d-flex align-items-center">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-8 col-sm-12 d-sm-none d-md-block d-grid row-gap-3">

                                            <h2 class="h1 fw-bold text-white"><?php echo esc_html($title); ?></h2>
                                            <p class="lead text-white"><?php echo esc_html($excerpt); ?></p>

                                            <?php if (!empty($slide_url)): ?>

                                                <div>
                                                    <a href="<?php echo esc_url($slide_url); ?>" class="btn btn-lg btn-outline-light rounded-pill px-4 py-2" role="button" title="<?php echo esc_html('Shop Products', 'kt'); ?>">
                                                        <?php echo esc_html('Shop Products', 'kt'); ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php
                        $i++;
                    endwhile;
                    ?>

                </div>

                <?php if ($atts['arrows'] === 'true'): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#<?php echo esc_attr($slider_id); ?>"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">
                            <?php esc_html_e('Previous', 'kt'); ?>
                        </span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#<?php echo esc_attr($slider_id); ?>"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden">
                            <?php esc_html_e('Next', 'kt'); ?>
                        </span>
                    </button>
                <?php endif; ?>

                <?php if ($atts['dots'] === 'true'): ?>
                    <div class="carousel-indicators">
                        <?php for ($n = 0; $n < $i; $n++): ?>
                            <button type="button" aria-label="Go to slide <?php echo esc_attr($n + 1); ?>"
                                data-bs-target="#<?php echo esc_attr($slider_id); ?>" data-bs-slide-to="<?php echo esc_attr($n); ?>"
                                class="<?php echo ($n === 0) ? 'active' : ''; ?>">
                            </button>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

            </section>

<?php
        endif;

        wp_reset_postdata();

        return ob_get_clean();
    }
}
