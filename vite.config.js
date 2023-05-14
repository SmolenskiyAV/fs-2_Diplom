import { defineConfig, searchForWorkspaceRoot } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';

export default defineConfig({
    esbuild: {
        loader: 'jsx',
    },
    //optimizeDeps: {
      //  esbuildOptions: {
        //    loader: {
          //      '.js': 'jsx',
            //},
        //},
    //},

    plugins: [
        //laravel({
          //  input: [
            //    'resources/css/app.css',
              //  'resources/js/app.js',
                //'resources/css/admin/normalize.css',
                //'resources/css/admin/styles.scss',
                //'resources/js/admin/accordeon.js',
            //],
            //refresh: [
              //  ...refreshPaths,
                //'app/Http/Livewire/**',
            //],
        //}),
        laravel([
            //'resources/css/app.css',
            //'resources/css/admin/normalize.css',
            //'resources/css/admin/styles.scss',
            //'resources/css/client/normalize.css',
            //'resources/css/client/styles.scss',
            //'resources/js/admin/accordeon.js',
            //'resources/js/admin/demo.js',
            //'resources/js/app.js',
        ])
    ],
});
