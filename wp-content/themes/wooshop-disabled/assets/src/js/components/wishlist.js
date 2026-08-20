/**
 * WooShop Wishlist
 *
 * Handles wishlist add/remove interactions.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {

    const buttons = document.querySelectorAll('[data-ws-wishlist]');

    if (!buttons.length || typeof WooShopWishlist === 'undefined') {
        return;
    }

    const toggleWishlist = async (button) => {

        const productId = button.dataset.productId;

        if (!productId) {
            return;
        }

        button.disabled = true;

        const data = new FormData();

        data.append('action', WooShopWishlist.action);

        data.append('nonce', WooShopWishlist.nonce);

        data.append('product_id', productId);

        try {

            const response = await fetch(WooShopWishlist.ajaxUrl, {
                method: 'POST', body: data,
            });

            const result = await response.json();

            if (!result.success) {
                return;
            }

            const active = result.data.active;

            button.classList.toggle('is-active', active);

            button.setAttribute('aria-pressed', active ? 'true' : 'false');

            button.setAttribute('aria-label', active ? WooShopWishlist.removeText : WooShopWishlist.addText);

            const text = button.querySelector('.ws-wishlist-button__text');

            if (text) {
                text.textContent = active ? WooShopWishlist.removeText : WooShopWishlist.addText;
            }

        } catch (error) {

            console.error('WooShop wishlist error:', error);

        } finally {

            button.disabled = false;
        }
    };

    buttons.forEach((button) => {

        button.addEventListener('click', () => toggleWishlist(button));

    });
});