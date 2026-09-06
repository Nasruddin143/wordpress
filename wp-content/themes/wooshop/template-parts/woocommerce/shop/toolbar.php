<?php
/**
 * WooShop Shop Toolbar
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<div class="shop-toolbar d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

    <div class="shop-toolbar__result-count">

        <?php woocommerce_result_count(); ?>

    </div>

    <div class="shop-toolbar__ordering">

        <?php woocommerce_catalog_ordering(); ?>

    </div>

</div>