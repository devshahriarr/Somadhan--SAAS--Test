import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
        './resources/js/**/*.js',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    light: '#60A5FA', // Soft blue for light mode hover/accents
                    DEFAULT: '#3B82F6', // Vibrant blue for primary elements
                    dark: '#1E40AF', // Deep blue for dark mode
                },
                secondary: {
                    light: '#34D399', // Bright teal for light mode accents
                    DEFAULT: '#10B981', // Rich teal for secondary elements
                    dark: '#047857', // Darker teal for dark mode
                },
                background: {
                    light: '#F9FAFB', // Clean, near-white background for light mode
                    DEFAULT: '#F3F4F6', // Subtle gray for default light background
                    dark: '#111827', // Deep slate for dark mode background
                },
                surface: {
                    light: '#FFFFFF', // Pure white for cards and surfaces in light mode
                    DEFAULT: '#E5E7EB', // Light gray for subtle surfaces
                    dark: '#1F2937', // Dark slate for dark mode surfaces
                },
                text: {
                    light: '#1F2937', // Dark gray for readable text in light mode
                    DEFAULT: '#111827', // Near-black for default text
                    dark: '#D1D5DB', // Light gray for dark mode text
                },
                accent: {
                    light: '#FBBF24', // Warm amber for highlights in light mode
                    DEFAULT: '#F59E0B', // Rich amber for accents
                    dark: '#D97706', // Deeper amber for dark mode
                },
                muted: {
                    light: '#D1D5DB', // Light gray for muted elements in light mode
                    DEFAULT: '#6B7280', // Mid-gray for subdued text/icons
                    dark: '#4B5563', // Darker gray for dark mode muted elements
                },
                danger: {
                    light: '#FCA5A5', // Soft red for light mode errors
                    DEFAULT: '#EF4444', // Bold red for danger states
                    dark: '#B91C1C', // Deep red for dark mode
                },
                success: {
                    light: '#6EE7B7', // Light green for success states
                    DEFAULT: '#10B981', // Vibrant green for success
                    dark: '#047857', // Dark green for dark mode
                },
            },
            fontSize: {
                xs: ['0.65rem', { lineHeight: '1rem' }], // 10px
                sm: ['0.75rem', { lineHeight: '1.25rem' }], // 12px
                base: ['0.875rem', { lineHeight: '1.5rem' }], // 14px
                lg: ['1rem', { lineHeight: '1.75rem' }], // 16px
                xl: ['1.125rem', { lineHeight: '1.75rem' }], // 18px
                '2xl': ['1.25rem', { lineHeight: '2rem' }], // 20px
                '3xl': ['1.5rem', { lineHeight: '2.25rem' }], // 24px
                '4xl': ['1.875rem', { lineHeight: '2.5rem' }], // 30px
                '5xl': ['2.25rem', { lineHeight: '2.75rem' }], // 36px
            },
        },
    },

    plugins: [forms],
};
