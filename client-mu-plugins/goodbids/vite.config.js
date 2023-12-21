/** @type {import('vite').UserConfig} */
import { defineConfig, splitVendorChunkPlugin } from 'vite';
import svgr from 'vite-plugin-svgr';
import liveReload from 'vite-plugin-live-reload';
import { resolve } from 'path';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import tailwindConfig from './tailwind.config.js';
import resolveConfig from 'tailwindcss/resolveConfig';
import react from '@vitejs/plugin-react'

export default defineConfig(({ command }) => ({
  root: 'src',
  base: command === 'serve' ? '' : '/dist/',
  plugins: [
    react(),
    liveReload([
      resolve(__dirname, './assets/**/*'),
      resolve(__dirname, './blocks/**/*'),
      resolve(__dirname, './views/**/*'),
    ]),
    splitVendorChunkPlugin(),
    svgr(),
    viteStaticCopy({
      targets: [
        {
          src: resolve( __dirname, 'tailwind.config.js' ),
          dest: resolve( __dirname, 'dist/config' ),
          rename: 'tailwind.json',
          transform: (contents, filename) => {
            // See https://tailwindcss.com/docs/configuration#referencing-in-java-script
            const config = resolveConfig( tailwindConfig );
            return JSON.stringify( config, null, 2 );
          },
        },
      ],
    })
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
    // I don't love that this is hard coded
    origin: 'https://goodbids.vipdev.lndo.site:5173',
    // we need a strict port to match on PHP side
    strictPort: true,
    // Vite port is defined by https://github.com/torenware/ddev-viteserve
    port: parseInt(process.env.VITE_PRIMARY_PORT ?? '5173'),
    hmr: {
      overlay: false,
    }
  },
}));
