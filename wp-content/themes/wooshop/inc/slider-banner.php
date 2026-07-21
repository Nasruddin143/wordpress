<?php

/**
 * Register Slider Custom Post Type
 */
function register_slider_cpt()
{
    $labels = array(
        'name' => 'Slider',
        'singular_name' => 'Slider',
        'menu_name' => 'Slider',
        'name_admin_bar' => 'Slider',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Slide',
        'edit_item' => 'Edit Slide',
        'new_item' => 'New Slide',
        'view_item' => 'View Slide',
        'all_items' => 'All Slides',
        'search_items' => 'Search Slides',
        'not_found' => 'No slides found',
        'not_found_in_trash' => 'No slides found in Trash',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-admin-post',

        'supports' => array(
            'title',
            'editor',
            'thumbnail',
            'excerpt',
            'page-attributes'
        ),

        'hierarchical' => false,
        'has_archive' => false,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'show_in_nav_menus' => false,
        'show_in_rest' => true,

        'rewrite' => false,

        'menu_order' => 0,
    );

    register_post_type('slider', $args);
}
add_action('init', 'register_slider_cpt');


// Bootstrap Slider
function custom_bootstrap_slider_shortcode()
{

    $post_ids = get_posts(array(
        'post_type' => 'slider', // Change to your CPT
        'numberposts' => 10,
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'meta_key' => '_thumbnail_id',

        // Performance
        'fields' => 'ids',
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
        'suppress_filters' => true,
    ));

    if (empty($post_ids)) {
        return '';
    }



    ob_start();
    ?>
    

    <div id="wpBootstrapCarousel" class="carousel slide" data-bs-ride="carousel">

        <div class="carousel-inner">

            <?php foreach ($post_ids as $index => $post_id): ?>

                <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">

                    <?php
                    echo get_the_post_thumbnail(
                        $post_id,
                        'full',
                        array(
                            'class' => 'img-fluid',
                            'loading' => ($index === 0) ? 'eager' : 'lazy',
                            'decoding' => 'async',
                            'alt' => esc_attr(get_the_title($post_id))
                        )
                    );
                    ?>

                    <div class="carousel-caption d-none">
                        <h2><?php echo esc_html(get_the_title($post_id)); ?></h2>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#wpBootstrapCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#wpBootstrapCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

    </div>

    <?php

    return ob_get_clean();
}

add_shortcode('bootstrap_slider', 'custom_bootstrap_slider_shortcode');