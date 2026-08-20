/**
 * WooShop Product Brands
 *
 * Handles lightweight brand-list interactions.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {

    const brandLists = document.querySelectorAll('.ws-brand-list');

    if (!brandLists.length) {
        return;
    }

    brandLists.forEach((list) => {

        const items = list.querySelectorAll('.ws-brand-list__item');

        items.forEach((item) => {

            item.addEventListener('keydown', (event) => {

                if (event.key !== 'ArrowRight' && event.key !== 'ArrowLeft') {
                    return;
                }

                event.preventDefault();

                const currentIndex = Array.from(items).indexOf(item);

                const direction = event.key === 'ArrowRight' ? 1 : -1;

                const nextIndex = (currentIndex + direction + items.length) % items.length;

                items[nextIndex].focus();
            });
        });
    });
});