import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './app/Filament/**/*.php',
        './vendor/filament/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                saffron: {
                    50: '#fff8ed',
                    100: '#ffefd4',
                    200: '#ffdba8',
                    300: '#ffc070',
                    400: '#ff9a37',
                    500: '#ff7d10',
                    600: '#f06106',
                    700: '#c74808',
                    800: '#9e390e',
                    900: '#7f310f',
                    950: '#451605',
                },
                maroon: {
                    50: '#fdf3f3',
                    100: '#fae4e4',
                    200: '#f4cece',
                    300: '#eba9a9',
                    400: '#df7979',
                    500: '#cf5353',
                    600: '#ba3b3b',
                    700: '#9c2d2d',
                    800: '#812828',
                    900: '#6d2525',
                    950: '#3b1010',
                },
                cream: '#fffaf2',
                gold: '#c9a227',
            },
            fontFamily: {
                sans: ['Inter', 'Noto Sans', ...defaultTheme.fontFamily.sans],
                serif: ['Cinzel', 'Tiro Devanagari Gujarati', ...defaultTheme.fontFamily.serif],
                gujarati: ['"Noto Sans Gujarati"', 'sans-serif'],
            },
            boxShadow: {
                temple: '0 10px 40px -12px rgba(199, 72, 8, 0.25)',
            },
            backgroundImage: {
                'temple-gradient': 'linear-gradient(135deg, #ff7d10 0%, #c74808 100%)',
            },
        },
    },

    plugins: [forms, typography],
};
