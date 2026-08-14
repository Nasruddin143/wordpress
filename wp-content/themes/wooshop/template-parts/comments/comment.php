<?php
/**
 * Individual Comment
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<li
    id="comment-<?php comment_ID(); ?>"
    <?php comment_class( 'ws-comment' ); ?>>

    <article
        id="div-comment-<?php comment_ID(); ?>"
        class="comment-body">

        <header class="comment-meta">

            <div class="comment-author">

                <?php
                echo get_avatar(
                    get_comment(),
                    48,
                    '',
                    '',
                    [
                        'class' => [
                            'ws-comment-avatar',
                        ],
                    ]
                );
                ?>

                <span class="comment-author-name">

                    <?php
                    comment_author_link();
                    ?>

                </span>

            </div>

            <time
                class="comment-date"
                datetime="<?php echo esc_attr( get_comment_date( DATE_W3C ) ); ?>">

                <?php
                echo esc_html(
                    get_comment_date()
                );
                ?>

            </time>

        </header>

        <div class="comment-content">

            <?php
            comment_text();
            ?>

        </div>

        <?php if ( comments_open() ) : ?>

            <footer class="comment-actions">

                <?php
                comment_reply_link(
                    [
                        'depth'     => $GLOBALS['comment_depth'],
                        'max_depth' => get_option(
                            'thread_comments_depth'
                        ),
                    ]
                );
                ?>

            </footer>

        <?php endif; ?>

    </article>