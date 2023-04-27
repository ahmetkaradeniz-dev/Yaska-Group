const colors = require('tailwindcss/colors');

export default {
  content: [
    './index.html',
    './src/**/*.{vue,js,ts,jsx,tsx}',
    './node_modules/vue-tailwind-datepicker/**/*.js',
  ],
  theme: {
    extend: {
      screens:{
        'sm': '576px',
        // => @media (min-width: 640px) { ... }
        'md': '768px',
        // => @media (min-width: 768px) { ... }
        'lg': '992px',
        // => @media (min-width: 1024px) { ... }
        'xl': '1140px',
      },
      container: {
        center: true,
        padding: '8px'
      },
      colors:{
        white: '#fff',
        text: '#848895',
        black: '#000000',
        lightText:'#939597',
        lightGray: '#3AAAA6',
        bgGray: '#FAFAFA',
        gamboge: '#F2AC0D',
        darkBlue:'#212A4D',
        "vtd-primary": colors.sky,
      },
    },
  },
  plugins: [
    require('@tailwindcss/line-clamp')
  ],
}

