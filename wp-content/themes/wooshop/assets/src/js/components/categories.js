/**
 * Categories Component
 *
 * Handles the WooShop header categories dropdown.
 *
 * @package WooShop
 */

class Categories {

    constructor() {

        this.wrapper = document.querySelector('.ws-categories');

        if (!this.wrapper) {
            return;
        }

        this.toggle = this.wrapper.querySelector(
            '.ws-categories-toggle'
        );

        this.menu = this.wrapper.querySelector(
            '.ws-categories-menu'
        );

        if (!this.toggle || !this.menu) {
            return;
        }

        this.init();

    }

    /**
     * Initialize component.
     */
    init() {

        this.bindToggle();

        this.bindOutsideClick();

        this.bindKeyboard();

        this.bindSubmenus();

    }

    /**
     * Toggle categories menu.
     */
    bindToggle() {

        this.toggle.addEventListener(
            'click',
            (event) => {

                event.preventDefault();

                event.stopPropagation();

                this.wrapper.classList.toggle(
                    'is-open'
                );

                this.toggle.setAttribute(
                    'aria-expanded',
                    this.wrapper.classList.contains('is-open')
                );

            }
        );

    }

    /**
     * Close when clicking outside.
     */
    bindOutsideClick() {

        document.addEventListener(
            'click',
            (event) => {

                if (!this.wrapper.contains(event.target)) {

                    this.close();

                }

            }
        );

    }

    /**
     * ESC closes dropdown.
     */
    bindKeyboard() {

        document.addEventListener(
            'keydown',
            (event) => {

                if (event.key === 'Escape') {

                    this.close();

                }

            }
        );

    }

    /**
     * Nested categories.
     */
    bindSubmenus() {

        this.menu
            .querySelectorAll('.cat-toggle')
            .forEach((button) => {

                button.addEventListener(
                    'click',
                    (event) => {

                        event.preventDefault();

                        event.stopPropagation();

                        const item = button.closest(
                            '.menu-item-has-children'
                        );

                        if (!item) {
                            return;
                        }

                        item.classList.toggle(
                            'is-open'
                        );

                    }
                );

            });

    }

    /**
     * Close menu.
     */
    close() {

        this.wrapper.classList.remove(
            'is-open'
        );

        this.toggle.setAttribute(
            'aria-expanded',
            'false'
        );

        this.menu
            .querySelectorAll('.menu-item-has-children')
            .forEach((item) => {

                item.classList.remove(
                    'is-open'
                );

            });

    }

}

document.addEventListener(
    'DOMContentLoaded',
    () => {

        new Categories();

    }
);

export default Categories;