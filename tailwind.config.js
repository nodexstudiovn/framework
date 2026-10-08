/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './app/Views/**/*.php',
    './index.html',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
        mono: ['JetBrains Mono', 'monospace'],
      },
      colors: {
        obsidian: '#09090b',
        zinc: {
          850: '#1f1f23',
          900: '#18181b',
          950: '#09090b',
        }
      }
    },
  },
  plugins: [],
};
