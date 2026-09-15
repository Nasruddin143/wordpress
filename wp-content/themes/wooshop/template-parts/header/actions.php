<?php
/**
 * Header Actions.
 *
 * Cart, wishlist and account actions.
 *
 * @package WooShop
 */

defined('ABSPATH') || exit;

$cart_count = 0;
$wishlist_count = 0;

if (class_exists('WooCommerce') && function_exists('WC') && WC()->cart) {
    $cart_count = WC()->cart->get_cart_contents_count();
}

$account_url = '';

if (class_exists('WooCommerce')) {
    $account_url = wc_get_page_permalink('myaccount');
} else {
    $account_url = wp_login_url();
}

/*
 * Wishlist URL.
 *
 * This can later be connected to your
 * WooShop Wishlist module.
 */
$wishlist_url = apply_filters('wooshop_wishlist_url', '#');
?>

<div class="header-actions d-flex align-items-center gap-2">

    <ul class="nav shop-icons">

        <li class="nav-item shop-icon">
            <!-- My Account -->
            <a href="<?php echo esc_url($account_url); ?>"
               class="nav-link header-action header-action--account"
               aria-label="<?php esc_attr_e('My account', 'wooshop'); ?>">

                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="26" height="26"
                         class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div class="label">
                    <?php esc_html_e('Account', 'wooshop'); ?>
                </div>
            </a>
        </li>

        <li class="nav-item shop-icon">
            <!-- Wishlist -->
            <a href="<?php echo esc_url($wishlist_url); ?>"
               class="nav-link position-relative header-action header-action--wishlist"
               aria-label="<?php esc_attr_e('Wishlist', 'wooshop'); ?>">

                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="26" height="26"
                         class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>

                    <span class="position-absolute top-0 start-100 translate-middle badge fw-light lh-sm rounded-pill text-bg-primary wish-list-count">
                        <?php echo esc_html($wishlist_count); ?>
                    </span>
                </div>

                <div class="label">
                    <?php esc_html_e('Wishlist', 'wooshop'); ?>
                </div>
            </a>
        </li>

        <?php if (class_exists('WooCommerce')) : ?>
            <li class="nav-item shop-icon">
                <!-- Cart -->
                <a class="nav-link position-relative header-action header-action--cart"
                   data-bs-toggle="offcanvas" data-bs-target="#wooshop-mini-cart" aria-controls="wooshop-mini-cart"
                   aria-label="<?php esc_attr_e('Open shopping cart', 'wooshop'); ?>">

                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="26" height="26"
                             class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round"
                             stroke-linejoin="round" stroke-width="2">
                            <circle cx="9" cy="21" r="1"/>
                            <circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>

                        <span class="position-absolute top-0 start-100 translate-middle badge fw-light lh-sm rounded-pill text-bg-primary mini-cart-count">
                            <?php echo esc_html($cart_count); ?>
                        </span>
                    </div>

                    <div class="label">
                        <?php esc_html_e('Cart', 'wooshop'); ?>
                    </div>
                </a>
            </li>
        <?php endif; ?>

    </ul>
</div>