const colors = require("tailwindcss/colors");

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx}", "./mails/*.html"],
  theme: {
    extend: {
      colors: {
        white: "#cacaca",
        transparent: "transparent",
        current: "currentColor",
        black: colors.black,
        emerald: colors.emerald,
        indigo: colors.indigo,
        yellow: {
          DEFAULT: "#FFD369",
          50: "#FFFFFF",
          100: "#FFFFFF",
          200: "#FFF7E3",
          300: "#FFEBBB",
          400: "#FFDF92",
          500: "#FFD369",
          600: "#FFC331",
          700: "#F8AF00",
          800: "#C08700",
          900: "#886000",
        },
        gray: {
          DEFAULT: "#E5E5E5",
          50: "#A8AEB8",
          100: "#9CA3AF",
          200: "#868F9C",
          300: "#707A89",
          400: "#5E6673",
          500: "#4B525C",
          600: "#393E46",
          700: "#202327",
          800: "#22831",
          900: "#0B0D10",
        },
      },
    },
  },
  plugins: [require("@headlessui/tailwindcss"), require("@tailwindcss/forms")],
};
