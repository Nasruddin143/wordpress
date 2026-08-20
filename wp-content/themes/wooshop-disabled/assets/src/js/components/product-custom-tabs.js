/**
 * WooShop Product Custom Tabs
 *
 * Handles accessible product tab navigation.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {

    const tabContainers = document.querySelectorAll('[data-ws-custom-tabs]');

    if (!tabContainers.length) {
        return;
    }

    tabContainers.forEach((container) => {

        const buttons = container.querySelectorAll('[data-ws-tab-target]');

        const panels = container.querySelectorAll('[role="tabpanel"]');

        const activateTab = (button) => {

            const targetId = button.dataset.wsTabTarget;

            buttons.forEach((item) => {

                const active = item === button;

                item.classList.toggle('is-active', active);

                item.setAttribute('aria-selected', active ? 'true' : 'false');

                item.setAttribute('tabindex', active ? '0' : '-1');
            });

            panels.forEach((panel) => {

                const active = panel.id === targetId;

                panel.classList.toggle('is-active', active);

                panel.hidden = !active;
            });
        };

        buttons.forEach((button, index) => {

            button.setAttribute('tabindex', index === 0 ? '0' : '-1');

            button.addEventListener('click', () => {
                activateTab(button);
            });

            button.addEventListener('keydown', (event) => {

                if (event.key !== 'ArrowRight' && event.key !== 'ArrowLeft') {
                    return;
                }

                event.preventDefault();

                const direction = event.key === 'ArrowRight' ? 1 : -1;

                const current = Array.from(buttons)
                    .indexOf(button);

                const next = (current + direction + buttons.length) % buttons.length;

                buttons[next].focus();
                activateTab(buttons[next]);
            });
        });
    });
});