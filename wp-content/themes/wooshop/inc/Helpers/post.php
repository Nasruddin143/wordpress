<?php
/**
 * Post Helpers
 *
 * WordPress post metadata helpers.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

/**
 * Determine whether the current post has metadata.
 *
 * @return bool
 */
function wooshop_has_post_meta(): bool
{
    return (
        get_the_author_meta( 'ID' ) ||
        get_the_date() ||
        get_the_category()
    );
}

/**
 * Get post author URL.
 *
 * @return string
 */
function wooshop_get_author_url(): string
{
    $author_id = (int) get_the_author_meta( 'ID' );

    if ( ! $author_id ) {
        return '';
    }

    $url = get_author_posts_url( $author_id );

    return $url ? $url : '';
}

/**
 * Get post comments URL.
 *
 * @return string
 */
function wooshop_get_comments_url(): string
{
    $comments_link = get_comments_link();

    return $comments_link ? $comments_link : '';
}

/**
 * Render a single comment.
 *
 * @param WP_Comment $comment Comment object.
 * @param array      $args    Comment arguments.
 * @param int        $depth   Current comment depth.
 *
 * @return void
 */
function wooshop_comment(
    $comment,
    array $args,
    int $depth
): void {

    $GLOBALS['comment'] = $comment;

    get_template_part(
        'template-parts/comments/comment',
        null,
        [
            'comment' => $comment,
            'args'    => $args,
            'depth'   => $depth,
        ]
    );
}

/**
 * Get the current post author ID.
 *
 * @return int
 */
function wooshop_get_post_author_id(): int
{
    return (int) get_the_author_meta( 'ID' );
}

/**
 * Determine whether the current post has an author.
 *
 * @return bool
 */
function wooshop_has_post_author(): bool
{
    return wooshop_get_post_author_id() > 0;
}