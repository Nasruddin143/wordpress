<?php
/**
 * WooShop Product Summary.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product ) {
    return;
}
?>

<div class="ws-product-summary">

    <?php
    /**
     * Summary before.
     */
    do_action(
        'wooshop_product_summary_before'
    );
    ?>

    <div class="ws-product-summary__content">

        <?php
        /**
         * Product title.
         */
        do_action(
            'wooshop_product_summary_title'
        );
        ?>

        <?php
        /**
         * Rating and reviews.
         */
        do_action(
            'wooshop_product_summary_rating'
        );
        ?>

        <?php
        /**
         * Price.
         */
        do_action(
            'wooshop_product_summary_price'
        );
        ?>

        <?php
        /**
         * Short description.
         */
        do_action(
            'wooshop_product_summary_excerpt'
        );
        ?>

        <?php
        /**
         * Stock status.
         */
        do_action(
            'wooshop_product_summary_stock'
        );
        ?>

        <div class="ws-product-summary__cart">

            <?php
            /**
             * Product add-to-cart.
             */
            do_action(
                    'wooshop_product_summary_cart'
            );
            ?>

        </div>

        <?php
        /**
         * Product meta.
         */
        do_action(
            'wooshop_product_summary_meta'
        );
        ?>

        <?php
        /**
         * Product sharing.
         */
        do_action(
            'wooshop_product_summary_sharing'
        );
        ?>

    </div>

    <?php
    /**
     * Summary after.
     */
    do_action(
        'wooshop_product_summary_after'
    );
    ?>

</div>