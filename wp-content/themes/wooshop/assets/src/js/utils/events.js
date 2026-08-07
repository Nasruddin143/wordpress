export function on(event, selector, callback) {

    document.addEventListener(event, e => {

        const target = e.target.closest(selector);

        if (!target) {

            return;

        }

        callback(e, target);

    });

}