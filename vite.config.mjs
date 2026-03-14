import { defineConfig } from 'vite';
import { resolve } from 'node:path';

export default defineConfig({
  build: {
    outDir: 'assets/build',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        app: resolve(__dirname, 'src/js/app.js'),
        'app-portfolio': resolve(__dirname, 'src/js/app-portfolio.js'),
        'page-transition': resolve(__dirname, 'src/js/common/page-transition.js'),
        home: resolve(__dirname, 'src/js/creation/production/home.js'),
        'home-text': resolve(__dirname, 'src/js/creation/production/home-text.js'),
        contact: resolve(__dirname, 'src/js/creation/component/contact.js'),
        admin: resolve(__dirname, 'src/js/admin.js'),
      },
      output: {
        entryFileNames: '[name].js',
        chunkFileNames: 'chunks/[name].js',
        assetFileNames: 'assets/[name][extname]',
      },
    },
  },
});
