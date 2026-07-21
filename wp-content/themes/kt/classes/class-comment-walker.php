<?php
/**
 * Custom Comment Walker
 *
 * @package YourTheme
 * @since 1.0.0
 */

if ( ! class_exists( 'Theme_Walker_Comment' ) ) {

	class Theme_Walker_Comment extends Walker_Comment {

		/**
		 * Outputs a comment in HTML5 format.
		 *
		 * @param WP_Comment $comment Comment object.
		 * @param int        $depth   Comment depth.
		 * @param array      $args    Arguments.
		 */
		protected function html5_comment( $comment, $depth, $args ) {

			$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
			?>
			<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
				<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">

					<footer class="comment-meta">
						<div class="comment-author vcard">
							<?php
							$author_url  = get_comment_author_url( $comment );
							$author_name = get_comment_author( $comment );
							$avatar      = get_avatar( $comment, $args['avatar_size'] );

							if ( $args['avatar_size'] ) {
								if ( $author_url ) {
									echo '<a href="' . esc_url( $author_url ) . '" rel="external nofollow" class="url">';
								}
								echo wp_kses_post( $avatar );
								if ( $author_url ) {
									echo '</a>';
								}
							}

							printf(
								'<span class="fn">%1$s</span><span class="screen-reader-text says">%2$s</span>',
								esc_html( $author_name ),
								__( 'says:', 'your-theme-textdomain' )
							);
							?>
						</div>

						<div class="comment-metadata">
							<?php
							printf(
								'<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment, $args ) ),
								get_comment_time( 'c' ),
								esc_html( sprintf(
									__( '%1$s at %2$s', 'your-theme-textdomain' ),
									get_comment_date( '', $comment ),
									get_comment_time()
								) )
							);

							if ( get_edit_comment_link() ) {
								echo ' &bull; <a class="comment-edit-link" href="' . esc_url( get_edit_comment_link() ) . '">' .
									esc_html__( 'Edit', 'your-theme-textdomain' ) . '</a>';
							}
							?>
						</div>
					</footer>

					<div class="comment-content entry-content">
						<?php comment_text(); ?>

						<?php if ( '0' === $comment->comment_approved ) : ?>
							<p class="comment-awaiting-moderation">
								<?php esc_html_e( 'Your comment is awaiting moderation.', 'your-theme-textdomain' ); ?>
							</p>
						<?php endif; ?>
					</div>

					<?php
					$reply_link = get_comment_reply_link(
						array_merge(
							$args,
							array(
								'add_below' => 'div-comment',
								'depth'     => $depth,
								'max_depth' => $args['max_depth'],
								'before'    => '<span class="comment-reply">',
								'after'     => '</span>',
							)
						)
					);

					$is_author = (int) $comment->user_id === (int) get_post_field( 'post_author', $comment->comment_post_ID );

					if ( $reply_link || $is_author ) :
						?>
						<footer class="comment-footer-meta">
							<?php
							echo $reply_link; // escaped by WP
							if ( $is_author ) {
								echo '<span class="by-post-author">' .
									esc_html__( 'By Post Author', 'your-theme-textdomain' ) .
									'</span>';
							}
							?>
						</footer>
					<?php endif; ?>

				</article>
			<?php
		}
	}
}
