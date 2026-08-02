<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WooShop
 */

get_header();
?>

<main id="primary" class="site-main home woocommerce">

    <div class="page-banner mb-3">
        <img class="img-fluid" src="<?php echo get_template_directory_uri() . '/assets/images/page-banner.webp' ?>"
            alt="Page Banner">
    </div>

    <div class="py-5">

        <div class="container">

            <?php
            if (function_exists('woocommerce_breadcrumb')) {
                woocommerce_breadcrumb(array(
                    'delimiter' => ' / ',
                    'wrap_before' => '<nav class="woocommerce-breadcrumb" aria-label="Breadcrumb">',
                    'wrap_after' => '</nav>',
                    'home' => _x('Home', 'breadcrumb', 'woocommerce'),
                ));
            }
            ?>

            <div class="row">
                <div class="col-12 col-sm-12 col-md-8 col-lg-9">

                    <?php

                    if (is_home() && !is_front_page()): ?>

                        <header>
                            <h1 class="page-title fw-normal mb-4 text-dark">
                                <?php single_post_title(); ?>
                            </h1>
                        </header>
                    <?php endif; ?>

                    <?php

                    if (have_posts()):

                        /* Start the Loop */
                        while (have_posts()):
                            the_post(); ?>

                            <?php
                            /*
                             * Include the Post-Type-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                             */
                            get_template_part('template-parts/content', get_post_type());

                        endwhile;

                        the_posts_navigation();

                    else:

                        get_template_part('template-parts/content', 'none');

                    endif;
                    ?>
                </div>

                <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                    <?php
                    get_sidebar(); ?>
                </div>
            </div>
        </div>
    </div>
</main><!-- #main -->

<?php
get_footer();