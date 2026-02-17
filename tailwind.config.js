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
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                'soft': '0 8px 30px rgb(0,0,0,0.04)',
                'soft-lg': '0 12px 40px rgb(0,0,0,0.06)',
                'soft-xl': '0 20px 50px rgb(0,0,0,0.08)',
            },
            borderRadius: {
                '4xl': '2rem',
            },
            keyframes: {
                'pop-in': {
                    '0%': { opacity: '0', transform: 'translateY(16px) scale(0.96)' },
                    '100%': { opacity: '1', transform: 'translateY(0) scale(1)' },
                },
            },
            animation: {
                'pop-in': 'pop-in 0.5s cubic-bezier(0.34,1.56,0.64,1) forwards',
            },
        },
    },

    plugins: [forms],
};
