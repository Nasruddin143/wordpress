/**
 * WooShop Product Waitlist
 *
 * Handles AJAX waitlist subscriptions.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {

    const containers = document.querySelectorAll('[data-ws-waitlist]');

    if (!containers.length) {
        return;
    }

    containers.forEach((container) => {

        const form = container.querySelector('[data-ws-waitlist-form]');

        const message = container.querySelector('[data-ws-waitlist-message]');

        if (!form || !message) {
            return;
        }

        form.addEventListener('submit', async (event) => {

            event.preventDefault();

            const button = form.querySelector('button[type="submit"]');

            if (button) {
                button.disabled = true;
            }

            message.textContent = '';

            const formData = new FormData(form);

            try {

                const response = await fetch(WooShopWaitlist.ajaxUrl, {
                    method: 'POST', body: formData, credentials: 'same-origin',
                });

                const data = await response.json();

                message.textContent = data?.data?.message || 'Unable to process your request.';

                message.classList.toggle('is-success', Boolean(data.success));

                message.classList.toggle('is-error', !data.success);

                if (data.success) {

                    const email = form.querySelector('input[name="email"]');

                    if (email) {
                        email.value = '';
                    }
                }

            } catch (error) {

                message.textContent = 'Unable to process your request. Please try again.';

                message.classList.add('is-error');

            } finally {

                if (button) {
                    button.disabled = false;
                }
            }
        });
    });
});