<?php
/**
 * Product Summary
 *
 * Displays the WooCommerce single-product summary hooks.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-product-summary">

    <?php
    do_action( 'woocommerce_single_product_summary' );
    ?>

</div>