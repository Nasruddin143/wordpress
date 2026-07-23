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
                    'wrap_before' => '<nav class="woocommerce-breadcrumb pb-3 mb-4 border-bottom" aria-label="Breadcrumb">',
                    'wrap_after' => '</nav>',
                    'home' => _x('Home', 'breadcrumb', 'woocommerce'),
                ));
            }
            ?>

            <div class="row">
                <div class="col col-md-9">
                    <?php
                    if (have_posts()):

                        if (is_home() && !is_front_page()):
                            ?>

                            <header>
                                <h1 class="page-title screen-reader-text fw-normal"><?php single_post_title(); ?></h1>
                            </header>
                            <?php
                        endif;

                        /* Start the Loop */
                        while (have_posts()):
                            the_post(); ?>

                            <article id="post-<?php the_ID(); ?>" <?php post_class('mb-5'); ?>>

                                <div class="card border">

                                    <?php wooshop_post_thumbnail(); ?>

                                    <div class="card-body">
                                        <header class="entry-header">

                                            <?php

                                            if (is_singular()):
                                                the_title('<h1 class="entry-title card-title">', '</h1>');
                                            else:
                                                the_title('<h2 class="fw-normal entry-title card-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark" class="link-dark text-decoration-none">', '</a></h2>');
                                            endif;

                                            if ('post' === get_post_type()): ?>

                                                <?php wooshop_post_meta(); ?>

                                            <?php endif; ?>

                                        </header><!-- .entry-header -->

                                        <div class="entry-content">
                                            <?php
                                            the_excerpt();

                                            wp_link_pages(
                                                array(
                                                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'wooshop'),
                                                    'after' => '</div>',
                                                )
                                            );
                                            ?>
                                        </div><!-- .entry-content -->

                                        <footer class="entry-footer">
                                            <?php wooshop_entry_footer(); ?>
                                        </footer>
                                        <!-- .entry-footer -->
                                    </div>
                                </div>
                            </article><!-- #post-<?php the_ID(); ?> -->

                        <?php endwhile;

                        the_posts_navigation();

                    else:

                        get_template_part('template-parts/content', 'none');

                    endif;
                    ?>
                </div>

                <div class="col col-md-3">
                    <?php
                    get_sidebar(); ?>
                </div>
            </div>
        </div>
    </div>
</main><!-- #main -->

<?php
get_footer();