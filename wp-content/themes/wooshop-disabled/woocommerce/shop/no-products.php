<?php
/**
 * WooCommerce Empty Shop State
 *
 * @package WooShop
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="ws-shop-empty">

    <h2 class="ws-shop-empty-title">
        <?php
        esc_html_e(
            'No products found.',
            'wooshop'
        );
        ?>
    </h2>

    <p class="ws-shop-empty-text">
        <?php
        esc_html_e(
            'There are currently no products available.',
            'wooshop'
        );
        ?>
    </p>

</section>