export default function throttle(callback, wait = 100) {

    let waiting = false;

    return (...args) => {

        if (waiting) {

            return;

        }

        callback(...args);

        waiting = true;

        setTimeout(() => {

            waiting = false;

        }, wait);

    };

}