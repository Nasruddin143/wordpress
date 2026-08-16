/**
 * WooShop Stock Scarcity
 *
 * Provides lightweight client-side behavior for
 * stock-scarcity indicators.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {

    const messages = document.querySelectorAll('.ws-stock-scarcity');

    if (!messages.length) {
        return;
    }

    messages.forEach((message) => {

        const quantity = parseInt(message.dataset.stockQuantity, 10);

        if (Number.isNaN(quantity) || quantity <= 0) {
            message.remove();
        }
    });
});