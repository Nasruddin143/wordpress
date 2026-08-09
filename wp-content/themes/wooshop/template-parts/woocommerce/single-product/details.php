<?php
/**
 * WooShop Product Details.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product ) {
    return;
}
?>

<section class="ws-product-details">

    <?php
    /**
     * Product details before.
     */
    do_action(
        'wooshop_product_details_before'
    );
    ?>

    <div class="ws-product-details__tabs">

        <?php
        /**
         * Product tabs.
         */
        do_action(
            'wooshop_single_product_tabs'
        );
        ?>

    </div>

    <?php
    /**
     * Product details after tabs.
     */
    do_action(
        'wooshop_product_details_after_tabs'
    );
    ?>

    <div class="ws-product-details__upsells">

        <?php
        /**
         * Upsell products.
         */
        do_action(
            'wooshop_single_product_upsells'
        );
        ?>

    </div>

    <div class="ws-product-details__related">

        <?php
        /**
         * Related products.
         */
        do_action(
            'wooshop_single_product_related'
        );
        ?>

    </div>

    <?php
    /**
     * Product details after.
     */
    do_action(
        'wooshop_product_details_after'
    );
    ?>

</section>