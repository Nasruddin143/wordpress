<?php
/**
 * WooShop Banner Widget
 * 
 * @package WooShop
 */

if (!defined('ABSPATH')) {
    exit;
}

class WooShop_Banner_Widget extends WP_Widget
{

    public function __construct()
    {

        parent::__construct(
            'wooshop_banner_widget',
            __('WooShop - Banner', 'wooshop'),
            array(
                'description' => __('Sidebar Banner Widget', 'wooshop')
            )
        );

        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
    }

    public function admin_scripts($hook)
    {

        if (!in_array($hook, array('widgets.php', 'customize.php'), true)) {
            return;
        }

        wp_enqueue_media();

        wp_enqueue_script(
            'wooshop-banner-widget',
            get_template_directory_uri() . '/assets/js/banner-widget.js',
            array('jquery'),
            '1.0',
            true
        );
    }

    /*--------------------------------------------------------------
    # Frontend
    --------------------------------------------------------------*/

    public function widget($args, $instance)
    {

        $image_id = !empty($instance['image_id']) ? absint($instance['image_id']) : 0;

        if (!$image_id) {
            return;
        }

        $image = wp_get_attachment_image_url($image_id, 'large');

        $category = $instance['category'] ?? '';
        $heading = $instance['heading'] ?? '';
        $subheading = $instance['subheading'] ?? '';
        $button_text = $instance['button_text'] ?? '';
        $button_url = $instance['button_url'] ?? '#';

        echo $args['before_widget'];
        ?>

        <div class="wooshop-banner">

            <div class="card text-bg-dark border-0">
                <?php echo wp_get_attachment_image(
                    $image_id,
                    'large',
                    false,
                    array(
                        'class' => 'img-fluid w-100',
                        'loading' => 'lazy'
                    )
                ); ?>
                <div class="card-img-overlay d-flex align-content-center flex-wrap">
                    <?php if ($category): ?>

                        <span class="banner-category small w-100 mb-2">

                            <?php echo esc_html($category); ?>

                        </span>

                    <?php endif; ?>

                    <?php if ($subheading): ?>

                        <div class="banner-subheading text-uppercase fw-semibold w-100">

                            <?php echo esc_html($subheading); ?>

                        </div>

                    <?php endif; ?>

                    <?php if ($heading): ?>

                        <h2 class="banner-heading w-100 fw-bold text-white">

                            <?php echo esc_html($heading); ?>

                        </h2>

                    <?php endif; ?>


                    <?php if ($button_text): ?>
                        <div class="banner-button">
                            <a href="<?php echo esc_url($button_url); ?>" class="btn btn-outline-light" title="<?php echo esc_attr($button_text); ?>">

                                <?php echo esc_html($button_text); ?>

                            </a>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

        </div>

        <?php

        echo $args['after_widget'];

    }

    /*--------------------------------------------------------------
    # Widget Form
    --------------------------------------------------------------*/

    public function form($instance)
    {

        $image_id = $instance['image_id'] ?? '';
        $category = $instance['category'] ?? '';
        $heading = $instance['heading'] ?? '';
        $subheading = $instance['subheading'] ?? '';
        $button_text = $instance['button_text'] ?? '';
        $button_url = $instance['button_url'] ?? '';

        $image = '';

        if ($image_id) {
            $image = wp_get_attachment_image_url($image_id, 'medium');
        }

        ?>

        <p>

            <input type="hidden" class="banner-image-id" name="<?php echo esc_attr($this->get_field_name('image_id')); ?>"
                value="<?php echo esc_attr($image_id); ?>">

            <button type="button" class="button select-banner-image">
                <?php esc_html_e('Select Image', 'wooshop'); ?>
            </button>

            <button type="button" class="button remove-banner-image">
                <?php esc_html_e('Remove Image', 'wooshop'); ?>
            </button>

        </p>

        <div class="banner-preview">

            <?php
            if ($image_id) {
                echo wp_get_attachment_image(
                    $image_id,
                    'medium',
                    false,
                    array(
                        'style' => 'max-width:100%;height:auto;'
                    )
                );
            }
            ?>

        </div>

        <p>

            <label>Category</label>

            <input class="widefat" type="text" name="<?php echo $this->get_field_name('category'); ?>"
                value="<?php echo esc_attr($category); ?>">

        </p>

        <p>

            <label>Heading</label>

            <input class="widefat" type="text" name="<?php echo $this->get_field_name('heading'); ?>"
                value="<?php echo esc_attr($heading); ?>">

        </p>

        <p>

            <label>Sub Heading</label>

            <textarea class="widefat" rows="3"
                name="<?php echo $this->get_field_name('subheading'); ?>"><?php echo esc_textarea($subheading); ?></textarea>

        </p>

        <p>

            <label>Button Text</label>

            <input class="widefat" type="text" name="<?php echo $this->get_field_name('button_text'); ?>"
                value="<?php echo esc_attr($button_text); ?>">

        </p>

        <p>

            <label>Button URL</label>

            <input class="widefat" type="url" name="<?php echo $this->get_field_name('button_url'); ?>"
                value="<?php echo esc_attr($button_url); ?>">

        </p>

        <?php
    }

    /*--------------------------------------------------------------
    # Save
    --------------------------------------------------------------*/

    public function update($new, $old)
    {

        $instance = [];

        $instance['image_id'] = absint($new['image_id']);
        $instance['category'] = sanitize_text_field($new['category']);
        $instance['heading'] = sanitize_text_field($new['heading']);
        $instance['subheading'] = sanitize_textarea_field($new['subheading']);
        $instance['button_text'] = sanitize_text_field($new['button_text']);
        $instance['button_url'] = esc_url_raw($new['button_url']);

        return $instance;

    }

}