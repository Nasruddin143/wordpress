<?php
/**
 * Comments Template
 *
 * Displays the WordPress comments section.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
    return;
}
?>

<section
    id="comments"
    class="comments-area ws-comments mt-5"
>

    <?php if ( have_comments() ) : ?>

        <h2 class="comments-title h4 mb-4">

            <?php
            printf(
            /* translators: %s: number of comments. */
                esc_html(
                    _n(
                        '%s Comment',
                        '%s Comments',
                        get_comments_number(),
                        'wooshop'
                    )
                ),
                esc_html(
                    number_format_i18n(
                        get_comments_number()
                    )
                )
            );
            ?>

        </h2>

        <ol class="comment-list list-unstyled">

            <?php
            wp_list_comments(
                array(
                    'style'      => 'ol',
                    'short_ping' => true,
                    'avatar_size' => 48,
                )
            );
            ?>

        </ol>

        <?php the_comments_navigation(); ?>

    <?php endif; ?>

    <?php if ( comments_open() || get_comments_number() ) : ?>

        <?php comment_form(); ?>

    <?php endif; ?>

</section>