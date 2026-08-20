/**
 * WooShop Payment Icons
 *
 * Provides lightweight interaction for payment icons.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {

    const containers = document.querySelectorAll('.ws-payment-icons');

    if (!containers.length) {
        return;
    }

    containers.forEach((container) => {

        const items = container.querySelectorAll('.ws-payment-icons__item');

        items.forEach((item) => {

            item.addEventListener('focus', () => {
                item.classList.add('is-focused');
            });

            item.addEventListener('blur', () => {
                item.classList.remove('is-focused');
            });
        });
    });
});