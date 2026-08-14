<?php
/**
 * Search Result Content
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<article
        id="post-<?php the_ID(); ?>"
        <?php post_class( 'ws-search-result' ); ?>>

    <?php if ( has_post_thumbnail() ) : ?>

        <div class="ws-search-result__thumbnail">

            <a
                    href="<?php the_permalink(); ?>"
                    aria-hidden="true"
                    tabindex="-1">

                <?php
                the_post_thumbnail(
                        'medium',
                        [
                                'loading' => 'lazy',
                        ]
                );
                ?>

            </a>

        </div>

    <?php endif; ?>

    <div class="ws-search-result__content">

        <header class="ws-search-result__header">

            <?php
            the_title(
                    '<h2 class="ws-search-result__title"><a href="' .
                    esc_url( get_permalink() ) .
                    '">',
                    '</a></h2>'
            );
            ?>

        </header>

        <div class="ws-search-result__excerpt">

            <?php
            the_excerpt();
            ?>

        </div>

        <a
                class="ws-search-result__link"
                href="<?php the_permalink(); ?>">

            <?php
            esc_html_e(
                    'Read More',
                    'wooshop'
            );
            ?>

            <span aria-hidden="true">
                &rarr;
            </span>

        </a>

    </div>

</article>