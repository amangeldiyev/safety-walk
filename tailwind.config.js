import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
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
                'primary': '#212D40',
                'secondary': '#F7C59F',
                'success': '#198754',
                'info': '#0dcaf0',
                'warning': '#ffc107',
                'danger': '#dc3545',
                'light': '#f8f9fa',
                'dark': '#212529',
                'text': '#082231'
            },
            backgroundColor: {
                'page': '#F3F4F6',
                'card': '#FFFFFF',
                'primary': '#212D40',
                'secondary': '#BCC4C7',
            },
            text: {
                'primary': '#082231',
                'secondary': '#F7C59F',
                'success': '#198754',
                'info': '#0dcaf0',
                'warning': '#ffc107',
                'danger': '#dc3545',
                'light': '#f8f9fa',
                'dark': '#212529',
                'text': '#082231'
            },
        },
    },
    darkMode: false,
    plugins: [forms],
};
