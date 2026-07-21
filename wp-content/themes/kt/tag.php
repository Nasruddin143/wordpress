<?php
/**
 * The template for displaying Tag archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#tag
 *
 * @package king_tailors
 */

defined('ABSPATH') || exit;

get_header();
?>

<?php

get_header();

$author_id = get_queried_object_id();
$curauth = get_userdata($author_id);
?>

<main id="primary" class="site-main author-page">

    <?php get_template_part('template-parts/content', 'custom-header'); ?>

    <div class="page-content-wrapper py-5">
        <div class="container">

            <!-- Page Header -->
            <header class="entry-header">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">

                    <!-- Breadcrumb -->
                    <?php if (function_exists('bcn_display')): ?>

                        <nav class="breadcrumb-nav mb-0" aria-label="breadcrumb">
                            <?php bcn_display(); ?>
                        </nav>

                    <?php endif; ?>


                    <!-- Social Share Buttons -->
                    <?php echo kt_social_share_buttons(); ?>
                </div>


                <!-- Page Title -->
                <h1 class="entry-title fw-semibold text-dark">
                    <?php single_tag_title(); ?>
                </h1>
            </header>

            <div class="row">

                <!-- MAIN CONTENT (8 columns) -->
                <div class="col-lg-8">

                    <?php if (have_posts()): ?>

                        <?php while (have_posts()):
                            the_post(); ?>

                            <div class="card border-0 shadow-lg">
                                <div class="card-body">

                                    <div class="row d-flex align-items-center">

                                        <div class="col-md-3">
                                            <a href="<?php the_permalink(); ?>" class="d-block">
                                                <?php the_post_thumbnail('kt-product-card', [
                                                    'class' => 'img-fluid rounded-start',
                                                    'loading' => 'lazy',
                                                    'alt' => esc_attr(get_the_title())
                                                ]); ?>
                                            </a>
                                        </div>

                                        <div class="col-md-9">

                                            <div class="small mb-2">In <?php the_category(', '); ?> by <?php the_author(); ?>
                                            </div>

                                            <a href="<?php the_permalink(); ?>" class="d-block">
                                                <h2 class="h4 fw-semibold mb-2">
                                                    <?php the_title(); ?>
                                                    </h3>
                                            </a>
                                            <div class="small text-muted">
                                                <?php echo esc_html(get_the_date()); ?>
                                            </div>

                                            <p class="text-muted mb-0"><?php echo wp_trim_words(get_the_excerpt(), 25); ?></p>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        <?php endwhile; ?>

                        <!-- Pagination -->
                        <div class="mt-4">
                            <?php
                            echo paginate_links([
                                'prev_text' => '← Previous',
                                'next_text' => 'Next →',
                                'class' => 'pagination'
                            ]);
                            ?>
                        </div>

                    </div>

                <?php else: ?>

                    <div class="alert alert-info">
                        No posts by this author.
                    </div>

                <?php endif; ?>


                <!-- SIDEBAR (4 columns, RIGHT SIDE) -->
                <div class="col-lg-4">

                    <?php get_sidebar(); ?>

                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>