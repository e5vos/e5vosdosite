import react from "@vitejs/plugin-react";
import laravel from "laravel-vite-plugin";
import { defineConfig } from "vite";
import { VitePWAOptions } from "vite-plugin-pwa";
import svgr from "vite-plugin-svgr";
import tsconfigPaths from "vite-tsconfig-paths";

// https://vitejs.dev/config/

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const pwaOptions: Partial<VitePWAOptions> = {
    mode: "development",
    base: "/",
    includeAssets: ["favicon.svg"],
    manifest: {
        name: "Eötvös DÖ",
        short_name: "Eötvös DÖ",
        theme_color: "#ffffff",
        icons: [
            {
                src: "/pwa-192x192.png",
                sizes: "192x192",
                type: "image/png",
            },
            {
                src: "/pwa-512x512.png",
                sizes: "512x512",
                type: "image/png",
            },
            {
                src: "/pwa-512x512.png",
                sizes: "512x512",
                type: "image/png",
                purpose: "any maskable",
            },
        ],
    },
    devOptions: {
        enabled: process.env.SW_DEV === "true",
        type: "module",
        navigateFallback: "index.html",
    },
};

export const config = {
    server: {
        port: 3000,
        crossOriginIsolated: false,
    },
    build: {
        sourcemap: process.env.SOURCE_MAP === "true",
    },
    plugins: [
        tsconfigPaths(),
        laravel(["resources/frontend/main.tsx"]),
        react(),
        svgr(),
    ],
    resolve: {
        alias: {
            "@": "/resources/frontend",
        },
    },
};

export default defineConfig(config);
