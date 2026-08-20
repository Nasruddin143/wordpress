/**
 * WooShop Free Shipping Bar
 *
 * Keeps the free-shipping progress indicator synchronized
 * with WooCommerce cart-fragment updates.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {

    const getBar = () => document.querySelector('[data-ws-free-shipping]');

    const updateBar = () => {

        const bar = getBar();

        if (!bar) {
            return;
        }

        const threshold = parseFloat(bar.dataset.threshold);

        const cart = parseFloat(bar.dataset.cart);

        if (Number.isNaN(threshold) || threshold <= 0) {
            return;
        }

        const progress = Math.min(100, (cart / threshold) * 100);

        const progressElement = bar.querySelector('[data-ws-free-shipping-progress]');

        if (progressElement) {

            progressElement.style.width = `${progress}%`;
        }

        const progressContainer = bar.querySelector('.ws-free-shipping-bar__progress');

        if (progressContainer) {

            progressContainer.setAttribute('aria-valuenow', String(progress));
        }
    };

    /**
     * WooCommerce fires this event after cart
     * fragments have been refreshed.
     */
    if (window.jQuery) {

        jQuery(document.body).on('wc_fragments_refreshed', updateBar);

        jQuery(document.body).on('updated_wc_div', updateBar);

        jQuery(document.body).on('added_to_cart', updateBar);
    }

    updateBar();
});