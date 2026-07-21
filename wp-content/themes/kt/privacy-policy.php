<?php

/**
 * Template Name: Privacy Policy
 * Description: SEO optimized Privacy Policy page template
 *
 * @package king_tailors
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="primary" class="site-main privacy-policy-page">

    <?php get_template_part('template-parts/content', 'custom-header'); ?>

    <div class="page-content-wrapper py-5">
        <div class="container">

            <?php while (have_posts()):
                the_post(); ?>

                <!--  PAGE HEADER  -->
                <header class="page-header mb-4">

                    <!-- Breadcrumb -->
                    <?php if (function_exists('bcn_display')) : ?>
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <nav class="breadcrumb-nav mb-0" aria-label="breadcrumb">
                                <?php bcn_display(); ?>
                            </nav>
                        </div>
                    <?php endif; ?>

                    <h1 class="page-title fw-bold">
                        <?php the_title(); ?>
                    </h1>

                    <?php if (has_excerpt()): ?>
                        <p class="text-muted mt-2">
                            <?php echo esc_html(get_the_excerpt()); ?>
                        </p>
                    <?php endif; ?>

                </header>

                <!--  CONTENT  -->
                <article id="post-<?php the_ID(); ?>" <?php post_class('entry-content'); ?>>

                    <?php the_content(); ?>

                    <?php
                    // Support paginated content if used
                    wp_link_pages([
                        'before' => '<nav class="page-links mt-4">',
                        'after' => '</nav>',
                    ]);
                    ?>

                </article>

                <!--  LAST UPDATED  -->
                <div class="mt-5 text-muted small">
                    <?php
                    printf(
                        esc_html__('Last updated on %s', 'king_tailors'),
                        esc_html(get_the_modified_date())
                    );
                    ?>
                </div>

            <?php endwhile; ?>

        </div>
    </div>

</main>

<?php get_footer();