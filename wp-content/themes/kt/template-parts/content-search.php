<?php

/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package king_tailors
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card border-0 rounded mb-3'); ?>>

    <div class="row g-0">

        <!-- Thumbnail -->
        <div class="col-md-3">
            <a href="<?php the_permalink(); ?>">
                <?php if (has_post_thumbnail()): ?>
                    <?php the_post_thumbnail('kt-page-featured-post', [
                        'class' => 'img-fluid rounded-start search-thumb w-100 h-100 object-fit-cover',
                        'alt' => the_title_attribute(['echo' => false])
                    ]); ?>
                <?php else: ?>
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder.webp') ?>"
                        class="img-fluid rounded-start w-100 h-100 object-fit-cover search-thumb"
                        alt="<?php the_title_attribute(); ?>">
                <?php endif; ?>
            </a>
        </div>

        <!-- Content -->
        <div class="col-md-9 d-flex">
            <div class="card-body p-4 align-content-center">

                <!-- Post Title -->
                <header class="entry-header mb-2">
                    <?php the_title(
                        sprintf(
                            '<h2 class="entry-title h5 fw-semibold card-title mb-2"><a href="%s" class="text-decoration-none text-dark">',
                            esc_url(get_permalink())
                        ),
                        '</a></h2>'
                    ); ?>
                </header>

                <!-- Excerpt -->
                <div class="entry-summary card-text mb-3">
                    <?php the_excerpt(); ?>
                </div>
            </div>
        </div>
    </div>

</article>