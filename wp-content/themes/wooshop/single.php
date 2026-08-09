<?php
/**
 * Single Post Template
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

    <main
            id="primary"
            class="site-main">

        <div class="ws-container">

            <?php
            while ( have_posts() ) :
                the_post();

                get_template_part(
                        'template-parts/content/content',
                        'single'
                );

            endwhile;
            ?>

            <?php
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }
            ?>

        </div>

    </main>

<?php
get_footer();