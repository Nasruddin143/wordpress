<?php
/**
 * Page Content Template
 *
 * Displays the content of a static WordPress page.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<article
        id="post-<?php the_ID(); ?>"
        <?php post_class( 'ws-page-content' ); ?>>

    <?php if ( has_post_thumbnail() ) : ?>

        <div class="ws-page-content__thumbnail">

            <?php
            the_post_thumbnail(
                    'large',
                    [
                            'class'   => 'ws-page-content__image',
                            'loading' => 'eager',
                    ]
            );
            ?>

        </div>

    <?php endif; ?>

    <header class="ws-page-content__header">

        <?php
        the_title(
                '<h1 class="ws-page-content__title">',
                '</h1>'
        );
        ?>

    </header>

    <div class="ws-page-content__body entry-content">

        <?php
        the_content();
        ?>

    </div>

    <?php
    wp_link_pages(
            [
                    'before' => '<nav class="ws-page-content__pagination" aria-label="' .
                            esc_attr__(
                                    'Page navigation',
                                    'wooshop'
                            ) .
                            '"><span class="ws-page-content__pagination-label">' .
                            esc_html__(
                                    'Pages:',
                                    'wooshop'
                            ) .
                            '</span>',
                    'after'  => '</nav>',
                    'link_before' => '<span class="ws-page-content__pagination-link">',
                    'link_after'  => '</span>',
            ]
    );
    ?>

    <?php if ( get_edit_post_link() ) : ?>

        <footer class="ws-page-content__footer">

            <?php
            edit_post_link(
                    esc_html__(
                            'Edit Page',
                            'wooshop'
                    ),
                    '<span class="ws-page-content__edit">',
                    '</span>'
            );
            ?>

        </footer>

    <?php endif; ?>

</article>