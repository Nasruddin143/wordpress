<?php
/**
 * Blog Categories Widget
 *
 * @package WooShop
 */

if (!defined('ABSPATH')) {
    exit;
}

class WooShop_Blog_Categories_Widget extends WP_Widget
{

    public function __construct()
    {

        parent::__construct(
            'wooshop_blog_categories',
            __('WooShop - Blog Categories', 'wooshop'),
            array('description' => __('Displays blog categories.', 'wooshop'), )
        );
    }

    /**
     * Frontend
     */
    public function widget($args, $instance)
    {

        $title = !empty($instance['title']) ? $instance['title'] : __('Categories', 'wooshop');
        $show_count = !empty($instance['show_count']);
        $hide_empty = !empty($instance['hide_empty']);
        $order_by = !empty($instance['orderby']) ? $instance['orderby'] : 'name';
        $order = !empty($instance['order']) ? $instance['order'] : 'ASC';

        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . esc_html($title) . $args['after_title'];
        }

        $categories = get_categories(
            array(
                'taxonomy' => 'category',
                'hide_empty' => $hide_empty,
                'orderby' => $order_by,
                'order' => $order,
            )
        );

        if (!empty($categories)) {

            echo '<ul class="wooshop-blog-categories">';

            foreach ($categories as $category) {

                $active = '';

                if (is_category($category->term_id)) {
                    $active = ' active';
                }

                echo '<li class="cat-item' . esc_attr($active) . '">';

                echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" title="' . esc_html($category->name) . '" class="d-flex justify-content-between align-items-center">';

                echo esc_html($category->name);

                if ($show_count) {

                    echo '<span>';

                    echo intval($category->count);

                    echo '</span>';

                }

                echo '</a>';

                echo '</li>';

            }

            echo '</ul>';

        }

        echo $args['after_widget'];

    }

    /**
     * Admin Form
     */
    public function form($instance)
    {

        $title = isset($instance['title']) ? $instance['title'] : '';

        $show_count = isset($instance['show_count']) ? $instance['show_count'] : true;

        $hide_empty = isset($instance['hide_empty']) ? $instance['hide_empty'] : true;

        $orderby = isset($instance['orderby']) ? $instance['orderby'] : 'name';

        $order = isset($instance['order']) ? $instance['order'] : 'ASC';

        ?>

        <p>

            <label>Title</label>

            <input class="widefat" type="text" name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                value="<?php echo esc_attr($title); ?>">

        </p>

        <p>

            <input type="checkbox" <?php checked($show_count); ?>
                name="<?php echo esc_attr($this->get_field_name('show_count')); ?>">

            Show Count

        </p>

        <p>

            <input type="checkbox" <?php checked($hide_empty); ?>
                name="<?php echo esc_attr($this->get_field_name('hide_empty')); ?>">

            Hide Empty Categories

        </p>

        <p>

            <label>Order By</label>

            <select class="widefat" name="<?php echo esc_attr($this->get_field_name('orderby')); ?>">

                <option value="name" <?php selected($orderby, 'name'); ?>>Name</option>

                <option value="count" <?php selected($orderby, 'count'); ?>>Count</option>

                <option value="id" <?php selected($orderby, 'id'); ?>>ID</option>

            </select>

        </p>

        <p>

            <label>Order</label>

            <select class="widefat" name="<?php echo esc_attr($this->get_field_name('order')); ?>">

                <option value="ASC" <?php selected($order, 'ASC'); ?>>Ascending</option>

                <option value="DESC" <?php selected($order, 'DESC'); ?>>Descending</option>

            </select>

        </p>

        <?php

    }

    /**
     * Save
     */
    public function update($new_instance, $old_instance)
    {

        $instance = array();

        $instance['title'] = sanitize_text_field($new_instance['title']);

        $instance['show_count'] = !empty($new_instance['show_count']);

        $instance['hide_empty'] = !empty($new_instance['hide_empty']);

        $instance['orderby'] = sanitize_text_field($new_instance['orderby']);

        $instance['order'] = sanitize_text_field($new_instance['order']);

        return $instance;

    }

}