document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.kt-slider-carousel').forEach(section => {

        const slider = section.querySelector('.kt-swiper');

        if (!slider || slider.classList.contains('swiper-initialized')) return;

        const nextBtn = section.querySelector('.swiper-button-next');
        const prevBtn = section.querySelector('.swiper-button-prev');
 
        // Define default breakpoints
        let breakpoints = {
            0: { slidesPerView: 1 },
            576: { slidesPerView: 2 },
            768: { slidesPerView: 3 },
            1200: { slidesPerView: 4 }
        };

        // 🔹 Brand slider override
        if (section.classList.contains('kt-brands')) {
            breakpoints = {
                0: { slidesPerView: 2 },
                576: { slidesPerView: 3 },
                768: { slidesPerView: 4 },
                1200: { slidesPerView: 6 }
            };
        }

        // 🔹 Testimonials slider override
        if (section.classList.contains('kt-testimonials')) {
            breakpoints = {
                0: { slidesPerView: 1 },
                576: { slidesPerView: 1 },
                768: { slidesPerView: 2 },
                1200: { slidesPerView: 3 },
            };
        }

        new Swiper(slider, {
            loop: slider.dataset.loop === 'true',
            speed: 600,
            spaceBetween: 15,

            autoplay: slider.dataset.autoplay === 'true'
                ? { delay: 4000, disableOnInteraction: false }
                : false,

            navigation: {
                nextEl: nextBtn,
                prevEl: prevBtn,
            },

            breakpoints: breakpoints,
            watchOverflow: true
        });
    });
});
