<?php
/**
 * Blog Header
 *
 * Displays the heading for the posts index.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<header class="ws-blog-header">

    <h1 class="ws-blog-title">

        <?php
        if ( is_home() && ! is_front_page() ) {

            $posts_page_id = get_option( 'page_for_posts' );

            if ( $posts_page_id ) {

                echo esc_html(
                    get_the_title( $posts_page_id )
                );

            } else {

                esc_html_e(
                    'Blog',
                    'wooshop'
                );
            }

        } else {

            esc_html_e(
                'Latest Posts',
                'wooshop'
            );
        }
        ?>

    </h1>

</header>