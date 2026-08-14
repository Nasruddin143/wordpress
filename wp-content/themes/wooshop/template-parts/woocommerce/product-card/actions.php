<?php
/**
 * Product Card Actions.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product ) {
    return;
}
?>

<div class="ws-product-card__actions">

    <?php
    /**
     * WooCommerce add-to-cart template.
     */
    woocommerce_template_loop_add_to_cart();
    ?>

</div>