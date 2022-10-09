import type { ConfigFile } from '@rtk-query/codegen-openapi'
const config: ConfigFile = {
    schemaFile: "../doc/api.yaml",
    apiFile: './src/lib/api.ts',
    apiImport: 'emptySplitApi',
    outputFile: './src/store/genApi.ts',
    exportName: 'genApi',
    hooks: true,

}

export default config;