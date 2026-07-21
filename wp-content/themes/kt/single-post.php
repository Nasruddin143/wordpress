<?php

use KT\Helpers\UI;

 get_header(); ?>

<main id="primary" class="site-main">

    <?php get_template_part('template-parts/content', 'custom-header'); ?>

    <div id="kt-blog-page" class="page-content-wrapper py-5 bg-light">

        <div class="container">

            <div class="row">
                <!-- Blog Section -->
                <div class="col-md-8">

                    <div class="card rounded border border-light-subtle shadow-sm">
                        <div class="card-body p-5">

                            <?php if (have_posts()):
                                while (have_posts()):
                                    the_post(); ?>

                                    <article id="post-<?php the_ID(); ?>" <?php post_class('kt-blog-article mb-4 border-right'); ?>>

                                        <header class="entry-header mb-4">

                                            <!-- Breadcrumbs (Language-aware automatically via Polylang) -->
                                            <?php if (function_exists('bcn_display')): ?>
                                                <nav class="breadcrumb-nav mb-4" aria-label="<?php esc_attr_e('Breadcrumb', 'kt'); ?>">
                                                    <?php bcn_display(); ?>
                                                </nav>
                                            <?php endif; ?>

                                            <!-- Title -->
                                            <h1 class="kt-blog-title h2 fw-bold mb-4">
                                                <?php the_title(); ?>
                                            </h1>

                                            <!-- Meta -->
                                            <div class="kt-blog-meta mb-4 text-muted small">
                                                Last updated on <?php echo get_the_date(); ?>
                                                by <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" rel="author" title="Articles by <?php the_author(); ?>"><?php the_author(); ?></a>
                                            </div>

                                            <div class="social-share-buttons">
                                                <?php echo UI::social_share(); ?>
                                            </div>

                                        </header>

                                        <hr class="my-4 border border-secondary-subtle">

                                        <!-- Featured Image -->
                                        <div class="content-image py-4">
                                            <?php if (has_post_thumbnail()): ?>
                                                <div class="kt-blog-image">
                                                    <?php the_post_thumbnail(
                                                        'kt-blog-image',
                                                        [
                                                            'class' => 'img-fluid',
                                                            'loading' => 'lazy',
                                                            'fetchpriority' => 'low',
                                                            'alt' => esc_attr(get_the_title()),
                                                            'title' => esc_attr(get_the_title())
                                                        ]
                                                    ); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Content -->
                                        <div class="kt-blog-content">
                                            <?php the_content(); ?>
                                        </div>

                                    </article>

                                    <!-- Pagination -->
                                    <div class="d-flex justify-content-between">
                                        <a href="<?php echo get_permalink(get_previous_post()); ?>" class="btn btn-outline-dark rounded-pill px-3 py-2" title="Go to previous story">
                                            <i class="bi bi-arrow-left-short me-1"></i> Prev Story </a>
                                        <a href="<?php echo get_permalink(get_next_post()); ?>" class="btn btn-outline-dark rounded-pill px-3 py-2" title="Go to next story">
                                            Next Story <i class="bi bi-arrow-right-short ms-1"></i> </a>
                                    </div>

                            <?php endwhile;
                            endif; ?>
                        </div>
                    </div>

                </div>

                <!-- Sidebar Section -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <?php if (is_active_sidebar('sidebar-1')): ?>
                                <?php dynamic_sidebar('sidebar-1'); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</main>

<?php get_footer();
