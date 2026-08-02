<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WooShop
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-5'); ?>>

    <header class="entry-header">

        <?php

        the_title('<h1 class="entry-title text-dark fw-normal mb-3 ">', '</h1>');


        if ('post' === get_post_type()): ?>

            <?php wooshop_post_meta(); ?>

        <?php endif; ?>

    </header><!-- .entry-header -->

    <div class="entry-content lh-lg">

        <div class="py-4">
        <?php wooshop_post_thumbnail(); ?>
        </div>

        <?php

        the_content(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'wooshop'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post(get_the_title())
            )

        );


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

</article><!-- #post-<?php the_ID(); ?> -->