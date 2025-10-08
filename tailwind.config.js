import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                indigo: {
                    50: '#E5F4F3',
                    100: '#CCE9E7',
                    200: '#99D3CE',
                    300: '#66BCB6',
                    400: '#33A69D',
                    500: '#2F9188',
                    600: '#2B7A78',
                    700: '#205C59',
                    800: '#153D3B',
                    900: '#0A1F1D',
                    950: '#050F0F',
                    DEFAULT: '#2B7A78',
                },
            },
        },
    },

    plugins: [forms],
};
