/**
 * Search Component
 *
 * AJAX-ready search handler.
 *
 * @package WooShop
 */

import debounce from '../utils/debounce';

class Search {

    constructor() {

        this.forms = document.querySelectorAll('.ws-search');

        if (!this.forms.length) {
            return;
        }

        this.init();

    }

    init() {

        this.forms.forEach((form) => {

            const input = form.querySelector(
                '.ws-search-input'
            );

            if (!input) {
                return;
            }

            input.addEventListener(
                'input',
                debounce(
                    (event) => this.onInput(event, form),
                    300
                )
            );

            input.addEventListener(
                'keydown',
                (event) => this.onKeydown(event, form)
            );

        });

    }

    /**
     * Input event.
     */
    onInput(event, form) {

        const value = event.target.value.trim();

        if (value.length < 2) {

            this.hideResults(form);

            return;

        }

        this.showLoading(form);

        /*
         * Future AJAX request.
         */
        console.log(
            'Searching:',
            value
        );

    }

    /**
     * Keyboard shortcuts.
     */
    onKeydown(event, form) {

        switch (event.key) {

            case 'Escape':

                this.hideResults(form);

                break;

        }

    }

    /**
     * Loading state.
     */
    showLoading(form) {

        const spinner = form.querySelector(
            '.ws-search-spinner'
        );

        if (spinner) {

            spinner.hidden = false;

        }

    }

    /**
     * Hide loading.
     */
    hideLoading(form) {

        const spinner = form.querySelector(
            '.ws-search-spinner'
        );

        if (spinner) {

            spinner.hidden = true;

        }

    }

    /**
     * Show results.
     */
    showResults(form, html) {

        const results = form.querySelector(
            '.ws-search-results'
        );

        if (!results) {
            return;
        }

        results.innerHTML = html;

        results.hidden = false;

        this.hideLoading(form);

    }

    /**
     * Hide results.
     */
    hideResults(form) {

        const results = form.querySelector(
            '.ws-search-results'
        );

        if (results) {

            results.hidden = true;

            results.innerHTML = '';

        }

        this.hideLoading(form);

    }

}

document.addEventListener(
    'DOMContentLoaded',
    () => {

        new Search();

    }
);

export default Search;