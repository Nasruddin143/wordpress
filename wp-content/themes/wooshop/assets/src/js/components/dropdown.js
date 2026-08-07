/**
 * Dropdown Component
 *
 * Generic dropdown handler.
 *
 * @package WooShop
 */

class Dropdown {

    constructor() {

        this.dropdowns = document.querySelectorAll(
            '[data-dropdown]'
        );

        this.init();

    }

    init() {

        if (!this.dropdowns.length) {
            return;
        }

        this.bind();

        this.outsideClick();

        this.escapeKey();

    }

    bind() {

        this.dropdowns.forEach((dropdown) => {

            const toggle = dropdown.querySelector(
                '[data-dropdown-toggle]'
            );

            if (!toggle) {
                return;
            }

            toggle.addEventListener('click', (event) => {

                event.preventDefault();

                event.stopPropagation();

                this.closeAll(dropdown);

                dropdown.classList.toggle('is-open');

            });

        });

    }

    outsideClick() {

        document.addEventListener('click', () => {

            this.closeAll();

        });

    }

    escapeKey() {

        document.addEventListener('keydown', (event) => {

            if (event.key === 'Escape') {

                this.closeAll();

            }

        });

    }

    closeAll(except = null) {

        this.dropdowns.forEach((dropdown) => {

            if (dropdown !== except) {

                dropdown.classList.remove('is-open');

            }

        });

    }

}

document.addEventListener('DOMContentLoaded', () => {

    new Dropdown();

});

export default Dropdown;