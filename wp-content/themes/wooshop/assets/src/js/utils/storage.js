export function set(key, value) {

    localStorage.setItem(
        key,

        JSON.stringify(value)
    );

}

export function get(key, fallback = null) {

    const item = localStorage.getItem(key);

    return item

        ? JSON.parse(item)

        : fallback;

}