<?php
/**
 * Shop Header
 *
 * Displays breadcrumbs, shop title, and archive description.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<header class="ws-shop-header border-bottom">

    <div class="container py-4">

        <?php
        get_template_part(
            'template-parts/components/breadcrumbs'
        );
        ?>

        <div class="mt-3">

            <?php
            woocommerce_page_title(
                '<h1 class="ws-shop-title h2 mb-2">',
                '</h1>'
            );
            ?>

            <?php
            do_action( 'woocommerce_archive_description' );
            ?>

        </div>

    </div>

</header>