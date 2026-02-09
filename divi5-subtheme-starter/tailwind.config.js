/** @type {import('tailwindcss').Config} */
module.exports = {
  presets: [require('./tailwind.preset.js')],
  content: [
    './*.php',
    './**/*.php',
    './assets/js/**/*.js',
    './safelist.txt'
  ],
  prefix: 'tw-',
  corePlugins: {
    preflight: false
  },
  theme: {},
  plugins: []
};
