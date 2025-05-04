import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
// import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/burger.css',
                'resources/js/burger.js',
            ],
            refresh: true,
        }),
    ],
    // resolve: {
    //     alias: {
    //         '$': 'jquery',
    //         'jquery': resolve(__dirname, 'node_modules/jquery/dist/jquery.min.js'),
    //     },
    // }
});
