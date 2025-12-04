import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50:  '#FFF9F2',
                    100: '#FFEEDA',
                    200: '#FFD9B3',
                    300: '#FFC48C',
                    400: '#FFAD66',
                    500: '#FF9440', // color principal
                    600: '#F57A20',
                    700: '#DB6112',
                    800: '#B5480D',
                    900: '#6A2A06',
                },
            },
        },
    },

    plugins: [forms],
};
