<?php
/**
 * Page Template
 *
 * Displays individual WordPress pages.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

    <main
            id="primary"
            class="site-main ws-page-main">

        <div class="ws-container">

            <?php
            while ( have_posts() ) :

                the_post();

                get_template_part(
                        'template-parts/content/content',
                        'page'
                );

            endwhile;
            ?>

        </div>

    </main>

<?php
get_footer();