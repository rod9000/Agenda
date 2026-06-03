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
        },
    },
    plugins: [require('@tailwindcss/forms')],
};
