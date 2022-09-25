const react = require("@vitejs/plugin-react");
const tsconfigPaths = require("vite-tsconfig-paths");
const svgr = require("vite-plugin-svgr")

module.exports = {
  "viteFinal": async (config, {
    configType
  }) => {
    config.plugins = [...config.plugins,svgr()]
    return config;
  },
  "stories": [
    "../src/**/*.stories.mdx",
    "../src/**/*.stories.@(js|jsx|ts|tsx)"
  ],
  "addons": [
    "@storybook/addon-links",
    "@storybook/addon-essentials",
    "@storybook/addon-interactions",
    {
      name: '@storybook/addon-postcss',
      options: {
        cssLoaderOptions: {
          // When you have splitted your css over multiple files
          // and use @import('./other-styles.css')
          importLoaders: 1,
        },
      }
    }
  ],
  "framework": "@storybook/react",
  "core": {
    "builder": "@storybook/builder-vite"
  },
  "features": {
    "storyStoreV7": true
  }
}