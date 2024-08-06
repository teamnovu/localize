export function deslug(string) {
    return string
        // Replace underscores with spaces
        .replace(/_/g, ' ')
        // Capitalize first letter of each word
        .replace(/\b\w/g, l => l.toUpperCase())
        // Add space in camelCase
        .replace(/([a-z])([A-Z])/g, '$1 $2');
}