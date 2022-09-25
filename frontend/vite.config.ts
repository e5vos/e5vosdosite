import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import tsconfigPaths from "vite-tsconfig-paths";
import svgr from "vite-plugin-svgr";
// https://vitejs.dev/config/

export const config = {
  plugins: [tsconfigPaths(), react(), svgr()],
}

export default defineConfig(config);
