/**
 * Mobile Menu
 *
 * Bootstrap Offcanvas integration.
 *
 * @package WooShop
 */

class MobileMenu {

    constructor() {

        this.menu = document.getElementById('wsMobileMenu');

        this.toggle = document.querySelector(
            '.ws-mobile-toggle'
        );

        if (!this.menu || !this.toggle) {

            return;

        }

        this.init();

    }

    init() {

        this.bootstrapEvents();

        this.submenus();

        this.keyboard();

    }

    /**
     * Bootstrap Offcanvas Events
     */
    bootstrapEvents() {

        this.menu.addEventListener(
            'show.bs.offcanvas',
            () => {

                document.body.classList.add(
                    'ws-mobile-menu-open'
                );

                this.toggle.setAttribute(
                    'aria-expanded',
                    'true'
                );

            }
        );

        this.menu.addEventListener(
            'hidden.bs.offcanvas',
            () => {

                document.body.classList.remove(
                    'ws-mobile-menu-open'
                );

                this.toggle.setAttribute(
                    'aria-expanded',
                    'false'
                );

                this.closeSubmenus();

            }
        );

    }

    /**
     * Mobile submenu
     */
    submenus() {

        this.menu
            .querySelectorAll('.submenu-toggle')
            .forEach((button) => {

                button.addEventListener(
                    'click',
                    (event) => {

                        event.preventDefault();

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
     * ESC key
     */
    keyboard() {

        document.addEventListener(
            'keydown',
            (event) => {

                if (event.key !== 'Escape') {

                    return;

                }

                const instance =
                    bootstrap.Offcanvas.getInstance(
                        this.menu
                    );

                if (instance) {

                    instance.hide();

                }

            }
        );

    }

    /**
     * Reset menu state
     */
    closeSubmenus() {

        this.menu
            .querySelectorAll(
                '.menu-item-has-children'
            )
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

        new MobileMenu();

    }
);

export default MobileMenu;