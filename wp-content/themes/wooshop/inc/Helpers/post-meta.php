<?php
/**
 * Post Meta Helpers
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wooshop_post_meta' ) ) {

    function wooshop_post_meta(): void {

        if ( 'post' !== get_post_type() ) {
            return;
        }
        ?>

        <div class="entry-meta">

            <time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">

                <?php echo esc_html( get_the_date() ); ?>

            </time>

            <span class="byline">

				<?php the_author_posts_link(); ?>

			</span>

        </div>

        <?php
    }
}