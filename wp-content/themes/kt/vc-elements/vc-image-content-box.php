<?php
if (!defined('ABSPATH'))
    exit;

/*
|--------------------------------------------------------------------------
| ELEMENT 3: Welcome Section (WPBakery)
|--------------------------------------------------------------------------
*/
if (defined('WPB_VC_VERSION')) {

    add_action('vc_before_init', function () {

        vc_map([
            'name' => esc_html__('Welcome Section', 'kt'),
            'base' => 'welcome_section',
            'icon' => 'dashicons-welcome-learn-more',
            'category' => esc_html__('Custom Components', 'kt'),
            'description' => esc_html__(
                'Displays a welcome section with title, description, button, and image',
                'kt'
            ),

            'params' => [

                [
                    'type' => 'textfield',
                    'heading' => esc_html__('Sub Title', 'kt'),
                    'param_name' => 'small_text',
                ],

                [
                    'type' => 'textfield',
                    'heading' => esc_html__('Title', 'kt'),
                    'param_name' => 'title',
                    'admin_label' => true,
                ],

                [
                    'type' => 'dropdown',
                    'heading' => esc_html__('Title Tag', 'kt'),
                    'param_name' => 'title_tag',
                    'value' => [
                        esc_html__('H1', 'kt') => 'h1',
                        esc_html__('H2', 'kt') => 'h2',
                        esc_html__('H3', 'kt') => 'h3',
                        esc_html__('H4', 'kt') => 'h4',
                        esc_html__('H5', 'kt') => 'h5',
                        esc_html__('H6', 'kt') => 'h6',
                    ],
                    'std' => 'h2',
                ],

                [
                    'type' => 'textarea',
                    'heading' => esc_html__('Description', 'kt'),
                    'param_name' => 'description',
                ],

                [
                    'type' => 'textfield',
                    'heading' => esc_html__('Button Text', 'kt'),
                    'param_name' => 'button_text',
                    'value' => esc_html__('Learn More', 'kt'),
                ],

                [
                    'type' => 'vc_link',
                    'heading' => esc_html__('Button Link', 'kt'),
                    'param_name' => 'button_link',
                ],

                [
                    'type' => 'attach_image',
                    'heading' => esc_html__('Section Image', 'kt'),
                    'param_name' => 'right_image',
                ],

                [
                    'type' => 'dropdown',
                    'heading' => esc_html__('Image Position', 'kt'),
                    'param_name' => 'image_position',
                    'value' => [
                        esc_html__('Right', 'kt') => 'right',
                        esc_html__('Left', 'kt') => 'left',
                    ],
                    'std' => 'right',
                ],

                [
                    'type' => 'el_id',
                    'heading' => esc_html__('Element ID', 'kt'),
                    'param_name' => 'el_id',
                    'description' => sprintf(
                        esc_html__(
                            'Enter element ID (Note: make sure it is unique and valid according to %1$sW3C specification%2$s).',
                            'kt'
                        ),
                        '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">',
                        '</a>'
                    ),
                ],

                [
                    'type' => 'textfield',
                    'heading' => esc_html__('Extra class name', 'kt'),
                    'param_name' => 'el_class',
                    'description' => esc_html__(
                        'Style particular content element differently – add a class name and refer to it in custom CSS.',
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


    add_shortcode('welcome_section', 'kt_render_welcome_section');

    function kt_render_welcome_section($atts)
    {
        $atts = shortcode_atts([
            'small_text' => '',
            'title' => '',
            'title_tag' => 'h2',
            'description' => '',
            'button_text' => esc_html__('Learn More', 'kt'),
            'button_link' => '',
            'right_image' => '',
            'image_position' => 'right',
            'css' => '',
        ], $atts);

        /* Button link */
        $link = !empty($atts['button_link']) ? vc_build_link($atts['button_link']) : [];
        $url = $link['url'] ?? '';
        $target = !empty($link['target']) ? ' target="' . esc_attr($link['target']) . '"' : '';
        $rel = !empty($link['rel']) ? ' rel="' . esc_attr($link['rel']) . '"' : '';

        /* Title tag validation */
        $tag = in_array($atts['title_tag'], ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'], true)
            ? $atts['title_tag']
            : 'h2';

        /* Column order */
        $text_order = ($atts['image_position'] === 'left') ? 'order-md-2' : 'order-md-1';
        $image_order = ($atts['image_position'] === 'left') ? 'order-md-1' : 'order-md-2';

        /* Image markup */
        $image_html = '';
        if (!empty($atts['right_image'])) {

            $alt = get_post_meta($atts['right_image'], '_wp_attachment_image_alt', true);
            if (empty($alt)) {
                $alt = !empty($atts['title'])
                    ? $atts['title']
                    : esc_html__('Welcome Image', 'kt');
            }

            $image_html = wp_get_attachment_image(
                $atts['right_image'],
                'kt-square',
                false,
                [
                    'class' => 'img-fluid rounded',
                    'alt' => esc_attr($alt),
                    'loading' => 'lazy',
                    'decoding' => 'async',
                ]
            );
        }

        $el_id = !empty($atts['el_id']) ? 'id="' . esc_attr($atts['el_id']) . '"' : '';
        $el_class = !empty($atts['el_class']) ? esc_attr($atts['el_class']) : '';
        $css_class = vc_shortcode_custom_css_class($atts['css'], ' ');


        ob_start(); ?>

        <section <?php echo $el_id; ?> class="welcome-section py-5 <?php echo $el_class . $css_class; ?>">

            <div class="container">

                <div class="row g-5 align-items-center">

                    <?php if ($image_html): ?>
                        <div class="col-lg-5 col-md-5 d-none d-md-block text-center <?php echo esc_attr($image_order); ?>">
                            <?php echo $image_html; ?>
                        </div>
                    <?php endif; ?>

                    <div class="col-lg-7 col-md-7 col-sm-12 d-grid row-gap-4 <?php echo esc_attr($text_order); ?>">

                        <?php if (!empty($atts['small_text'])): ?>
                            <span class="text-uppercase text-danger fw-bold d-block">
                                <?php echo esc_html($atts['small_text']); ?>
                            </span>
                        <?php endif; ?>

                        <<?php echo esc_html($tag); ?> class="mb-0 fw-semibold">
                            <?php echo esc_html($atts['title']); ?>
                        </<?php echo esc_html($tag); ?>>

                        <?php if (!empty($atts['description'])): ?>
                            <p class="mb-2">
                                <?php echo wp_kses_post($atts['description']); ?>
                            </p>
                        <?php endif; ?>

                        <?php if (!empty($atts['button_text']) && $url): ?>
                            <a href="<?php echo esc_url($url); ?>" class="kt-btn-inverse" <?php echo $target . $rel; ?>
                                role="button">
                                <?php echo esc_html($atts['button_text']); ?>
                                <i class="bi bi-arrow-right-short" aria-hidden="true"></i>
                            </a>
                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </section>

        <?php
        return ob_get_clean();
    }


} else {

    // Admin notice if WPBakery is not active
    add_action('admin_notices', function () {

        echo '<div class="notice notice-warning is-dismissible">
		<p>
			<strong>' . esc_html('WPBakery Page Builder is not installed or activated.') . '</strong>
			' . esc_html('The Slider Banner element will not work until WPBakery is active.') . '
		</p>
	</div>';

    });
}
