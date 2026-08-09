<?php
/**
 * WooShop Product Gallery.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product ) {
    return;
}
?>

<div class="ws-product-gallery">

    <?php
    /**
     * Gallery before content.
     */
    do_action(
        'wooshop_product_gallery_before'
    );
    ?>

    <div class="ws-product-gallery__inner">

        <?php
        /**
         * Main WooCommerce gallery.
         */
        do_action(
            'wooshop_product_gallery_content'
        );
        ?>

    </div>

    <?php
    /**
     * Gallery after content.
     */
    do_action(
        'wooshop_product_gallery_after'
    );
    ?>

</div>