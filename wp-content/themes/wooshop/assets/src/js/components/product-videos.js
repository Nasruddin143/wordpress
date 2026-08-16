/**
 * WooShop Product Videos
 *
 * Provides lightweight lazy initialization for
 * product video players.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {

    const videos = document.querySelectorAll('.ws-product-videos video');

    if (!videos.length) {
        return;
    }

    const observer = new IntersectionObserver((entries) => {

        entries.forEach((entry) => {

            if (!entry.isIntersecting) {
                return;
            }

            const video = entry.target;

            if (video.preload === 'none') {
                video.preload = 'metadata';
            }

            observer.unobserve(video);
        });
    }, {
        rootMargin: '200px 0px',
    });

    videos.forEach((video) => {
        observer.observe(video);
    });
});