/**
 * WooShop Mobile Sales
 *
 * Handles the mobile sticky add-to-cart interaction.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {

    const bar = document.querySelector('[data-ws-mobile-sales]');

    if (!bar) {
        return;
    }

    const addButton = bar.querySelector('[data-ws-mobile-add]');

    const cartForm = document.querySelector('form.cart');

    if (!addButton || !cartForm) {
        return;
    }

    const syncVisibility = () => {

        const rect = cartForm.getBoundingClientRect();

        const visible = rect.top < window.innerHeight && rect.bottom > 0;

        bar.classList.toggle('is-visible', !visible);
    };

    addButton.addEventListener('click', () => {

        /*
         * Native WooCommerce forms handle:
         * - simple products
         * - variable products
         * - grouped products
         * - quantity
         * - variation selection.
         */

        const submitButton = cartForm.querySelector('.single_add_to_cart_button');

        if (!submitButton) {
            return;
        }

        submitButton.click();
    });

    window.addEventListener('scroll', syncVisibility, {
        passive: true,
    });

    window.addEventListener('resize', syncVisibility);

    syncVisibility();
});