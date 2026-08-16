<?php
/**
 * Shop Toolbar
 *
 * Displays product count and WooCommerce sorting controls.
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="ws-shop-toolbar py-3 border-bottom">

    <div class="row align-items-center g-3">

        <div class="col-12 col-md">

            <?php
            woocommerce_result_count();
            ?>

        </div>

        <div class="col-12 col-md-auto">

            <div class="ws-shop-ordering">

                <?php
                woocommerce_catalog_ordering();
                ?>

            </div>

        </div>

    </div>

</div>