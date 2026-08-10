<?php
/**
 * WooCommerce Shop Archive Header
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<header class="ws-shop-header">

    <h1 class="ws-shop-title">
        <?php woocommerce_page_title(); ?>
    </h1>

    <?php
    do_action( 'woocommerce_archive_description' );
    ?>

</header>