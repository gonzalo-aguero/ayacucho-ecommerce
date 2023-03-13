/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        screens: {
            sm: '480px',
            md: '768px',
            lg: '1024px',
            xl: '1280px',
            "2xl": '1536px'
        },
        colors: {
            'red': 'red',
            'blue': '#1fb6ff',
            'purple': '#7e5bef',
            'pink': '#ff49db',
            'orange': '#ef8400',
            'orange-medium': '#f59b2c',
            'orange-light': '#ffb93a',
            'green': '#13ce66',
            'yellow': '#ffc82c',
            'gray-dark': '#273444',
            'gray': '#8492a6',
            'gray-light': '#fafafa',
            'gray-light2': '#d7d7d7',
            'gray-light-transparent': '#d7d7d74d',
            'white': '#ffffff',
            'black':'#0c0c0c',
        },
        fontFamily: {
            sans: ['Inter var','Graphik', 'sans-serif'],
            serif: ['Merriweather', 'serif'],
        },
        extend: {
            spacing: {
                '128': '32rem',
                '144': '36rem',
            },
            borderRadius: {
                '4xl': '2rem',
            },
            gridTemplateColumns: {
                "cart-table": '1fr 6fr 4fr 4fr 6fr'
            }
        },
        container: {
            center: true,
            padding: {
                DEFAULT: '1rem',
                sm: '2rem',
                lg: '4rem',
                xl: '5rem',
                '2xl': '6rem',
            },
        },
    },
    plugins: [],
}
