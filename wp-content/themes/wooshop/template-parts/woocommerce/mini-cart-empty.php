<?php
/**
 * WooShop Empty Mini Cart
 *
 * Displays the empty cart state.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;
?>

<div class="mini-cart-empty text-center py-5 px-3">

    <div class="mini-cart-empty__icon mb-4" aria-hidden="true">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="80" height="80"
             class="main-grid-item-icon text-secondary"
             fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1">
            <circle cx="9" cy="21" r="1"/>
            <circle cx="20" cy="21" r="1"/>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
        </svg>

    </div>

    <h3 class="mini-cart-empty__title h3 mb-3">

        <?php esc_html_e('Your cart is empty', 'wooshop'); ?>

    </h3>

    <p class="mini-cart-empty__description text-muted mb-4">

        <?php esc_html_e('Looks like you have not added anything to your cart yet.', 'wooshop'); ?>

    </p>

    <a href="<?php echo 'shop'
                |> wc_get_page_id(...)
                |> get_permalink(...)
                |> esc_url(...); ?>" class="btn btn-primary btn-cart-actions">

        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"
             class="main-grid-item-icon me-2"
             fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
            <line x1="3" x2="21" y1="6" y2="6"/>
            <path d="M16 10a4 4 0 0 1-8 0"/>
        </svg>


        <?php esc_html_e('Continue Shopping', 'wooshop'); ?>

    </a>

</div>