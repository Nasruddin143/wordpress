/**
 * Sticky Header
 *
 * Smart sticky header with hide/show on scroll.
 *
 * @package WooShop
 */

class StickyHeader {

    constructor() {

        this.header = document.querySelector('.ws-site-header');

        if (!this.header) {
            return;
        }

        this.lastScroll = window.scrollY;

        this.offset = 100;

        this.ticking = false;

        this.bind();

    }

    /**
     * Register events.
     */
    bind() {

        window.addEventListener(
            'scroll',
            () => this.onScroll(),
            {
                passive: true,
            }
        );

    }

    /**
     * Scroll handler.
     */
    onScroll() {

        if (this.ticking) {
            return;
        }

        this.ticking = true;

        requestAnimationFrame(() => {

            this.update();

            this.ticking = false;

        });

    }

    /**
     * Update header state.
     */
    update() {

        const current = window.scrollY;

        /*
         * Back to top.
         */
        if (current <= this.offset) {

            this.header.classList.remove(
                'is-sticky',
                'is-hidden'
            );

            this.lastScroll = current;

            return;

        }

        /*
         * Sticky.
         */
        this.header.classList.add(
            'is-sticky'
        );

        /*
         * Hide while scrolling down.
         */
        if (current > this.lastScroll) {

            this.header.classList.add(
                'is-hidden'
            );

        } else {

            this.header.classList.remove(
                'is-hidden'
            );

        }

        this.lastScroll = current;

    }

}

document.addEventListener(
    'DOMContentLoaded',
    () => {

        new StickyHeader();

    }
);

export default StickyHeader;