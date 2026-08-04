document.addEventListener('DOMContentLoaded', () => {

    document
        .querySelectorAll('.menu-item-has-children')
        .forEach((item) => {

            item.addEventListener('mouseenter', () => {

                const sub = item.querySelector('.sub-menu');

                if (sub) {

                    sub.style.display = 'block';

                }

            });

            item.addEventListener('mouseleave', () => {

                const sub = item.querySelector('.sub-menu');

                if (sub) {

                    sub.style.display = 'none';

                }

            });

        });

});