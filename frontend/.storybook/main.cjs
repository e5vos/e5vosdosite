const {
  loadConfigFromFile,
  mergeConfig
} = require("vite")
const { default: tsconfigPaths } = require('vite-tsconfig-paths')
const svgr = require("vite-plugin-svgr")

const path = require("path")


module.exports = {
  "viteFinal": async (config, {
    configType
  }) => {
    const {
      config: userConfig
    } = await loadConfigFromFile(path.resolve(__dirname, "../vite.config.ts"))
    return mergeConfig(config, {
      ...userConfig,
      plugins: [
        tsconfigPaths(),
        svgr()
      ]
    });
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