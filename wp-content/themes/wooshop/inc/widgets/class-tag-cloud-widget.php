<?php
/**
 * Tag Cloud Widget
 *
 * @package WooShop
 */

if (!defined('ABSPATH')) {
    exit;
}

class WooShop_Tag_Cloud_Widget extends WP_Widget
{

    public function __construct()
    {

        parent::__construct(
            'wooshop_tag_cloud',
            __('WooShop - Tag Cloud', 'wooshop'),
            array(
                'description' => __('Displays blog tags.', 'wooshop'),
            )
        );

    }

    /**
     * Frontend
     */
    public function widget($args, $instance)
    {

        $title = !empty($instance['title']) ? $instance['title'] : __('Tags', 'wooshop');
        $number = !empty($instance['number']) ? absint($instance['number']) : 20;
        $hide_empty = !empty($instance['hide_empty']);
        $orderby = !empty($instance['orderby']) ? $instance['orderby'] : 'name';
        $order = !empty($instance['order']) ? $instance['order'] : 'ASC';

        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . esc_html($title) . $args['after_title'];
        }

        $tags = get_tags(
            array(
                'hide_empty' => $hide_empty,
                'number' => $number,
                'orderby' => $orderby,
                'order' => $order,
            )
        );

        if (!empty($tags)) {

            echo '<div class="wooshop-tag-cloud d-flex flex-wrap">';

            foreach ($tags as $tag) {

                echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="tag-cloud-item mb-2 me-2 py-2 px-3 border" title="'.esc_html($tag->name).'">';

                echo esc_html($tag->name);

                echo '</a>';

            }

            echo '</div>';

        }

        echo $args['after_widget'];

    }

    /**
     * Admin Form
     */
    public function form($instance)
    {

        $title = $instance['title'] ?? '';
        $number = $instance['number'] ?? 20;
        $hide_empty = isset($instance['hide_empty']) ? (bool) $instance['hide_empty'] : true;
        $orderby = $instance['orderby'] ?? 'name';
        $order = $instance['order'] ?? 'ASC';
        ?>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Title', 'wooshop'); ?>
            </label>

            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>">
                <?php esc_html_e('Number of Tags', 'wooshop'); ?>
            </label>

            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>"
                name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" min="1" max="100"
                value="<?php echo esc_attr($number); ?>">
        </p>

        <p>
            <input type="checkbox" id="<?php echo esc_attr($this->get_field_id('hide_empty')); ?>"
                name="<?php echo esc_attr($this->get_field_name('hide_empty')); ?>" value="1" <?php checked($hide_empty); ?>>

            <label for="<?php echo esc_attr($this->get_field_id('hide_empty')); ?>">
                <?php esc_html_e('Hide Empty Tags', 'wooshop'); ?>
            </label>
        </p>

        <p>

            <label for="<?php echo esc_attr($this->get_field_id('orderby')); ?>">
                <?php esc_html_e('Order By', 'wooshop'); ?>
            </label>

            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('orderby')); ?>"
                name="<?php echo esc_attr($this->get_field_name('orderby')); ?>">

                <option value="name" <?php selected($orderby, 'name'); ?>>Name</option>
                <option value="count" <?php selected($orderby, 'count'); ?>>Count</option>
                <option value="id" <?php selected($orderby, 'id'); ?>>ID</option>

            </select>

        </p>

        <p>

            <label for="<?php echo esc_attr($this->get_field_id('order')); ?>">
                <?php esc_html_e('Order', 'wooshop'); ?>
            </label>

            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('order')); ?>"
                name="<?php echo esc_attr($this->get_field_name('order')); ?>">

                <option value="ASC" <?php selected($order, 'ASC'); ?>>Ascending</option>
                <option value="DESC" <?php selected($order, 'DESC'); ?>>Descending</option>

            </select>

        </p>

        <?php
    }

    /**
     * Save
     */
    public function update($new, $old)
    {

        $instance = array();

        $instance['title'] = sanitize_text_field($new['title']);
        $instance['number'] = absint($new['number']);
        $instance['hide_empty'] = !empty($new['hide_empty']);
        $instance['orderby'] = sanitize_text_field($new['orderby']);
        $instance['order'] = sanitize_text_field($new['order']);

        return $instance;

    }

}