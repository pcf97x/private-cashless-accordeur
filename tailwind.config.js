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
            colors: {
                accordeur: {
                    50:  '#eef6f7',
                    100: '#d5e8eb',
                    200: '#a8d1d8',
                    300: '#7ab9c3',
                    400: '#4d9fac',
                    500: '#326F7C',
                    600: '#2b5f6a',
                    700: '#234e58',
                    800: '#1c3e46',
                    900: '#142e34',
                },
                rouge: {
                    50:  '#fef2f0',
                    100: '#fde1dc',
                    200: '#fbc3b9',
                    300: '#f49a8a',
                    400: '#ec7560',
                    500: '#E84029',
                    600: '#c73520',
                    700: '#a62b1a',
                    800: '#862214',
                    900: '#6d1b10',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                'card': '0 1px 3px 0 rgba(50, 111, 124, 0.06), 0 1px 2px -1px rgba(50, 111, 124, 0.06)',
                'card-hover': '0 10px 25px -5px rgba(50, 111, 124, 0.1), 0 8px 10px -6px rgba(50, 111, 124, 0.08)',
                'sidebar': '2px 0 8px -2px rgba(50, 111, 124, 0.08)',
            },
            borderRadius: {
                'xl': '0.875rem',
                '2xl': '1rem',
            },
        },
    },

    plugins: [forms],
};
