<?php
/**
 * Template part for displaying a blog post
 *
 * @package kt
 */
?>

<div class="col-md-4">
    <div class="card mb-4 h-100">
        <?php if (has_post_thumbnail()): ?>

            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('kt-blog-image', [
                    'class' => 'card-img-top img-fluid',
                    'loading' => 'lazy',
                    'alt' => esc_attr(get_the_title()),
                    'title' => esc_attr(get_the_title())
                ]); ?>
            </a>
        <?php else: ?>
            <a href="<?php the_permalink(); ?>"> 
                <img class="card-img-top"
                    src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder-images/blog-post.svg'); ?>"
                    alt="<?php the_title(); ?>" title="<?php the_title(); ?>">
            </a>

        <?php endif; ?>
        <div class="card-body">
            <p class="text-dark"><?php the_date(); ?></p>
            <h2 class="card-title h5 fw-bold mb-3">
                <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none">
                    <?php the_title(); ?>
                </a>
            </h2>
            <!-- <p class="card-text"><?php //echo wp_trim_words(get_the_excerpt(), 25); ?></p> -->
            <!-- <a href="<?php //the_permalink(); ?>" class="btn btn-outline-dark rounded-pill px-3">Read More</a> -->
        </div>
    </div>
</div>