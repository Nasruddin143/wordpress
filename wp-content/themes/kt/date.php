<?php
/**
 * The template for displaying Date archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#date
 *
 * @package king_tailors
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="primary" class="site-main date-archive">

    <?php get_template_part('template-parts/content', 'custom-header'); ?>

    <div class="page-content-wrapper py-5">
        <div class="container">

            <?php if (have_posts()): ?>

                <!--  ARCHIVE HEADER  -->
                <header class="page-header mb-5">

                    <!-- Breadcrumb -->
                    <?php if (function_exists('bcn_display')): ?>
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <nav class="breadcrumb-nav mb-0" aria-label="breadcrumb">
                                <?php bcn_display(); ?>
                            </nav>
                        </div>
                    <?php endif; ?>

                    <h1 class="page-title">
                        <?php
                        if (is_day()) {
                            printf(
                                esc_html__('Posts from %s', 'king_tailors'),
                                '<time datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time>'
                            );
                        } elseif (is_month()) {
                            printf(
                                esc_html__('Posts from %s', 'king_tailors'),
                                esc_html(get_the_date('F Y'))
                            );
                        } elseif (is_year()) {
                            printf(
                                esc_html__('Posts from %s', 'king_tailors'),
                                esc_html(get_the_date('Y'))
                            );
                        } else {
                            esc_html_e('Archives', 'king_tailors');
                        }
                        ?>
                    </h1>

                </header>

                <!--  POSTS GRID  -->
                <div class="row g-4">

                    <?php while (have_posts()):
                        the_post(); ?>
                        <div class="col-lg-4 col-md-6">
                            <?php
                            get_template_part(
                                'template-parts/content',
                                get_post_type()
                            );
                            ?>
                        </div>
                    <?php endwhile; ?>

                </div>

                <!--  PAGINATION  -->
                <div class="mt-5 d-flex justify-content-center">
                    <?php get_template_part('template-parts/navigation/navigation-pagination'); ?>
                </div>

            <?php else: ?>

                <!--  NO POSTS  -->
                <div class="alert alert-warning text-center">
                    <?php esc_html_e('No posts found for this date.', 'king_tailors'); ?>
                </div>

            <?php endif; ?>

        </div>
    </div>

</main>

<?php get_footer();