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

        <h2 id="wooshop-mini-cart-label" class="offcanvas-title h5 mb-0 d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="main-grid-item-icon me-2" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                <line x1="3" x2="21" y1="6" y2="6" />
                <path d="M16 10a4 4 0 0 1-8 0" />
            </svg>

            <?php esc_html_e('Your Cart', 'wooshop'); ?>
        </h2>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                aria-label="<?php esc_attr_e('Close cart', 'wooshop'); ?>"></button>

    </div>

    <div class="offcanvas-body p-3">

        <?php get_template_part('template-parts/woocommerce/mini-cart-content'); ?>

    </div>

</div>