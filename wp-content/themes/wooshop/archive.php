<?php
/**
 * Archive Template
 *
 * Displays generic archives using the shared layout.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

    <div class="ws-archive-header-wrapper">

        <div class="container py-4">

            <?php
            get_template_part(
                'template-parts/components/breadcrumbs'
            );
            ?>

            <?php
            get_template_part(
                'template-parts/archive/archive-header'
            );
            ?>

        </div>

    </div>

<?php
get_template_part(
    'template-parts/layout/archive-layout'
);
?>

<?php
get_footer();