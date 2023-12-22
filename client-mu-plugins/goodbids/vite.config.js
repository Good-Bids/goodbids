/** @type {import('vite').UserConfig} */
import { defineConfig, splitVendorChunkPlugin } from 'vite';
import svgr from 'vite-plugin-svgr';
import liveReload from 'vite-plugin-live-reload';
import { fileURLToPath } from 'url';
import { dirname, resolve } from 'path';
import react from '@vitejs/plugin-react';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

export default defineConfig(({ command }) => ({
  root: 'src',
  base: command === 'serve' ? '' : '/dist/',
  plugins: [
    react(),
    liveReload([
      resolve(__dirname, './src/assets/**/*'),
      resolve(__dirname, './blocks/**/*'),
      resolve(__dirname, './views/**/*'),
    ]),
    splitVendorChunkPlugin(),
    svgr(),
  ],
  build: {
    // output dir for production build
    outDir: '../dist',
    emptyOutDir: true,
    // emit manifest so PHP can find the hashed files
    manifest: true,
    // our entry
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'src/main.tsx'),
        admin: resolve(__dirname, 'src/admin.tsx'),
        editor: resolve(__dirname, 'src/editor.tsx'),
      }
    },
  },
  server: {
    host: '0.0.0.0',
    origin: 'https://goodbids.vipdev.lndo.site:5173',
    // we need a strict port to match on PHP side
    strictPort: true,
    port: 5173,
    hmr: {
      overlay: false,
    }
  },
}));
