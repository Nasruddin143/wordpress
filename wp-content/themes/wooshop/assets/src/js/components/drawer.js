/**
 * Drawer Component
 *
 * Generic off-canvas drawer.
 *
 * @package WooShop
 */

class Drawer {

    constructor() {

        this.drawers = document.querySelectorAll('[data-drawer]');

        if (!this.drawers.length) {
            return;
        }

        this.init();

    }

    /**
     * Initialize.
     */
    init() {

        this.bindOpen();

        this.bindClose();

        this.bindOverlay();

        this.bindKeyboard();

    }

    /**
     * Open drawer.
     */
    bindOpen() {

        document
            .querySelectorAll('[data-drawer-open]')
            .forEach((button) => {

                button.addEventListener('click', (event) => {

                    event.preventDefault();

                    const id = button.dataset.drawerOpen;

                    this.open(id);

                });

            });

    }

    /**
     * Close drawer.
     */
    bindClose() {

        document
            .querySelectorAll('[data-drawer-close]')
            .forEach((button) => {

                button.addEventListener('click', (event) => {

                    event.preventDefault();

                    this.close();

                });

            });

    }

    /**
     * Overlay click.
     */
    bindOverlay() {

        this.drawers.forEach((drawer) => {

            drawer.addEventListener('click', (event) => {

                if (event.target === drawer) {

                    this.close();

                }

            });

        });

    }

    /**
     * ESC key.
     */
    bindKeyboard() {

        document.addEventListener('keydown', (event) => {

            if (event.key === 'Escape') {

                this.close();

            }

        });

    }

    /**
     * Open.
     */
    open(id) {

        const drawer = document.getElementById(id);

        if (!drawer) {
            return;
        }

        drawer.classList.add('is-active');

        drawer.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.classList.add(
            'ws-drawer-open'
        );

    }

    /**
     * Close all.
     */
    close() {

        this.drawers.forEach((drawer) => {

            drawer.classList.remove('is-active');

            drawer.setAttribute(
                'aria-hidden',
                'true'
            );

        });

        document.body.classList.remove(
            'ws-drawer-open'
        );

    }

}

document.addEventListener(
    'DOMContentLoaded',
    () => {

        new Drawer();

    }
);

export default Drawer;