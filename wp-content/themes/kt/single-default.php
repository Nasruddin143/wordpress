<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Your_Theme_Name
 */

get_header(); // Loads the header.php file
?>

	<main id="primary" class="site-main">

		<?php
		// Start the Loop to fetch and display the post.
		while ( have_posts() ) :
			the_post();
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<header class="entry-header">
					<?php
					// Display the post title.
					the_title( '<h1 class="entry-title">', '</h1>' );
					?>
				</header><!-- .entry-header -->

				<div class="post-meta">
					<?php
					// Display the post date.
					echo '<time datetime="' . get_the_date( 'c' ) . '">' . get_the_date() . '</time>';
                    
                    // Display the author name linked to their archive page.
					echo ' by ' . get_the_author_posts_link();
                    
                    // Display the post categories.
					echo ' in ' . get_the_category_list( ', ' );
                    
                    // Display the post tags.
					if ( has_tag() ) {
						echo ' | Tags: ' . get_the_tag_list( '', ', ', '' );
					}
                    
                    // Display the "Edit" link for logged-in users with permissions.
					edit_post_link( 'Edit', ' | ', '' );
					?>
				</div><!-- .post-meta -->

				<?php
				// Display the post thumbnail (featured image) if it exists.
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'large' ); // You can specify an image size like 'large', 'full', etc.
				}
				?>

				<div class="entry-content">
					<?php
					// Display the main content of the post.
					the_content();

					// This function handles pagination for posts that are split into multiple pages using the <!--nextpage--> tag.
					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'your-theme-textdomain' ),
							'after'  => '</div>',
						)
					);
					?>
				</div><!-- .entry-content -->

				<footer class="entry-footer">
					<?php
					// Previous/next post navigation.
					the_post_navigation(
						array(
							'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'your-theme-textdomain' ) . '</span> <span class="nav-title">%title</span>',
							'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'your-theme-textdomain' ) . '</span> <span class="nav-title">%title</span>',
						)
					);
					?>
				</footer><!-- .entry-footer -->

			</article><!-- #post-<?php the_ID(); ?> -->

			<?php
			// Load the comments template. This will display the comments section if comments are open.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #primary -->

<?php
get_sidebar(); // Loads the sidebar.php file
get_footer(); // Loads the footer.php file
?>