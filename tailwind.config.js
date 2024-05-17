const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            screens: {
                'max-2xl': { 'max': '1535px' },
                // => @media (max-width: 1535px) { ... }

                'max-xl': { 'max': '1279px' },
                // => @media (max-width: 1279px) { ... }

                'max-lg': { 'max': '1023px' },
                // => @media (max-width: 1023px) { ... }

                'max-md': { 'max': '767px' },
                // => @media (max-width: 767px) { ... }

                'max-sm': { 'max': '639px' },
                // => @media (max-width: 639px) { ... }
            },
            colors: {
                "primary": '#0145a3',
                "secundary": '#fe0000',
                "proconph": '#0145a3',
            },
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
