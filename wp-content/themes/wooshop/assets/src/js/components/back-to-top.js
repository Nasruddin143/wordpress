/**
 * Back To Top
 *
 * @package WooShop
 */

class BackToTop {

    constructor() {

        this.button = document.querySelector('.ws-back-to-top');

        if (!this.button) {
            return;
        }

        this.offset = 300;

        this.ticking = false;

        this.init();

    }

    /**
     * Initialize component.
     */
    init() {

        this.bindScroll();

        this.bindClick();

    }

    /**
     * Window scroll.
     */
    bindScroll() {

        window.addEventListener(
            'scroll',
            () => {

                if (this.ticking) {
                    return;
                }

                this.ticking = true;

                requestAnimationFrame(() => {

                    this.update();

                    this.ticking = false;

                });

            },
            {
                passive: true,
            }
        );

    }

    /**
     * Click event.
     */
    bindClick() {

        this.button.addEventListener(
            'click',
            (event) => {

                event.preventDefault();

                window.scrollTo({

                    top: 0,

                    behavior: 'smooth',

                });

            }
        );

    }

    /**
     * Update visibility.
     */
    update() {

        if (window.scrollY >= this.offset) {

            this.button.classList.add(
                'is-visible'
            );

        } else {

            this.button.classList.remove(
                'is-visible'
            );

        }

    }

}

document.addEventListener(
    'DOMContentLoaded',
    () => {

        new BackToTop();

    }
);

export default BackToTop;