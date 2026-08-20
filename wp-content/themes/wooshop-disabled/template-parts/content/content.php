<?php
/**
 * Default Post Content
 *
 * Displays a standard WordPress post in archive contexts.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<article
    id="post-<?php the_ID(); ?>"
    <?php post_class( 'ws-content-card mb-4' ); ?>
>

    <header class="entry-header mb-3">

        <?php
        the_title(
            '<h2 class="entry-title h4 mb-2"><a class="text-decoration-none" href="' . esc_url( get_permalink() ) . '">',
            '</a></h2>'
        );
        ?>

        <div class="entry-meta small text-body-secondary">
            <?php echo esc_html( get_the_date() ); ?>
        </div>

    </header>

    <div class="entry-content">

        <?php the_excerpt(); ?>

    </div>

    <footer class="entry-footer">

        <a
            class="btn btn-outline-primary btn-sm"
            href="<?php echo esc_url( get_permalink() ); ?>"
        >
            <?php esc_html_e( 'Read more', 'wooshop' ); ?>
        </a>

    </footer>

</article>