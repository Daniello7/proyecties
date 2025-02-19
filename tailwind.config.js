import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: ['./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php', './storage/framework/views/*.php', './resources/views/**/*.blade.php',],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            }, animation: {
                "pulse-fast": "pulse 0.3s ease-in-out infinite",
            },
        },
    },

    plugins: [forms, function ({addComponents}) {
        addComponents({
            '.active-link': {
                position: 'relative',
                paddingLeft: '0.5rem',
                borderRadius: '0 0.5rem 0.5rem 0',
                boxShadow: '4px 4px 10px gray',
                '@apply text-white': {},
                '@apply dark:text-white': {},
                '@apply dark:shadow-[10px_8px_10px_-4px_black]': {},
                '@apply bg-gradient-to-r from-blue-600 to-emerald-600': {},
                '@apply dark:bg-gradient-to-r dark:from-violet-600 dark:to-pink-600': {},

                '&:before': {
                    content: '""',
                    position: 'absolute',
                    top: 0,
                    left: 0,
                    width: '40px',
                    height: '40px',
                    borderRadius: '50%',
                    transform: 'translate(-10px,-30px) rotate(45deg)',
                    borderWidth: '10px',
                    '@apply border-transparent border-b-blue-600 dark:border-b-violet-600': {},
                },

                '&:after': {
                    content: '""',
                    position: 'absolute',
                    bottom: 0,
                    left: 0,
                    width: '40px',
                    height: '40px',
                    borderRadius: '50%',
                    transform: 'translate(-10px,30px) rotate(-45deg)',
                    borderWidth: '10px',
                    '@apply border-transparent border-t-blue-600 dark:border-t-violet-600': {},
                }
            }, '.not-active-link': {
                marginLeft: '0.5rem', borderRadius: '0.5rem', color: '#2563eb', '@apply dark:text-pink-500': {},

                '&:hover': {
                    color: '#ffffff',
                    boxShadow: '4px 4px 10px gray',
                    '@apply dark:shadow-[4px_4px_10px_black]': {},
                    '@apply bg-gradient-to-r from-emerald-600 to-blue-600': {},
                    '@apply dark:bg-gradient-to-r dark:from-pink-600 dark:to-violet-600': {},
                },
            }, '.custom-gradient-text': {
                width: 'fit-content',
                color: 'transparent',
                backgroundSize: '100% 100%',
                backgroundClip: 'text',
                backgroundImage: 'linear-gradient(to bottom right, #2563eb, #10b981)',
                '@apply dark:bg-gradient-to-br dark:from-violet-600 dark:to-pink-600 dark:to-60%': {},
            }, '.conic-gradient-light': {
                backgroundImage: 'conic-gradient(#059669,#2563eb 60deg,#059669 150deg,#2563eb 240deg,#059669 330deg)',
            }, '.conic-gradient-dark': {
                backgroundImage: 'conic-gradient(#db2777,#7c3aed 60deg,#db2777 150deg,#7c3aed 240deg,#db2777 330deg)',
            },
            '.gradient-border': {
                position: 'relative',
            },
            '.gradient-border::after, .gradient-border::before': {
                content: "''",
                position: 'absolute',
                inset: '-2px',
                zIndex: '-10',
                background: 'conic-gradient(#059669, #2563eb 60deg, #059669 150deg, #2563eb 240deg, #059669 330deg)',
                '@apply dark:bg-[conic-gradient(#db2777,#7c3aed_60deg,#db2777_150deg,#7c3aed_240deg,#db2777_330deg)]': {},
                borderRadius: '24px',
            },
            '.gradient-border:hover::before': {
                filter: 'blur(10px)',
                opacity: '0.8',
            }
        });
    }],
};
