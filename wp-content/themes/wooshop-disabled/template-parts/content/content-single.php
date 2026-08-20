<?php
/**
 * Single Post Content
 *
 * Displays a complete WordPress post.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<article
    id="post-<?php the_ID(); ?>"
    <?php post_class( 'ws-single-content' ); ?>
>

    <header class="entry-header mb-4">

        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

        <div class="entry-meta small text-body-secondary">
            <?php echo esc_html( get_the_date() ); ?>
        </div>

    </header>

    <?php if ( has_post_thumbnail() ) : ?>

        <div class="entry-thumbnail mb-4">

            <?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid rounded' ) ); ?>

        </div>

    <?php endif; ?>

    <div class="entry-content">

        <?php
        the_content();

        wp_link_pages(
            array(
                'before' => '<nav class="page-links mt-4">',
                'after'  => '</nav>',
            )
        );
        ?>

    </div>

</article>