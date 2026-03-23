import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        'bg-blue-600',
        'hover:bg-blue-700',
        'bg-yellow-500',
        'hover:bg-yellow-600',
        'bg-green-600',
        'hover:bg-green-700',
        'bg-red-600',
        'hover:bg-red-700',
        'bg-red-800',
        'hover:bg-red-900',
        'bg-gray-500',
        'hover:bg-gray-600',
        'text-white',
        'rounded',
        'px-2',
        'py-1',
        'text-xs',
        'inline-block',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};