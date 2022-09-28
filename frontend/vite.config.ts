import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import tsconfigPaths from "vite-tsconfig-paths";
import svgr from "vite-plugin-svgr";
import { VitePWA, VitePWAOptions } from "vite-plugin-pwa";
// https://vitejs.dev/config/

const pwaOptions: Partial<VitePWAOptions> = {
  mode: 'development',
  base: '/',
  includeAssets:['favicon.svg'],
  manifest:{
    name: 'Eötvös DÖ',
    short_name: 'Eötvös DÖ',
    theme_color: '#ffffff',
    icons: [
      {
        src: '/pwa-192x192.png',
        sizes: '192x192',
        type: 'image/png',
      },
      {
        src: '/pwa-512x512.png',
        sizes: '512x512',
        type: 'image/png',
      },
      {
        src: '/pwa-512x512.png',
        sizes: '512x512',
        type: 'image/png',
        purpose: 'any maskable',
      },
    ],
  },
  devOptions: {
    enabled: process.env.SW_DEV === 'true',
    type: 'module',
    navigateFallback: 'index.html',
  },
};

export const config = {
  build:{
    sourcemap: process.env.SOURCE_MAP === 'true',
  },
  plugins: [tsconfigPaths(), react(), svgr(), VitePWA(pwaOptions)],
};

export default defineConfig(config);
