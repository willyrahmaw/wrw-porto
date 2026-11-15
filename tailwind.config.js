/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                dark: {
                    DEFAULT: '#0A0A0A',
                    surface: '#121212',
                    card: '#161616',
                    border: '#242424',
                },
                light: {
                    DEFAULT: '#FAF9F6', // Pure Warm Gallery Paper (Clean, crisp, no dingy yellow/grey)
                    surface: '#F2F1EA', // Velvety subtle section background
                    card: '#FFFFFF',    // Pure crisp white card with clean definition
                    border: '#E2E1D8',  // Refined architectural 1px hairline border
                },
                offwhite: '#EDEDE8',
                muted: '#6B6A64', // Deep readable slate in light mode (5.3:1 contrast on paper)
                accent: {
                    DEFAULT: 'var(--color-accent, #C6F432)',
                    hover: 'var(--color-accent-hover, #D4FA58)',
                    orange: '#FF5B1F',
                    lime: '#C6F432',
                },
            },
            fontFamily: {
                display: ['"Instrument Serif"', 'Georgia', 'serif'],
                sans: ['"DM Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                mono: ['"JetBrains Mono"', 'ui-monospace', 'monospace'],
            },
            fontSize: {
                'hero': ['clamp(2.5rem, 8.5vw, 11.5rem)', { lineHeight: '0.94', letterSpacing: '-0.03em' }],
                'section': ['clamp(2rem, 5.2vw, 6.5rem)', { lineHeight: '0.98', letterSpacing: '-0.025em' }],
            },
            transitionTimingFunction: {
                'awwwards': 'cubic-bezier(0.76, 0, 0.24, 1)',
                'out-expo': 'cubic-bezier(0.16, 1, 0.3, 1)',
            },
            spacing: {
                '18': '4.5rem',
                '22': '5.5rem',
                '30': '7.5rem',
            },
            keyframes: {
                marquee: {
                    '0%': { transform: 'translateX(0%)' },
                    '100%': { transform: 'translateX(-50%)' },
                },
                revealUp: {
                    '0%': { transform: 'translateY(110%)' },
                    '100%': { transform: 'translateY(0%)' },
                },
            },
            animation: {
                'marquee': 'marquee 28s linear infinite',
                'reveal-up': 'revealUp 1s cubic-bezier(0.76, 0, 0.24, 1) forwards',
            },
        },
    },
    plugins: [],
};
