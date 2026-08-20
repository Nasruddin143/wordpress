/**
 * WooShop Size Guide
 *
 * Handles the responsive Size Guide dialog.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {

    const openButton = document.querySelector('[data-ws-size-guide-open]');

    const dialog = document.querySelector('[data-ws-size-guide]');

    if (!openButton || !dialog) {
        return;
    }

    const closeButtons = dialog.querySelectorAll('[data-ws-size-guide-close]');

    const openDialog = () => {

        dialog.classList.add('is-open');

        document.body.classList.add('ws-size-guide-open');

        openButton.setAttribute('aria-expanded', 'true');
    };

    const closeDialog = () => {

        dialog.classList.remove('is-open');

        document.body.classList.remove('ws-size-guide-open');

        openButton.setAttribute('aria-expanded', 'false');

        openButton.focus();
    };

    openButton.setAttribute('aria-expanded', 'false');

    openButton.addEventListener('click', openDialog);

    closeButtons.forEach((button) => {

        button.addEventListener('click', closeDialog);
    });

    document.addEventListener('keydown', (event) => {

        if (event.key === 'Escape' && dialog.classList.contains('is-open')) {
            closeDialog();
        }
    });
});