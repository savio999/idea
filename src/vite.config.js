import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',      // Expose Vite outside the container
        port: 5174,           // Match this with your Docker exposed port
        strictPort: true,     // Fail if port 5174 is busy (prevents port shifting)
        hmr: {
            host: 'localhost', // Force browser client to connect to host machine localhost
        },
    },
});