/**
 * WooShop Social Sharing
 *
 * Handles product-link copying for the social-sharing component.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {

    const containers = document.querySelectorAll('[data-ws-social-sharing]');

    if (!containers.length) {
        return;
    }

    containers.forEach((container) => {

        const copyButton = container.querySelector('[data-ws-copy-link]');

        const message = container.querySelector('[data-ws-share-message]');

        if (!copyButton || !message) {
            return;
        }

        copyButton.addEventListener('click', async () => {

            const url = copyButton.dataset.shareUrl;

            if (!url) {
                return;
            }

            try {

                await navigator.clipboard.writeText(url);

                message.textContent = 'Link copied.';

                message.classList.add('is-visible');

            } catch (error) {

                const input = document.createElement('input');

                input.value = url;

                input.setAttribute('readonly', '');

                input.style.position = 'absolute';

                input.style.left = '-9999px';

                document.body.appendChild(input);

                input.select();

                document.execCommand('copy');

                input.remove();

                message.textContent = 'Link copied.';

                message.classList.add('is-visible');
            }

            window.setTimeout(() => {
                message.classList.remove('is-visible');
            }, 2000);
        });
    });
});