<?php
/**
 * Custom Post Navigation Override
 */
if ( ! function_exists( 'the_posts_navigation' ) ) {
    return;
}

$prev = get_previous_posts_link( '<span class="btn btn-outline-primary px-4 py-2">← Latest</span>' );
$next = get_next_posts_link( '<span class="btn btn-primary px-4 py-2">Older →</span>' );

if ( $prev || $next ) : ?>
    <nav class="custom-posts-nav my-5" aria-label="Posts Navigation">
        <div class="d-flex justify-content-between">
            <div class="nav-previous"><?php echo $prev; ?></div>
            <div class="nav-next"><?php echo $next; ?></div>
        </div>
    </nav>
<?php endif;
