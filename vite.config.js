import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',

                'resources/js/register.js',
                'resources/js/drivers.js',
                'resources/js/vehicles.js',
                'resources/js/trips.js',
            ],
            refresh: true,
        }),
    ],
    // ADICIONE ESTE BLOCO ABAIXO:
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'localhost',
        },
    },
});
