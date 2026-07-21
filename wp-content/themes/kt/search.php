<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package king_tailors
 */

use KT\Helpers\UI;

defined('ABSPATH') || exit;

get_header();
?>

<main id="primary" class="site-main search-results-page">

    <?php get_template_part('template-parts/content', 'custom-header'); ?>

    <div class="page-content-wrapper py-5">
        <div class="container">

            <!--  PAGE HEADER  -->
            <header class="page-header mb-5">

                <div class="d-sm-flex align-items-center justify-content-between mb-4">

                    <!-- Breadcrumb -->
                    <?php if (function_exists('bcn_display')): ?>

                        <nav class="breadcrumb-nav mb-0" aria-label="breadcrumb">
                            <?php bcn_display(); ?>
                        </nav>

                    <?php endif; ?>

                    <!-- Social Share Buttons -->
                    <?php echo UI::social_share(); ?>
                </div>


                <h1 class="page-title fw-bold">
                    <?php
                    printf(
                        __('Search Results for: "%s"'),
                        '<span class="text-dark">' . esc_html(get_search_query()) . '</span>'
                    );
                    ?>
                </h1>

            </header>

            <!--  SEARCH FORM  -->
            <!-- <div class="mb-5 text-center"> -->
            <?php //get_search_form(); ?>
            <!-- </div> -->

            <!--  SEARCH RESULTS  -->
            <?php if (have_posts()): ?>

                <div class="row g-4">

                    <?php while (have_posts()):
                        the_post(); ?>

                        <div class="col-12">
                            <?php get_template_part('template-parts/content', 'search'); ?>
                        </div>

                    <?php endwhile; ?>

                </div>

                <!-- Pagination -->
                <div class="mt-5 d-flex justify-content-center">
                    <?php get_template_part('template-parts/navigation/navigation-pagination'); ?>
                </div>

            <?php else: ?>

                <!--  NO RESULTS  -->
                <div class="text-center py-5">
                    <?php get_template_part('template-parts/content', 'none'); ?>
                </div>

            <?php endif; ?>

        </div>
    </div>

</main>

<?php get_footer();