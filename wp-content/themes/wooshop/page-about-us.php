<?php
/**
 * The template for displaying About Us page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WooShop
 */

get_header();
?>

<main id="primary" class="site-main woocommerce page-about-us">

    <div class="page-banner mb-3">
        <img class="img-fluid" src="<?php echo get_template_directory_uri() . '/assets/images/page-banner.webp' ?>"
            alt="Page Banner">
    </div>

    <div class="container">

        <?php
        while (have_posts()):
            the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('py-5'); ?>>

                <?php if (!is_front_page()): ?>

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

                    <header class="entry-header mb-4">
                        <?php the_title('<h1 class="text-dark fw-normal entry-title">', '</h1>'); ?>
                    </header><!-- .entry-header -->
                <?php endif; ?>


                <?php if (wooshop_post_thumbnail()): ?>
                    <div class="mb-4">
                        <?php wooshop_post_thumbnail(); ?>
                    </div>
                <?php endif; ?>


                <div class="entry-content text-body-secondary fw-medium lh-base text-justify">
                    <?php
                    the_content();

                    wp_link_pages(
                        array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'wooshop'),
                            'after' => '</div>',
                        )
                    );
                    ?>
                </div><!-- .entry-content -->

                <?php if (get_edit_post_link()): ?>
                    <footer class="entry-footer">
                        <?php
                        edit_post_link(
                            sprintf(
                                wp_kses(
                                    /* translators: %s: Name of current post. Only visible to screen readers */
                                    __('Edit <span class="screen-reader-text">%s</span>', 'wooshop'),
                                    array(
                                        'span' => array(
                                            'class' => array(),
                                        ),
                                    )
                                ),
                                wp_kses_post(get_the_title())
                            ),
                            '<span class="edit-link">',
                            '</span>'
                        );
                        ?>
                    </footer><!-- .entry-footer -->
                <?php endif; ?>
            </article><!-- #post-<?php the_ID(); ?> -->

        <?php endwhile; // End of the loop.
        ?>

    </div>
</main><!-- #main -->

<?php get_footer();