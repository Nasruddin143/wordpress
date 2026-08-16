<?php
/**
 * Blog Home Template
 *
 * Displays the site's blog posts.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

    <div class="container py-5">

        <?php
        get_template_part(
            'template-parts/archive/archive-header'
        );

        get_template_part(
            'template-parts/archive/archive-loop'
        );
        ?>

    </div>

<?php
get_footer();