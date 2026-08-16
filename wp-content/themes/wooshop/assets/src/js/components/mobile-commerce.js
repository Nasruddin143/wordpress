/**
 * WooShop Mobile Commerce
 *
 * Handles the mobile sticky add-to-cart interaction.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {

    const button = document.querySelector('[data-ws-mobile-add-to-cart]');

    if (!button) {
        return;
    }

    button.addEventListener('click', () => {

        const addToCartButton = document.querySelector('.single_add_to_cart_button');

        if (!addToCartButton) {
            return;
        }

        addToCartButton.click();
    });
});