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

    <!-- Wishlist -->

    <a
            href="<?php echo esc_url($wishlist_url); ?>"
            class="btn btn-link text-decoration-none p-2 header-action header-action--wishlist"
            aria-label="<?php esc_attr_e('Wishlist', 'wooshop'); ?>"
    >

        <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
        >
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/>
        </svg>

        <span class="visually-hidden">
            <?php esc_html_e('Wishlist', 'wooshop'); ?>
        </span>

    </a>


    <!-- My Account -->

    <a
            href="<?php echo esc_url($account_url); ?>"
            class="btn btn-link text-decoration-none p-2 header-action header-action--account"
            aria-label="<?php esc_attr_e('My account', 'wooshop'); ?>"
    >

        <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
        >
            <circle cx="12" cy="7" r="4"/>

            <path d="M5.5 21a6.5 6.5 0 0 1 13 0"/>
        </svg>

        <span class="visually-hidden">
            <?php esc_html_e('My account', 'wooshop'); ?>
        </span>

    </a>


    <!-- Cart -->

    <?php if (class_exists('WooCommerce')) : ?>

        <button
                type="button"
                class="btn btn-link position-relative text-decoration-none p-2 header-action header-action--cart"
                data-bs-toggle="offcanvas"
                data-bs-target="#wooshop-mini-cart"
                aria-controls="wooshop-mini-cart"
                aria-label="<?php esc_attr_e('Open shopping cart', 'wooshop'); ?>"
        >

            <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                    fill="none"
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    aria-hidden="true"
            >
                <circle cx="9" cy="21" r="1"/>

                <circle cx="20" cy="21" r="1"/>

                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>

            <span
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-bg-primary mini-cart-count"
            >
                <?php echo esc_html($cart_count); ?>
            </span>

            <span class="visually-hidden">
                <?php
                _n(
                        '%d item in cart',
                        '%d items in cart',
                        $cart_count,
                        'wooshop'
                )
                    |> esc_html(...)
                    |> (fn($x) => printf($x, $cart_count));
                ?>
            </span>

        </button>

    <?php endif; ?>

</div>