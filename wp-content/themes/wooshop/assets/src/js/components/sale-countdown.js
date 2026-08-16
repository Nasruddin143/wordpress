/**
 * WooShop Sale Countdown
 *
 * Updates active WooCommerce sale countdown timers.
 *
 * @package WooShop
 */

document.addEventListener('DOMContentLoaded', () => {

    const countdowns = document.querySelectorAll('[data-ws-sale-countdown]');

    if (!countdowns.length) {
        return;
    }

    countdowns.forEach((countdown) => {

        const endTime = parseInt(countdown.dataset.endTime, 10);

        if (!endTime) {
            return;
        }

        const days = countdown.querySelector('[data-ws-countdown-days]');

        const hours = countdown.querySelector('[data-ws-countdown-hours]');

        const minutes = countdown.querySelector('[data-ws-countdown-minutes]');

        const seconds = countdown.querySelector('[data-ws-countdown-seconds]');

        const update = () => {

            const currentTime = Math.floor(Date.now() / 1000);

            let remaining = endTime - currentTime;

            if (remaining <= 0) {

                remaining = 0;

                countdown.classList.add('is-expired');

                clearInterval(timer);

                window.location.reload();

                return;
            }

            const daysValue = Math.floor(remaining / 86400);

            remaining %= 86400;

            const hoursValue = Math.floor(remaining / 3600);

            remaining %= 3600;

            const minutesValue = Math.floor(remaining / 60);

            const secondsValue = remaining % 60;

            if (days) {
                days.textContent = String(daysValue).padStart(2, '0');
            }

            if (hours) {
                hours.textContent = String(hoursValue).padStart(2, '0');
            }

            if (minutes) {
                minutes.textContent = String(minutesValue).padStart(2, '0');
            }

            if (seconds) {
                seconds.textContent = String(secondsValue).padStart(2, '0');
            }
        };

        update();

        const timer = setInterval(update, 1000);
    });
});