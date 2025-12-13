import { FlatCompat } from '@eslint/eslintrc'
import js from '@eslint/js'
import tsPlugin from '@typescript-eslint/eslint-plugin'
import tsParser from '@typescript-eslint/parser'
import unusedImports from 'eslint-plugin-unused-imports'
import { defineConfig, globalIgnores } from 'eslint/config'
import path from 'node:path'
import { fileURLToPath } from 'node:url'

const __filename = fileURLToPath(import.meta.url)
const __dirname = path.dirname(__filename)
const compat = new FlatCompat({
    baseDirectory: __dirname,
    recommendedConfig: js.configs.recommended,
    allConfig: js.configs.all,
})

export default defineConfig([
    globalIgnores([
        '**/node_modules/',
        '**/vendor/',
        '**/app/',
        '**/bootstrap/',
        '**/config/',
        '**/database/',
        '**/lang/',
        '**/public/',
        '**/routes/',
        '**/storage/',
        '**/tests/',
        'resources/views/',
        'resources/js/ziggy.d.ts',
        'resources/js/ziggy.js',
    ]),
    {
        extends: compat.extends(
            'eslint:recommended',
            'plugin:react/recommended',
            'plugin:@typescript-eslint/recommended',
            'plugin:prettier/recommended'
        ),

        plugins: {
            'unused-imports': unusedImports,
            '@typescript-eslint': tsPlugin,
        },

        languageOptions: {
            parser: tsParser,
            ecmaVersion: 'latest',
            sourceType: 'module',
        },

        settings: {
            react: {
                version: 'detect',
            },
        },

        rules: {
            'unused-imports/no-unused-imports': 'error',
            'react/react-in-jsx-scope': 'off',
        },
    },
])
