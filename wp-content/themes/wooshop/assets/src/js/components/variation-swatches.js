/**
 * WooShop Variation Swatches
 *
 * Progressive enhancement for WooCommerce variation selects.
 *
 * @package WooShop
 */

(function () {
    'use strict';

    /**
     * Variation swatches controller.
     */
    const VariationSwatches = {

        /**
         * Initialize.
         *
         * @return {void}
         */
        init() {
            const forms = document.querySelectorAll('.ws-product-summary__cart .variations_form');

            if (!forms.length) {
                return;
            }

            forms.forEach((form) => {
                this.initForm(form);
            });
        },

        /**
         * Initialize a variation form.
         *
         * @param {HTMLElement} form Variation form.
         * @return {void}
         */
        initForm(form) {
            const selects = form.querySelectorAll('.variations select');

            if (!selects.length) {
                return;
            }

            selects.forEach((select) => {
                this.createSwatches(select);
            });
        },

        /**
         * Create swatches for a select.
         *
         * @param {HTMLSelectElement} select Variation select.
         * @return {void}
         */
        createSwatches(select) {
            if (select.dataset.wsSwatchesInitialized === 'true') {
                return;
            }

            select.dataset.wsSwatchesInitialized = 'true';

            const options = Array.from(select.options).filter((option) => option.value);

            if (!options.length) {
                return;
            }

            const wrapper = document.createElement('div');

            wrapper.className = 'ws-variation-swatches';

            wrapper.setAttribute('role', 'group');

            const attribute = select.name || '';

            const type = this.getSwatchType(attribute);

            wrapper.dataset.type = type;

            options.forEach((option) => {
                const button = this.createSwatch(option, type);

                if (!button) {
                    return;
                }

                button.addEventListener('click', () => {
                    this.selectOption(select, option.value, wrapper);
                });

                wrapper.appendChild(button);
            });

            select.hidden = true;

            select.parentNode.insertBefore(wrapper, select);

            this.syncSelection(select, wrapper);

            select.addEventListener('change', () => {
                this.syncSelection(select, wrapper);
            });
        },

        /**
         * Select a variation option.
         *
         * @param {HTMLSelectElement} select Variation select.
         * @param {string} value Selected value.
         * @param {HTMLElement} wrapper Swatch wrapper.
         * @return {void}
         */
        selectOption(select, value, wrapper) {
            select.value = value;

            select.dispatchEvent(new Event('change', {
                bubbles: true
            }));

            this.syncSelection(select, wrapper);
        },

        /**
         * Synchronize swatch state.
         *
         * @param {HTMLSelectElement} select Variation select.
         * @param {HTMLElement} wrapper Swatch wrapper.
         * @return {void}
         */
        syncSelection(select, wrapper) {
            const buttons = wrapper.querySelectorAll('.ws-variation-swatch');

            buttons.forEach((button) => {
                const selected = button.dataset.value === select.value;

                button.classList.toggle('is-selected', selected);

                button.setAttribute('aria-pressed', selected ? 'true' : 'false');
            });
        }, getSwatchType(attribute) {
            const normalized = attribute
                .toLowerCase()
                .replace(/^attribute_/, '')
                .replace(/^pa_/, '');

            if (normalized === 'color' || normalized === 'colour') {
                return 'color';
            }

            return 'label';
        }, createSwatch(option, type) {
            const button = document.createElement('button');

            button.type = 'button';

            button.className = 'ws-variation-swatch';

            button.dataset.value = option.value;

            button.setAttribute('aria-label', option.textContent.trim());

            button.setAttribute('aria-pressed', 'false');

            switch (type) {

                case 'color':
                    button.classList.add('ws-variation-swatch--color');

                    button.style.setProperty('--ws-swatch-color', option.dataset.color || '');

                    break;

                case 'image':
                    button.classList.add('ws-variation-swatch--image');

                    if (option.dataset.image) {
                        const image = document.createElement('img');

                        image.src = option.dataset.image;

                        image.alt = option.textContent.trim();

                        button.appendChild(image);
                    }

                    break;

                case 'label':
                default:
                    button.classList.add('ws-variation-swatch--label');

                    button.textContent = option.textContent.trim();

                    break;
            }

            return button;
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        VariationSwatches.init();
    });
})();