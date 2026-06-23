export default {
    plugins: {
        // Tailwind CSS v4's PostCSS plugin handles @import resolution, CSS
        // nesting and vendor prefixing on its own, so postcss-import,
        // tailwindcss/nesting and autoprefixer are no longer needed.
        '@tailwindcss/postcss': {},
    },
}
