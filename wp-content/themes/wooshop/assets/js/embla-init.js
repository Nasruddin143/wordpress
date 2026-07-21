document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.embla').forEach((emblaNode) => {

        const viewport = emblaNode.querySelector('.embla__viewport');

        const embla = EmblaCarousel(viewport, {
            loop: true,
            align: 'start',
            slidesToScroll: 1
        });

        const prevBtn = emblaNode.querySelector('.embla__prev');
        const nextBtn = emblaNode.querySelector('.embla__next');

        if (prevBtn) {
            prevBtn.addEventListener('click', () => embla.scrollPrev());
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => embla.scrollNext());
        }

    });

});