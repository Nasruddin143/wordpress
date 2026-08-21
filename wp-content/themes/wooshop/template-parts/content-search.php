<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WooShop
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
            <time
                    class="entry-date published"
                    datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"
            >
                <?php echo esc_html( get_the_date() ); ?>
            </time>

			<?php
			//wooshop_posted_on();
			wooshop_posted_by();
			?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

    <?php
    if ( has_post_thumbnail() ) :
        the_post_thumbnail(
                'large',
                array(
                        'class' => 'img-fluid w-100',
                )
        );
    endif;
    ?>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

    <footer class="entry-footer mt-4">

        <?php
        $categories = get_the_category_list( ', ' );

        if ( $categories ) :
            ?>
            <div class="cat-links mb-2">
			<span class="me-1">
				<?php esc_html_e( 'Posted in', 'wooshop' ); ?>
			</span>
                <?php echo wp_kses_post( $categories ); ?>
            </div>
        <?php
        endif;
        ?>

        <?php
        $tags = get_the_tag_list( '', ', ' );

        if ( $tags ) :
            ?>
            <div class="tags-links mb-2">
			<span class="me-1">
				<?php esc_html_e( 'Tagged', 'wooshop' ); ?>
			</span>
                <?php echo wp_kses_post( $tags ); ?>
            </div>
        <?php
        endif;
        ?>

        <?php
        if ( comments_open() || get_comments_number() ) :
            ?>
            <div class="comments-link mb-2">
                <?php comments_popup_link(); ?>
            </div>
        <?php
        endif;
        ?>

        <?php edit_post_link(); ?>

    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
