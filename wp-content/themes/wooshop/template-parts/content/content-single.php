<?php
/**
 * Single Content
 *
 * Displays a single post.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

    <article
            id="post-<?php the_ID(); ?>"
            <?php post_class( 'ws-single-content' ); ?>>

        <header class="entry-header">

            <?php
            the_title(
                    '<h1 class="entry-title">',
                    '</h1>'
            );
            ?>

            <?php
            get_template_part(
                    'template-parts/meta/post-meta'
            );
            ?>

        </header>

        <?php if ( has_post_thumbnail() ) : ?>

            <div class="entry-thumbnail">

                <?php
                the_post_thumbnail(
                        'wooshop-large'
                );
                ?>

            </div>

        <?php endif; ?>

        <div class="entry-content">

            <?php
            the_content();
            ?>

        </div>

        <?php if ( get_the_tags() ) : ?>

            <footer class="entry-footer">

                <?php
                get_template_part(
                        'template-parts/meta/tags'
                );
                ?>

            </footer>

        <?php endif; ?>

    </article>

<?php
/*
 * Author information.
 */
get_template_part(
        'template-parts/author/author-card'
);

/*
 * Previous / Next post navigation.
 */
get_template_part(
        'template-parts/navigation/post-navigation'
);