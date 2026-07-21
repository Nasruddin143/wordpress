document.addEventListener("DOMContentLoaded", function () {

    /* --------------------------
       GOOGLE MAP (Lazy Load)
    --------------------------- */
    document.querySelectorAll('.kt-map-placeholder').forEach(el => {
        el.addEventListener('click', () => {
            el.innerHTML = `<iframe src="${el.dataset.src}" loading="lazy"></iframe>`;
        });
    });

    /* --------------------------
       DESKTOP NAV
    --------------------------- */
    document.querySelectorAll('.offcanvas .menu-item-has-children > a')
        .forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const submenu = this.nextElementSibling;
                if (submenu) submenu.classList.toggle('show');
            });
        });

    /* --------------------------
       MOBILE NAVBAR
    --------------------------- */
    document.querySelectorAll('.mobile-nav .menu-item-has-children > a')
        .forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                const parent = this.parentElement;
                const submenu = this.nextElementSibling;
                if (!submenu) return;

                document.querySelectorAll('.mobile-nav .menu-item-has-children.open')
                    .forEach(item => {
                        if (item !== parent) {
                            item.classList.remove('open');
                            const sm = item.querySelector('.sub-menu');
                            if (sm) sm.classList.remove('show');
                        }
                    });

                parent.classList.toggle('open');
                submenu.classList.toggle('show');
            });
        });

    /* --------------------------
       HOVER NAVBAR
    --------------------------- */
    if (window.innerWidth >= 992) {
        document.querySelectorAll('.navbar .dropdown').forEach(el => {
            const menu = el.querySelector('.dropdown-menu');

            el.addEventListener('mouseenter', () => {
                el.classList.add('show');
                if (menu) menu.classList.add('show');
            });

            el.addEventListener('mouseleave', () => {
                el.classList.remove('show');
                if (menu) menu.classList.remove('show');
            });
        });
    }

    /* --------------------------
       CAROUSEL
    --------------------------- */
    document.querySelectorAll(".carousel[data-carousel-options]").forEach(slider => {
        let options = {};

        try {
            options = JSON.parse(slider.getAttribute("data-carousel-options"));
        } catch (e) { }

        new bootstrap.Carousel(slider, {
            interval: options.interval ?? 4000,
            ride: options.ride ?? false,
            wrap: options.wrap ?? true,
            pause: options.pause ?? false
        });
    });

    /* --------------------------
       SEARCH TOGGLE
    --------------------------- */
    const searchBox = document.querySelector(".expanding-search");
    const button = document.querySelector(".search-toggle");

    if (searchBox && button) {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            searchBox.classList.toggle("active");

            const input = searchBox.querySelector("input");
            if (input && searchBox.classList.contains("active")) {
                setTimeout(() => input.focus(), 250);
            }
        });

        document.addEventListener("click", function (e) {
            if (!searchBox.contains(e.target)) {
                searchBox.classList.remove("active");
            }
        });
    }

    /* --------------------------
       SHRINK NAVBAR
    --------------------------- */
    let isShrunk = false;

    window.addEventListener("scroll", function () {
        const el = document.getElementById("masthead");
        if (!el) return;

        const scrollY = window.scrollY;

        if (scrollY > 41 && !isShrunk) {
            el.classList.add("shrink");
            isShrunk = true;
        } else if (scrollY <= 41 && isShrunk) {
            el.classList.remove("shrink");
            isShrunk = false;
        }
    });

    /* --------------------------
       BACK TO TOP (ONLY ONCE ✅)
    --------------------------- */
    const btn = document.getElementById('kt-go-top');

    if (btn) {
        window.addEventListener('scroll', () => {
            btn.classList.toggle('show', window.scrollY > 300);
        });

        btn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }


    /* --------------------------
       REQUIRED FOR RATING SYSTEM
    --------------------------- */
    document.querySelectorAll('.kt-stars').forEach(el => {
        el.style.setProperty('--rating', el.dataset.rating);
    });


    /* --------------------------
       REVIEW MODAL POPUP
    --------------------------- */
    if (document.querySelector('.glsr-error')) {
        var modal = new bootstrap.Modal(document.getElementById('reviewModal-<?php echo $post_id; ?>'));
        modal.show();
    }
});