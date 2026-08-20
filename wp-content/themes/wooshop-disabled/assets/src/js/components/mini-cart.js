/**
 * WooShop Smart Off-Canvas Mini Cart
 *
 * Handles opening, closing, accessibility, and focus
 * management for the WooCommerce off-canvas cart.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {

    const cart = document.querySelector('[data-ws-mini-cart]');

    if (!cart) {
        return;
    }

    const drawer = cart.querySelector('.ws-mini-cart__drawer');

    const closeButtons = cart.querySelectorAll('[data-ws-cart-close]');

    let lastFocusedElement = null;

    /**
     * Open the mini cart.
     *
     * @returns {void}
     */
    const openCart = () => {

        lastFocusedElement = document.activeElement;

        cart.classList.add('is-open');

        cart.setAttribute('aria-hidden', 'false');

        document.documentElement.classList.add('ws-cart-open');

        if (drawer) {
            drawer.focus();
        }
    };

    /**
     * Close the mini cart.
     *
     * @returns {void}
     */
    const closeCart = () => {

        cart.classList.remove('is-open');

        cart.setAttribute('aria-hidden', 'true');

        document.documentElement.classList.remove('ws-cart-open');

        if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
            lastFocusedElement.focus();
        }
    };

    /**
     * Bind close controls.
     *
     * @returns {void}
     */
    closeButtons.forEach((button) => {

        button.addEventListener('click', closeCart);
    });

    /**
     * Open cart from WooShop cart triggers.
     */
    document.addEventListener('click', (event) => {

        const trigger = event.target.closest('[data-ws-cart-open]');

        if (!trigger) {
            return;
        }

        event.preventDefault();

        openCart();
    });

    /**
     * Open cart automatically after a successful
     * WooCommerce AJAX add-to-cart operation.
     */
    jQuery(document.body).on('added_to_cart', () => {
        openCart();
    });

    /**
     * Close cart with Escape.
     */
    document.addEventListener('keydown', (event) => {

        if (event.key === 'Escape' && cart.classList.contains('is-open')) {
            closeCart();
        }
    });

    /**
     * Close the cart when WooCommerce refreshes
     * its cart fragments.
     */
    jQuery(document.body).on('wc_fragments_refreshed', () => {

        if (cart.classList.contains('is-open')) {
            cart.classList.add('is-refreshed');

            window.setTimeout(() => {
                cart.classList.remove('is-refreshed');
            }, 150);
        }
    });
});