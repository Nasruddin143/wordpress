<?php
/**
 * Default Content
 *
 * Displays a standard post/content item.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<article
        id="post-<?php the_ID(); ?>"
        <?php post_class( 'ws-content-card' ); ?>>

    <header class="entry-header">

        <h2 class="entry-title">

            <a
                    href="<?php echo esc_url( get_permalink() ); ?>">

                <?php the_title(); ?>

            </a>

        </h2>

        <?php
        get_template_part(
                'template-parts/meta/post-meta'
        );
        ?>

    </header>

    <?php if ( has_post_thumbnail() ) : ?>

        <div class="entry-thumbnail">

            <a
                    href="<?php echo esc_url( get_permalink() ); ?>">

                <?php
                the_post_thumbnail(
                        'wooshop-card'
                );
                ?>

            </a>

        </div>

    <?php endif; ?>

    <div class="entry-content">

        <?php
        if ( is_singular() ) :

            the_content();

        else :

            the_excerpt();

        endif;
        ?>

    </div>

    <?php if ( ! is_singular() ) : ?>

        <footer class="entry-footer">

            <a
                    class="ws-read-more"
                    href="<?php echo esc_url( get_permalink() ); ?>">

                <?php esc_html_e( 'Read More', 'wooshop' ); ?>

            </a>

        </footer>

    <?php endif; ?>

</article>