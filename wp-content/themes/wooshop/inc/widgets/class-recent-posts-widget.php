<?php
/**
 * Recent Posts Widget
 *
 * @package WooShop
 */

if (!defined('ABSPATH')) {
    exit;
}

class WooShop_Recent_Posts_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'wooshop_recent_posts',
            __('WooShop - Recent Posts', 'wooshop'),
            array(
                'description' => __('Displays recent blog posts.', 'wooshop'),
            )
        );
    }

    /**
     * Frontend
     */
    public function widget($args, $instance)
    {

        $title = !empty($instance['title']) ? $instance['title'] : __('Recent Posts', 'wooshop');
        $posts_per_page = !empty($instance['posts_per_page']) ? absint($instance['posts_per_page']) : 5;
        $show_image = !empty($instance['show_image']);
        $show_date = !empty($instance['show_date']);
        $show_author = !empty($instance['show_author']);
        $show_excerpt = !empty($instance['show_excerpt']);
        $excerpt_length = !empty($instance['excerpt_length']) ? absint($instance['excerpt_length']) : 15;

        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . esc_html($title) . $args['after_title'];
        }

        $posts = get_posts(array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => $posts_per_page,
            'orderby' => 'date',
            'order' => 'DESC',
            'ignore_sticky_posts' => true,
            'no_found_rows' => true,
            'update_post_meta_cache' => true,
            'update_post_term_cache' => false,
        ));

        if (!empty($posts)) {

            echo '<ul class="wooshop-recent-posts">';

            foreach ($posts as $post) {

                $post_id = $post->ID;

                echo '<li class="recent-post d-flex mb-3">';

                /**
                 * Featured Image
                 */
                if ($show_image) {

                    echo '<a class="recent-post-thumb me-3" href="' . esc_url(get_permalink($post_id)) . '" title="' . esc_html(get_the_title($post_id)) . '">';

                    if (has_post_thumbnail($post_id)) {

                        echo get_the_post_thumbnail(
                            $post_id,
                            'thumbnail',
                            array(
                                'class' => 'img-fluid',
                                'loading' => 'lazy',
                                'alt' => esc_html(get_the_title($post_id))
                            )
                        );

                    } else {

                        echo '<img src="' .
                            esc_url(get_template_directory_uri() . '/assets/images/placeholder.svg') .
                            '" class="img-fluid rounded" alt="" width="80">';

                    }

                    echo '</a>';

                }

                echo '<div class="recent-post-content flex-grow-1">';

                /**
                 * Title
                 */

                echo '<a href="' . esc_url(get_permalink($post_id)) . '">';
                echo esc_html(get_the_title($post_id));
                echo '</a>';


                /**
                 * Date + Author
                 */
                if ($show_date || $show_author) {

                    echo '<small class="text-muted d-block mb-2">';

                    if ($show_date) {

                        echo esc_html(get_the_date('', $post_id));

                    }

                    if ($show_date && $show_author) {

                        echo ' | ';

                    }

                    if ($show_author) {

                        echo esc_html__('By ', 'wooshop');
                        echo esc_html(
                            get_the_author_meta(
                                'display_name',
                                $post->post_author
                            )
                        );

                    }

                    echo '</small>';

                }

                /**
                 * Excerpt
                 */
                if ($show_excerpt) {

                    echo '<p class="mb-0">';
                    echo esc_html(
                        wp_trim_words(
                            get_the_excerpt($post_id),
                            $excerpt_length
                        )
                    );
                    echo '</p>';

                }

                echo '</div>';

                echo '</article>';

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
        $title = $instance['title'] ?? '';
        $posts_per_page = $instance['posts_per_page'] ?? 5;
        $show_image = isset($instance['show_image']) ? (bool) $instance['show_image'] : true;
        $show_date = isset($instance['show_date']) ? (bool) $instance['show_date'] : true;
        $show_author = isset($instance['show_author']) ? (bool) $instance['show_author'] : false;
        $show_excerpt = isset($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;
        $excerpt_length = $instance['excerpt_length'] ?? 15;
        ?>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Title</label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" type="text"
                name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('posts_per_page')); ?>">Number of Posts</label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('posts_per_page')); ?>" type="number" min="1"
                max="20" name="<?php echo esc_attr($this->get_field_name('posts_per_page')); ?>"
                value="<?php echo esc_attr($posts_per_page); ?>">
        </p>

        <p>
            <input type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_image')); ?>" value="1" <?php checked($show_image); ?> name="<?php echo esc_attr($this->get_field_name('show_image')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>">Show Featured Image</label>
        </p>

        <p>
            <input type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_date')); ?>" value="1" <?php checked($show_date); ?> name="<?php echo esc_attr($this->get_field_name('show_date')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>">Show Date</label>
        </p>

        <p>
            <input type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_author')); ?>" value="1" <?php checked($show_author); ?> name="<?php echo esc_attr($this->get_field_name('show_author')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>">Show Author</label>
        </p>

        <p>
            <input type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_excerpt')); ?>" value="1" <?php checked($show_excerpt); ?> name="<?php echo esc_attr($this->get_field_name('show_excerpt')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_excerpt')); ?>">Show Excerpt</label>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('excerpt_length')); ?>">Excerpt Length</label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('excerpt_length')); ?>" type="number" min="5"
                max="50" name="<?php echo esc_attr($this->get_field_name('excerpt_length')); ?>"
                value="<?php echo esc_attr($excerpt_length); ?>">
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
        $instance['posts_per_page'] = absint($new['posts_per_page']);
        $instance['show_image'] = !empty($new['show_image']);
        $instance['show_date'] = !empty($new['show_date']);
        $instance['show_author'] = !empty($new['show_author']);
        $instance['show_excerpt'] = !empty($new['show_excerpt']);
        $instance['excerpt_length'] = absint($new['excerpt_length']);

        return $instance;
    }
}
