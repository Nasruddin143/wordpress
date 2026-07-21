<?php

if (!function_exists('kt_custom_breadcrumbs')) {

    function kt_custom_breadcrumbs() {

        $delimiter = '<span class="sep">»</span>';
        $home_title = 'Home';

        $home_link = home_url();

        echo '<nav class="kt-breadcrumbs">';

        // Home
        echo '<a href="' . esc_url($home_link) . '">' . esc_html($home_title) . '</a>';

        // Front page
        if (is_front_page()) {
            echo '</nav>';
            return;
        }

        echo ' ' . $delimiter . ' ';

        global $post;

        // Category
        if (is_category()) {

            $cat = get_queried_object();

            if ($cat->parent != 0) {
                echo get_category_parents($cat->parent, true, ' ' . $delimiter . ' ');
            }

            echo '<span class="current">' . single_cat_title('', false) . '</span>';
        }

        // Single Post
        elseif (is_single()) {

            if (get_post_type() == 'post') {

                $cats = get_the_category();

                if (!empty($cats)) {

                    $cat = $cats[0];

                    echo get_category_parents($cat, true, ' ' . $delimiter . ' ');
                }

                echo '<span class="current">' . get_the_title() . '</span>';
            }

            // Custom Post Type
            else {

                $post_type = get_post_type_object(get_post_type());

                if ($post_type) {

                    echo '<a href="' . esc_url(get_post_type_archive_link($post_type->name)) . '">'
                        . esc_html($post_type->labels->singular_name)
                        . '</a>';

                    echo ' ' . $delimiter . ' ';
                }

                echo '<span class="current">' . get_the_title() . '</span>';
            }
        }

        // Page
        elseif (is_page()) {

            if ($post->post_parent) {

                $parents = [];

                $parent_id = $post->post_parent;

                while ($parent_id) {

                    $page = get_post($parent_id);

                    $parents[] = '<a href="' . get_permalink($page->ID) . '">'
                        . get_the_title($page->ID)
                        . '</a>';

                    $parent_id = $page->post_parent;
                }

                $parents = array_reverse($parents);

                echo implode(' ' . $delimiter . ' ', $parents);

                echo ' ' . $delimiter . ' ';
            }

            echo '<span class="current">' . get_the_title() . '</span>';
        }

        // Archive
        elseif (is_archive()) {

            echo '<span class="current">' . post_type_archive_title('', false) . '</span>';
        }

        // Search
        elseif (is_search()) {

            echo '<span class="current">Search: ' . get_search_query() . '</span>';
        }

        // 404
        elseif (is_404()) {

            echo '<span class="current">404 Not Found</span>';
        }

        echo '</nav>';
    }
}