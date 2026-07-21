<?php require_once get_template_directory() . '/vc-elements/swiper-assets.php';

if (!defined('ABSPATH')) {
    exit;
}

/*--------------------------------------------------------------
 WPBakery Element
--------------------------------------------------------------*/
if (defined('WPB_VC_VERSION')) {

    add_action('vc_before_init', function () {

        vc_map([
            'name' => __('Brands We Sell', 'kt'),
            'base' => 'brands_we_sell',
            'category' => __('Custom Components', 'kt'),
            'description' => __('Brand logo carousel (Swiper)', 'kt'),

            'params' => [

                [
                    'type' => 'textfield',
                    'heading' => __('Widget Title', 'kt'),
                    'param_name' => 'title',
                    'admin_label' => true,
                ],

                [
                    'type' => 'textarea',
                    'heading' => __('Description', 'kt'),
                    'param_name' => 'desc',
                ],

                [
                    'type' => 'css_editor',
                    'heading' => __('CSS box', 'kt'),
                    'param_name' => 'css',
                    'group' => __('Design Options', 'kt'),
                ],

                [
                    'type' => 'param_group',
                    'heading' => __('Brand Items', 'kt'),
                    'param_name' => 'brands',
                    'params' => [

                        [
                            'type' => 'attach_image',
                            'heading' => __('Brand Logo', 'kt'),
                            'param_name' => 'brand_image',
                        ],

                        [
                            'type' => 'textfield',
                            'heading' => __('Brand Link', 'kt'),
                            'param_name' => 'brand_link',
                        ],
                    ],
                ],

                [
                    'type' => 'dropdown',
                    'heading' => __('Loop', 'kt'),
                    'param_name' => 'loop',
                    'value' => [
                        __('Yes', 'kt') => 'true',
                        __('No', 'kt') => 'false',
                    ],
                    'std' => 'true',
                    'group' => __('Slider Settings', 'kt'),
                ],

                [
                    'type' => 'dropdown',
                    'heading' => __('Autoplay', 'kt'),
                    'param_name' => 'autoplay',
                    'value' => [
                        __('Yes', 'kt') => 'true',
                        __('No', 'kt') => 'false',
                    ],
                    'std' => 'true',
                    'group' => __('Slider Settings', 'kt'),
                ],

                [
                    'type' => 'textfield',
                    'heading' => __('Autoplay Delay (ms)', 'kt'),
                    'param_name' => 'autoplay_timeout',
                    'std' => '3000',
                    'group' => __('Slider Settings', 'kt'),
                ],

                [
                    'type' => 'textfield',
                    'heading' => __('Items Mobile', 'kt'),
                    'param_name' => 'items_mobile',
                    'std' => '2',
                    'group' => __('Responsive', 'kt'),
                ],

                [
                    'type' => 'textfield',
                    'heading' => __('Items Tablet', 'kt'),
                    'param_name' => 'items_tablet',
                    'std' => '4',
                    'group' => __('Responsive', 'kt'),
                ],

                [
                    'type' => 'textfield',
                    'heading' => __('Items Desktop', 'kt'),
                    'param_name' => 'items_desktop',
                    'std' => '6',
                    'group' => __('Responsive', 'kt'),
                ],
            ],
        ]);
    });

    /*--------------------------------------------------------------
     Shortcode
    --------------------------------------------------------------*/
    add_shortcode('brands_we_sell', function ($atts) {

        kt_ensure_swiper_loaded();

        $atts = shortcode_atts([
            'title' => '',
            'desc' => '',
            'brands' => '',
            'loop' => 'true',
            'autoplay' => 'true',
            'autoplay_timeout' => 3000,
            'items_mobile' => 2,
            'items_tablet' => 4,
            'items_desktop' => 6,
            'css' => '',
        ], $atts, 'brands_we_sell');

        $brands = vc_param_group_parse_atts($atts['brands']);

        $group = 'kt_brand_slider';
        $cache_key = $group . md5(serialize($atts));
        $cached = KT_Cache::get($cache_key, $group);

        if ($cached !== false) {
            return $cached;
        }
        $uid = 'wp_' . substr($cache_key, 0, 50);
        $css_class = vc_shortcode_custom_css_class($atts['css'], ' ');

        ob_start(); ?>

        <section class="kt-slider-carousel kt-brands brands-we-sell py-5 <?php echo esc_attr($css_class); ?>">

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


            <!-- <div class="d-flex justify-content-center mb-4">
                        <div class="carousel-nav slider-prev" aria-label="<?php //esc_attr_e('Previous slide', 'kt'); 
                                                                            ?>">
                            <i class="bi bi-arrow-left-short fs-3"></i>
                        </div>

                        <div class="carousel-nav slider-next" aria-label="<?php //esc_attr_e('Next slide', 'kt'); 
                                                                            ?>">
                            <i class="bi bi-arrow-right-short fs-3"></i>
                        </div>
                    </div> -->


            <div id="<?php echo esc_attr($uid); ?>" class="kt-swiper swiper"
                data-loop="<?php echo esc_attr($atts['loop']); ?>" data-autoplay="<?php echo esc_attr($atts['autoplay']); ?>">

                <div class="swiper-wrapper">

                    <?php
                    if (!empty($brands)):
                        foreach ($brands as $b):

                            if (empty($b['brand_image'])) {
                                continue;
                            }
                    ?>

                            <div class="swiper-slide text-center">
                                <div class="py-3 px-4 bg-light rounded">

                                    <?php if (!empty($b['brand_link'])): ?>
                                        <a href="<?php echo esc_url($b['brand_link']); ?>" target="_blank" rel="noopener nofollow"
                                            aria-label="<?php esc_attr_e('Visit brand website', 'kt'); ?>" title="<?php esc_attr_e('Visit brand website', 'kt'); ?>">
                                        <?php endif; ?>

                                        <?php
                                        echo wp_get_attachment_image(
                                            $b['brand_image'],
                                            'kt-brand',
                                            false,
                                            [
                                                'class' => 'img-fluid',
                                                'loading' => 'lazy',
                                                'alt' => esc_attr(get_post_meta($b['brand_image'], '_wp_attachment_image_alt', true) ?: 'Brand logo'),
                                                'title' => esc_attr(get_post_meta($b['brand_image'], '_wp_attachment_image_alt', true) ?: 'Brand logo'),
                                            ]
                                        );
                                        ?>

                                        <?php if (!empty($b['brand_link'])): ?>
                                        </a>
                                    <?php endif; ?>

                                </div>
                            </div>

                    <?php endforeach;
                    endif;
                    ?>

                </div>

                <div class="swiper-button-prev custom-nav"></div>
                <div class="swiper-button-next custom-nav"></div>
            </div>


        </section>

<?php
        return ob_get_clean();
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
