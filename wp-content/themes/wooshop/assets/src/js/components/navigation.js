/**
 * Navigation
 *
 * Handles:
 * - Desktop dropdowns
 * - Keyboard accessibility
 * - Mobile submenu toggle
 *
 * @package WooShop
 */

const Navigation = {

    init() {

        this.dropdown();

        this.keyboard();

        this.mobile();

    },

    dropdown() {

        const items = document.querySelectorAll(
            '.menu-item-has-children'
        );

        items.forEach((item) => {

            item.addEventListener('mouseenter', () => {

                item.classList.add('is-open');

            });

            item.addEventListener('mouseleave', () => {

                item.classList.remove('is-open');

            });

        });

    },

    keyboard() {

        const links = document.querySelectorAll(
            '.menu-item-has-children > a'
        );

        links.forEach((link) => {

            link.addEventListener('focus', () => {

                link.parentElement.classList.add('is-open');

            });

            link.addEventListener('blur', () => {

                link.parentElement.classList.remove('is-open');

            });

        });

    },

    mobile() {

        document
            .querySelectorAll('.submenu-toggle')
            .forEach((button) => {

                button.addEventListener('click', (event) => {

                    event.preventDefault();

                    const parent = button.closest(
                        '.menu-item-has-children'
                    );

                    parent.classList.toggle('is-open');

                });

            });

    }

};

document.addEventListener('DOMContentLoaded', () => {

    Navigation.init();

});

export default Navigation;