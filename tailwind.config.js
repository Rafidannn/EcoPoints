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
                sans: ['"Public Sans"', ...defaultTheme.fontFamily.sans],
                mono: ['"IBM Plex Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                // EcoPoints design tokens
                paper: '#F3F1EA',
                surface: '#EDEAE0',
                border: '#D6D2C6',
                ink: {
                    DEFAULT: '#1E211C',
                    muted: '#5A5E55',
                    faint: '#8E9189',
                },
                primary: {
                    DEFAULT: '#26473A',
                    hover: '#1E3A2F',
                    light: '#EBF0EC',
                },
                organik: {
                    DEFAULT: '#7C8A3E',
                    light: '#F0F2E6',
                },
                anorganik: {
                    DEFAULT: '#2E6E76',
                    light: '#E6F2F3',
                },
                b3: {
                    DEFAULT: '#A6472B',
                    light: '#F5EAE6',
                },
                poin: {
                    DEFAULT: '#D6B33D',
                    light: '#FBF5E3',
                },
            },
            borderColor: {
                DEFAULT: '#D6D2C6',
            },
        },
    },

    plugins: [forms],
};
