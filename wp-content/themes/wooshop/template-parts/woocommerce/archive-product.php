<?php
/**
 * WooCommerce Product Archive
 *
 * Provides the main WooShop product archive structure.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

    <div class="ws-shop">

        <?php
        get_template_part(
            'template-parts/woocommerce/shop/shop-header'
        );
        ?>

        <div class="container">

            <?php
            get_template_part(
                'template-parts/woocommerce/shop/shop-toolbar'
            );
            ?>

            <?php
            get_template_part(
                'template-parts/woocommerce/shop/shop-products'
            );
            ?>

            <?php
            get_template_part(
                'template-parts/woocommerce/shop/shop-pagination'
            );
            ?>

        </div>

        <?php
        get_template_part(
            'template-parts/woocommerce/shop/shop-footer'
        );
        ?>

    </div>

<?php
get_footer();