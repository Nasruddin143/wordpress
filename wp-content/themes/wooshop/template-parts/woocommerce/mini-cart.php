<?php
/**
 * WooCommerce Mini Cart.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

if (!class_exists('WooCommerce')) {
    return;
}
?>

<div class="offcanvas offcanvas-end" tabindex="-1" id="wooshop-mini-cart" aria-labelledby="wooshop-mini-cart-label">

    <div class="offcanvas-header border-bottom">

        <h2 id="wooshop-mini-cart-label" class="offcanvas-title h5 mb-0">
            <?php esc_html_e('Your Cart', 'wooshop'); ?>
        </h2>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                aria-label="<?php esc_attr_e('Close cart', 'wooshop'); ?>"></button>

    </div>

    <div class="offcanvas-body p-0">

        <?php get_template_part('template-parts/woocommerce/mini-cart-content'); ?>

    </div>

</div>