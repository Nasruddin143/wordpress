<?php
/**
 * Front Page Template
 *
 * Displays the site's static front page.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

    <div class="container py-5">

        <?php
        while ( have_posts() ) :
            the_post();

            get_template_part(
                'template-parts/content/content-page'
            );

        endwhile;
        ?>

    </div>

<?php
get_footer();