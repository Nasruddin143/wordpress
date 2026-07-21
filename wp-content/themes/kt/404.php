<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package kt
 */
use KT\Helpers\UI;

get_header();
?>

<main id="primary" class="site-main">

    <?php get_template_part('template-parts/content', 'custom-header'); ?>

    <section class="error-404 not-found py-5">
        <div class="container">

            <!--  HEADER  -->
            <header class="entry-header mb-4">

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


                <h1 class="entry-title">
                    <?php echo esc_html(__("Oops! That page can’t be found.")); ?>
                </h1>

            </header>

            <!-- Heading -->
            <div class="search-header mb-4 text-center">
                <h2 class="search-title display-5 fw-bold text-dark">
                    <?php echo esc_html(__("Oops! That page can’t be found.")); ?>
                </h2>
                    </div>

            <!-- Message -->
            <p class="lead mb-4 text-muted text-center">
                <?php echo esc_html(__('It looks like nothing was found at this location.')); ?>
            </p>

            <!-- Search -->
            <div class="row justify-content-center mb-4">
                <div class="col-lg-6 col-md-8">
                    <?php get_search_form(); ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-center gap-3 flex-wrap">

                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-dark">
                    <?php echo esc_html(__('Go to Homepage')); ?>
                </a>

                <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact-us'))); ?>"
                    class="btn btn-outline-dark">
                    <?php echo esc_html(__('Contact Us')); ?>
                </a>

            </div>

        </div>
    </section>

</main>


<?php get_footer(); ?>