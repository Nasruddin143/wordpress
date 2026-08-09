<?php
/**
 * Comments Template
 *
 * Displays post comments and comment form.
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
    class="ws-comments-area">

    <?php if ( have_comments() ) : ?>

        <h2 class="comments-title">

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

        <ol class="comment-list">

            <?php
            wp_list_comments(
                [
                    'style'       => 'ol',
                    'short_ping'  => true,
                    'avatar_size' => 48,
                    'callback'    => 'wooshop_comment',
                ]
            );
            ?>

        </ol>

        <?php if ( get_comment_pages_count() > 1 ) : ?>

            <nav
                class="comment-navigation"
                aria-label="<?php esc_attr_e( 'Comments', 'wooshop' ); ?>">

                <div class="nav-previous">

                    <?php
                    previous_comments_link(
                        __( 'Older Comments', 'wooshop' )
                    );
                    ?>

                </div>

                <div class="nav-next">

                    <?php
                    next_comments_link(
                        __( 'Newer Comments', 'wooshop' )
                    );
                    ?>

                </div>

            </nav>

        <?php endif; ?>

    <?php endif; ?>

    <?php if ( comments_open() ) : ?>

        <div class="ws-comment-form">

            <?php
            comment_form();
            ?>

        </div>

    <?php endif; ?>

</section>