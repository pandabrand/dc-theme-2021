const defaultTheme = require('tailwindcss/defaultTheme')
module.exports = {
  purge: [],
  darkMode: false, // or 'media' or 'class'
  theme: {
    fontFamily: {
      hand: ['Dchristiansen-Regular', 'cursive'],
      display: ['Syne', 'sans-serif'],
      body: ['Syne', 'sans-serif'],
    },
    maxWidth: {
      '1/4': '25%',
      '1/2': '50%',
      '3/5': '60%',
      '3/4': '75%',
    },
    container: {
      center: true,
      padding: '4rem',
    },
    extend: {
      colors: {
        yellow: {
          DEFAULT: '#e3dad8',
        },
      },
      fontFamily: {
        sans: [
          'Syne',
          'Open Sans',
          ...defaultTheme.fontFamily.sans,
        ]
      }
    },
  },
  variants: {
    extend: {
      padding: ['hover'],
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
