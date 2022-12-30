module.exports = {
    content: [
        "./index.html",
        "./src/**/*.{vue,js,ts,jsx,tsx}",
    ],
    theme: {
      extend: {
          colors: {
              primary: '#137C8B',
              secondary: '#709CA7',
              tertiary: '#B8CBD0',
              quad: '#D9D9D9'
          },
          fontFamily: {
              primary: ['Poppins', 'ui-sans-serif', 'system-ui'],
              secondary: ['Roboto', 'ui-sans-serif', 'system-ui']
          },
          backgroundImage: {
          },
          spacing: {
              108: '27rem',
              120: '30rem'
          }
      },
  },
  plugins: [
      require('@tailwindcss/forms')
  ],
}
