/**
 * WooShop Product Filters
 *
 * Handles mobile filter controls and AJAX product filtering.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-ws-product-filters]');

    if (!root || typeof WooShopProductFilters === 'undefined') {
        return;
    }

    const panel = root.querySelector('[data-ws-filter-panel]');
    const toggle = root.querySelector('[data-ws-filter-toggle]');
    const close = root.querySelector('[data-ws-filter-close]');
    const apply = root.querySelector('[data-ws-apply-filters]');
    const clear = root.querySelector('[data-ws-clear-filters]');

    const getProductsContainer = () => {
        return document.querySelector('.woocommerce ul.products');
    };

    const collectFilters = () => {
        const data = new FormData();

        data.append('action', WooShopProductFilters.action);

        data.append('nonce', WooShopProductFilters.nonce);

        root.querySelectorAll('[data-ws-filter]').forEach((input) => {

            if ((input.type === 'checkbox' && !input.checked) || input.value === '') {
                return;
            }

            data.append(input.name, input.value);
        });

        return data;
    };

    const applyFilters = async () => {
        const container = getProductsContainer();

        if (!container) {
            return;
        }

        container.classList.add('ws-products-loading');

        try {

            const response = await fetch(WooShopProductFilters.ajaxUrl, {
                method: 'POST', body: collectFilters(),
            });

            const result = await response.json();

            if (!result.success) {
                return;
            }

            const wrapper = document.querySelector('.woocommerce ul.products');

            if (wrapper && result.data.html) {
                wrapper.outerHTML = result.data.html;
            }

            closePanel();

        } catch (error) {

            console.error('WooShop product filter error:', error);

        } finally {

            container.classList.remove('ws-products-loading');
        }
    };

    const openPanel = () => {

        if (!panel) {
            return;
        }

        panel.classList.add('is-open');

        if (toggle) {
            toggle.setAttribute('aria-expanded', 'true');
        }
    };

    const closePanel = () => {

        if (!panel) {
            return;
        }

        panel.classList.remove('is-open');

        if (toggle) {
            toggle.setAttribute('aria-expanded', 'false');
        }
    };

    if (toggle) {
        toggle.addEventListener('click', openPanel);
    }

    if (close) {
        close.addEventListener('click', closePanel);
    }

    if (apply) {
        apply.addEventListener('click', applyFilters);
    }

    if (clear) {

        clear.addEventListener('click', () => {

            root
                .querySelectorAll('[data-ws-filter]')
                .forEach((input) => {

                    if (input.type === 'checkbox') {
                        input.checked = false;
                    }

                    if (input.type === 'number') {
                        input.value = '';
                    }
                });

            applyFilters();
        });
    }
});