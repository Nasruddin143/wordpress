/**
 * Spinner Component
 *
 * Global loading indicator.
 *
 * @package WooShop
 */

class Spinner {

    constructor() {

        this.spinner = document.querySelector(
            '.ws-spinner'
        );

    }

    /**
     * Show spinner.
     */
    show() {

        if (!this.spinner) {

            return;

        }

        this.spinner.hidden = false;

        document.body.classList.add(
            'ws-loading'
        );

    }

    /**
     * Hide spinner.
     */
    hide() {

        if (!this.spinner) {

            return;

        }

        this.spinner.hidden = true;

        document.body.classList.remove(
            'ws-loading'
        );

    }

    /**
     * Toggle spinner.
     */
    toggle(state = true) {

        state
            ? this.show()
            : this.hide();

    }

}

const spinner = new Spinner();

export default spinner;