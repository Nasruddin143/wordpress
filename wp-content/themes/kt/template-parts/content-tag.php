<?php
/*
 * Template Part: Tag Archive Card
 * Used in tag.php to display posts in a Bootstrap card grid.
 */
?>

<div class="col-lg-4 col-md-6">

    <article id="post-<?php the_ID(); ?>" <?php post_class('card h-100 border-0 rounded-0'); ?>>

        <?php if (has_post_thumbnail()): ?>
            <a href="<?php the_permalink(); ?>" class="">
                <?php the_post_thumbnail(
                    'medium',
                    [
                        'class' => 'card-img-top img-fluid',
                        'loading' => 'lazy',
                        'alt' => esc_attr(get_the_title()),
                    ]
                ); ?>
            </a>
        <?php endif; ?>

        <div class="card-body">

            <header class="entry-header mb-2">
                <h2 class="entry-title h6 mb-1">
                    <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                        <?php the_title(); ?>
                    </a>
                </h2>
            </header>

        </div>

    </article>

</div>