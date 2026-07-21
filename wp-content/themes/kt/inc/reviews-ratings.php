<?php

function kt_track_product_views($post_id)
{
    if (!is_singular())
        return;

    if ((int) $post_id !== get_queried_object_id())
        return;

    $views = (int) get_post_meta($post_id, '_kt_views', true);
    update_post_meta($post_id, '_kt_views', $views + 1);
}


add_action('wp_head', function () {
    if (is_singular('product')) {
        kt_track_product_views(get_the_ID());
    }
});


function kt_unique_view()
{
    if (!is_singular('product'))
        return;

    $post_id = get_the_ID();
    $key = 'kt_viewed_' . $post_id;

    if (!isset($_COOKIE[$key])) {
        $views = (int) get_post_meta($post_id, '_kt_views', true);
        update_post_meta($post_id, '_kt_views', $views + 1);

        setcookie($key, 1, time() + 3600, '/'); // 1 hour lock
    }
}
add_action('template_redirect', 'kt_unique_view');


$post_id = get_the_ID();

$rating = (float) get_post_meta($post_id, '_kt_avg_rating', true);
$count = (int) get_post_meta($post_id, '_kt_review_count', true);
$views = (int) get_post_meta($post_id, '_kt_views', true);
$date = get_the_date('U');

// Top Rated
if ($rating >= 4.5 && $count >= 5) {
    echo '<span class="badge top-rated">Top Rated</span>';
}

// Best Seller (manual)
if (get_post_meta($post_id, '_kt_best_seller', true)) {
    echo '<span class="badge best-seller">Best Seller</span>';
}

// Trending (views + rating)
if ($views > 50 && $rating >= 4) {
    echo '<span class="badge trending">Trending</span>';
}

// New Arrival (7 days)
if (time() - $date < 7 * 86400) {
    echo '<span class="badge new">New</span>';
}