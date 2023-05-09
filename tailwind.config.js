const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            width: {
                '96': '24rem'
            },
            spinner: (theme) => ({
                default: {
                  position: 'absolute',
                  color: '#dae1e7', // color you want to make the spinner
                  size: '1em', // size of the spinner (used for both width and height)
                  border: '2px', // border-width of the spinner (shouldn't be bigger than half the spinner's size)
                  speed: '500ms', // the speed at which the spinner should rotate
                },
              }),
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('tailwindcss-spinner')({ className: 'spinner', themeKey: 'spinner' }),
    ],
};
