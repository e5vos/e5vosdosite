import path from 'path'
import { loadConfigFromFile, mergeConfig } from 'vite'
import svgr from 'vite-plugin-svgr'
import tsconfigPaths from 'vite-tsconfig-paths'

const config = {
    viteFinal: async (config, { configType }) => {
        const { config: userConfig } = (await loadConfigFromFile(
            configType,
            path.resolve(__dirname, '../vite.config.ts')
        ))!
        return mergeConfig(config, {
            ...userConfig,
            plugins: [tsconfigPaths({ root: '..' }), svgr()],
        })
    },

    stories: [
        '../resources/frontend/**/*.stories.mdx',
        '../resources/frontend/**/*.stories.@(js|jsx|ts|tsx)',
    ],

    addons: [
        '@storybook/addon-links',
        '@storybook/addon-essentials',
        '@storybook/addon-interactions',
        {
            name: '@storybook/addon-postcss',
            options: {
                cssLoaderOptions: {
                    // When you have splitted your css over multiple files
                    // and use @import('./other-styles.css')
                    importLoaders: 1,
                },
            },
        },
    ],

    framework: '@storybook/react-vite',

    core: {
        disableTelemetry: true,
    },

    features: {
        storyStoreV7: true,
    },

    docs: {
        autodocs: true,
    },
}

export default config
