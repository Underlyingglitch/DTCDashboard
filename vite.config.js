import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/scss/livescores.scss',
                'resources/js/app.js',
                'resources/js/import.js',
                'resources/js/jurytafel.js'
            ],
            refresh: true,
        }),
    ],
});
