module.exports = {
    prefix: 'novu-',
    important: true,
    content: [
        './resources/views/**/*.{html,php}',
        './resources/js/**/*.{vue,js}',
    ],
    corePlugins: {
        preflight: false,
    },
}