<?php
/**
 * Related Products
 *
 * Displays WooCommerce related products.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="ws-related-products">

    <?php
    woocommerce_output_related_products();
    ?>

</section>