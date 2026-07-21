<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package kt
 */

use KT\Helpers\UI;

get_header(); ?>


<main id="primary" class="site-main">

    <?php get_template_part('template-parts/content', 'custom-header'); ?>

    <div class="page-content-wrapper py-5 bg-light">

        <div class="container">

            <!-- Breadcrumbs -->
            <div class="row mb-4 align-items-start">

                <div class="d-flex justify-content-between">
                    <!-- Breadcrumbs function -->
                    <?php if (function_exists('bcn_display')): ?>
                        <div class="d-sm-flex align-items-center justify-content-between">
                            <nav class="breadcrumb-nav mb-0" aria-label="breadcrumb">
                                <?php bcn_display(); ?>
                            </nav>
                        </div>
                    <?php endif; ?>

                    <!-- Social Share Buttons -->
                    <?php echo UI::social_share(); ?>
                </div>
            </div>

            <?php

            if (have_posts()) {

                while (have_posts()) {

                    the_post(); ?>

                    <?php get_template_part('template-parts/content', 'single'); ?>

                    <!-- Post Navigation -->
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo get_permalink(get_previous_post()); ?>" class="btn btn-dark rounded-pill px-3" title="Go to previous post">
                            <i class="bi bi-arrow-left me-1"></i> Prev </a>
                        <a href="<?php echo get_permalink(get_next_post()); ?>" class="btn btn-dark rounded-pill px-3" title="Go to next post">
                            Next <i class="bi bi-arrow-right ms-1"></i> </a>
                    </div>

                <?php } ?>

            <?php } ?>
        </div>
    </div>
</main>

<?php get_footer();
