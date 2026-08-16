<?php
/**
 * Single Product Layout
 *
 * Provides the Bootstrap responsive layout for a product page.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>

<div class="ws-single-product">

    <div class="container">

        <?php
        get_template_part(
            'template-parts/components/breadcrumbs'
        );
        ?>

        <div class="row g-4 g-lg-5 py-4 py-lg-5">

            <div class="col-12 col-lg-6">

                <?php
                get_template_part(
                    'template-parts/woocommerce/single-product/product-gallery'
                );
                ?>

            </div>

            <div class="col-12 col-lg-6">

                <?php
                get_template_part(
                    'template-parts/woocommerce/single-product/product-summary'
                );
                ?>

            </div>

        </div>

        <?php
        get_template_part(
            'template-parts/woocommerce/single-product/product-tabs'
        );
        ?>

        <?php
        get_template_part(
            'template-parts/woocommerce/single-product/related-products'
        );
        ?>

    </div>

</div>