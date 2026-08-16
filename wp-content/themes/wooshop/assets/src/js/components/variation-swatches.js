/**
 * WooShop Variation Swatches
 *
 * Synchronizes visual swatches with WooCommerce's native
 * variation dropdowns.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {

    const containers = document.querySelectorAll('[data-ws-swatches]');

    if (!containers.length) {
        return;
    }

    containers.forEach((container) => {

        const select = container.querySelector('select');

        const swatches = container.querySelectorAll('[data-ws-swatch]');

        if (!select || !swatches.length) {
            return;
        }

        const updateState = () => {

            const currentValue = select.value;

            swatches.forEach((swatch) => {

                const value = swatch.dataset.value;

                const active = (currentValue === value);

                swatch.classList.toggle('is-selected', active);

                swatch.setAttribute('aria-pressed', active ? 'true' : 'false');
            });
        };

        swatches.forEach((swatch) => {

            swatch.addEventListener('click', () => {

                const value = swatch.dataset.value;

                select.value = value;

                /*
                 * Notify WooCommerce that the
                 * variation attribute changed.
                 */
                select.dispatchEvent(new Event('change', {
                    bubbles: true
                }));

                updateState();
            });
        });

        select.addEventListener('change', updateState);

        /*
         * Initial state.
         */
        updateState();
    });
});