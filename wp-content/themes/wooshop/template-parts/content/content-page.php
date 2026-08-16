<?php
/**
 * Page Content
 *
 * Displays the content of a standard WordPress page.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<article
    id="post-<?php the_ID(); ?>"
    <?php post_class( 'ws-page-content' ); ?>
>

    <header class="entry-header mb-4">

        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

    </header>

    <div class="entry-content">

        <?php
        the_content();

        wp_link_pages(
            array(
                'before' => '<nav class="page-links mt-4" aria-label="' .
                    esc_attr__( 'Page navigation', 'wooshop' ) . '">',
                'after'  => '</nav>',
            )
        );
        ?>

    </div>

</article>