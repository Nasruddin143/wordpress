document.addEventListener('DOMContentLoaded', () => {

    const form = document.querySelector('.ws-search__form');

    if (!form) {
        return;
    }

    form.addEventListener('submit', () => {

        form.classList.add('is-searching');

    });

});