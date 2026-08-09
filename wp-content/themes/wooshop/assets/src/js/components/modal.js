/**
 * Modal Component
 *
 * Generic modal manager.
 *
 * @package WooShop
 */

class Modal {

    constructor() {

        this.modals = document.querySelectorAll('[data-modal]');

        if (!this.modals.length) {
            return;
        }

        this.init();

    }

    /**
     * Initialize.
     */
    init() {

        this.openButtons();

        this.closeButtons();

        this.overlay();

        this.keyboard();

    }

    /**
     * Open modal.
     */
    openButtons() {

        document
            .querySelectorAll('[data-modal-open]')
            .forEach((button) => {

                button.addEventListener('click', (event) => {

                    event.preventDefault();

                    const id = button.dataset.modalOpen;

                    this.open(id);

                });

            });

    }

    /**
     * Close buttons.
     */
    closeButtons() {

        document
            .querySelectorAll('[data-modal-close]')
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
    overlay() {

        this.modals.forEach((modal) => {

            modal.addEventListener('click', (event) => {

                if (event.target === modal) {

                    this.close();

                }

            });

        });

    }

    /**
     * ESC key.
     */
    keyboard() {

        document.addEventListener('keydown', (event) => {

            if (event.key === 'Escape') {

                this.close();

            }

        });

    }

    /**
     * Open modal.
     */
    open(id) {

        const modal = document.getElementById(id);

        if (!modal) {

            return;

        }

        modal.classList.add('is-active');

        modal.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.classList.add(
            'ws-modal-open'
        );

    }

    /**
     * Close all modals.
     */
    close() {

        this.modals.forEach((modal) => {

            modal.classList.remove('is-active');

            modal.setAttribute(
                'aria-hidden',
                'true'
            );

        });

        document.body.classList.remove(
            'ws-modal-open'
        );

    }

}

document.addEventListener(
    'DOMContentLoaded',
    () => {

        new Modal();

    }
);

export default Modal;