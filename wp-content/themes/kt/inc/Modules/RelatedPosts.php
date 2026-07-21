<?php

namespace KT\Modules;

use KT\Helpers\Price;
use KT\Helpers\Ratings;

defined('ABSPATH') || exit;

class RelatedPosts
{
    private $post_id;
    private $post_type;
    private $limit;

    public function __construct($post_id = null, $post_type = 'post', $limit = 4)
    {
        $this->post_id   = $post_id ?: get_the_ID();
        $this->post_type = $post_type;
        $this->limit     = $limit;
    }

    /**
     * ----------------------------------------
     * MAIN RENDER
     * ----------------------------------------
     */
    public function render()
    {
        $posts = $this->get_posts();

        if (empty($posts)) return;

        echo '<div class="related-posts">';
        echo '<h3 class="fw-bold mb-3">Featured items you may like</h3>';
        echo '<div class="row mb-4">';

        foreach ($posts as $post) {
            echo '<div class="col-md-3">';
            echo $this->render_card($post);
            echo '</div>';
        }

        echo '</div></div>';
    }

    /**
     * ----------------------------------------
     * QUERY
     * ----------------------------------------
     */
    private function get_posts()
    {
        $taxonomies = get_object_taxonomies($this->post_type);

        if (empty($taxonomies)) return [];

        $tax_query = ['relation' => 'OR'];

        foreach ($taxonomies as $taxonomy) {

            $terms = wp_get_post_terms($this->post_id, $taxonomy, ['fields' => 'ids']);

            if (!empty($terms) && !is_wp_error($terms)) {
                $tax_query[] = [
                    'taxonomy' => $taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $terms
                ];
            }
        }

        if (count($tax_query) <= 1) return [];

        return get_posts([
            'post_type'              => $this->post_type,
            'exclude'                => [$this->post_id],
            'posts_per_page'         => $this->limit,
            'orderby'                => 'comment_count',
            'tax_query'              => $tax_query,
            'no_found_rows'          => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        ]);
    }

    /**
     * ----------------------------------------
     * CARD HTML
     * ----------------------------------------
     */
    private function render_card($post)
    {
        ob_start();

        $id    = $post->ID;
        $title = get_the_title($id);
        $link  = get_permalink($id);

?>
        <div class="card h-100 rounded-0 border-0">

            <?php if (has_post_thumbnail($id)): ?>
                <a href="<?php echo esc_url($link); ?>" class="bg-light">
                    <?php
                    echo get_the_post_thumbnail($id, 'kt-product-main', [
                        'class' => 'img-fluid',
                        'alt'   => esc_attr($title),
                        'title' => esc_attr($title)
                    ]);
                    ?>
                </a>
            <?php endif; ?>

            <div class="card-body">

                <h4 class="h6 fw-bold card-title">
                    <a href="<?php echo esc_url($link); ?>"
                        class="link-dark text-decoration-none"
                        title="<?php echo esc_attr($title); ?>">
                        <?php echo esc_html($title); ?>
                    </a>
                </h4>

                <div class="d-flex justify-content-between align-items-center">

                    <?php
                    echo Price::render($id, [
                        'show_discount' => true,
                        'show_diff'     => false,
                    ]);
                    ?>

                    <?php echo Ratings::rating_stars($id); ?>

                </div>

            </div>
        </div>
<?php

        return ob_get_clean();
    }

    /**
     * ----------------------------------------
     * RATING SYSTEM (OPTIMIZED)
     * ----------------------------------------
     */
    private function get_rating_html($post_id)
    {
        $reviews = apply_filters('glsr_get_reviews', null, [
            'assigned_posts' => $post_id,
            'status' => 'approved',
            //'post_type' => $post_type,
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


        return '<div class="kt-rating-stars"><span class="stars">'.$stars_html .'</span></div>';
    }
}
