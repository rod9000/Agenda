const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    darkMode: 'class',
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
            colors: {
                brand: {
                    50: '#F4F7EE', 100: '#E8EDDB', 200: '#D1DAB7',
                    300: '#BAC893', 400: '#AFB796', 500: '#959E7D',
                    600: '#7B8564', 700: '#616C4B', 800: '#475332',
                    900: '#2D3A19',
                },
            },
            screens: {
                'hidpi': { 'raw': '(min-resolution: 600dpi), (-webkit-min-device-pixel-ratio: 4)' },
            },
            animation: {
                'fade-up': 'fadeUp 0.4s ease-out',
                'fade-in': 'fadeIn 0.3s ease-out',
                'scale-in': 'scaleIn 0.3s ease-out',
                'bounce-in': 'bounceIn 0.5s ease-out',
            },
            keyframes: {
                fadeUp: { '0%': { opacity: '0', transform: 'translateY(20px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                scaleIn: { '0%': { opacity: '0', transform: 'scale(0.9)' }, '100%': { opacity: '1', transform: 'scale(1)' } },
                bounceIn: { '0%': { opacity: '0', transform: 'scale(0.3)' }, '50%': { transform: 'scale(1.05)' }, '70%': { transform: 'scale(0.9)' }, '100%': { opacity: '1', transform: 'scale(1)' } },
            },
        },
    },
    plugins: [require('@tailwindcss/forms')],
};
