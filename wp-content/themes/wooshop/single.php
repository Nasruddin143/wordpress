<?php
/**
 * Single Post Template
 *
 * Displays an individual WordPress post.
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
                    'template-parts/content/content-single'
            );

        endwhile;
        ?>

    </div>

<?php
get_footer();