<?php
/**
 * Content Area
 *
 * Provides the reusable primary content wrapper.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

    <div class="ws-content-area-inner">

<?php
while ( have_posts() ) :
    the_post();

    get_template_part(
        'template-parts/content/content-page'
    );

endwhile;
?>

    </div>
