<?php

/**
 * Blog Home Template
 *
 * @package king_tailors
 */

defined('ABSPATH') || exit;

get_header();

?>

<main id="primary" class="site-main blog-home">

    <?php get_template_part('template-parts/content', 'custom-header'); ?>

    <div class="page-content-wrapper py-5">
        <div class="container">

            <!--  BLOG HEADER  -->
            <header class="page-header mb-4">

                <!-- Breadcrumb -->
                <?php if (function_exists('bcn_display')): ?>
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <nav class="breadcrumb-nav mb-0" aria-label="breadcrumb">
                            <?php bcn_display(); ?>
                        </nav>
                    </div>
                <?php endif; ?>

                <h1 class="page-title">
                    <?php echo esc_html(get_the_title(get_option('page_for_posts'))); ?>
                </h1>

            </header>

            <!--  CONTENT  -->

            <div class="page-content">

                <div class="row">

                    <?php if (have_posts()): ?>
                        <?php while (have_posts()):
                            the_post(); ?>

                            <?php get_template_part('template-parts/content', 'home'); ?>

                        <?php endwhile; ?>

                    <?php else: ?>
                        <?php get_template_part('template-parts/content', 'none'); ?>
                    <?php endif; ?>

                    <div class="nav-previous alignleft">
                        <?php previous_posts_link('Older posts'); ?>
                    </div>
                    <div class="nav-next alignright">
                        <?php next_posts_link('Newer posts'); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<?php get_footer();
