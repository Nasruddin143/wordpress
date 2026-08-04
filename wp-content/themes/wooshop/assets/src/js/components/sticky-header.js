document.addEventListener('DOMContentLoaded', () => {

    const header = document.getElementById('site-header');

    if (!header) {
        return;
    }

    const stickyOffset = header.offsetTop;

    function updateStickyHeader() {

        if (window.scrollY > stickyOffset) {

            header.classList.add('is-sticky');
            document.body.classList.add('ws-header-sticky');

        } else {

            header.classList.remove('is-sticky');
            document.body.classList.remove('ws-header-sticky');

        }

    }

    window.addEventListener(
        'scroll',
        updateStickyHeader,
        { passive: true }
    );

    updateStickyHeader();

});