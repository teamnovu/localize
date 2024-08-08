export function inputType(value) {
    if (value === null) return true;
    if (typeof value === 'string') return true;
    if (typeof value === 'number') return true;
    return false;
}

export function deslug(string = '') {
    if (typeof string !== 'string') {
        return string;
    }

    return string
        // Replace underscores with spaces
        .replace(/_/g, ' ')
        // Capitalize first letter of each word
        .replace(/\b\w/g, l => l.toUpperCase())
        // Add space in camelCase
        .replace(/([a-z])([A-Z])/g, '$1 $2');
}

export function walkObject(object, path, name) {
    let sub = object
    for (let step of path) {
        sub = sub[step]
        if (!sub) return undefined
    }
    return sub[name]
}