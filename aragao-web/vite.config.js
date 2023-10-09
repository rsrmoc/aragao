import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.css',
                'resources/js/app.js',
                'resources/js/views/tables-users.js',
                'resources/js/pages/dashboard/obras.js',
                'resources/js/pages/dashboard/reunioes.js',
                'resources/js/views/obras/etapas-tabs-usuarios.js',
                'resources/js/views/obras/etapas-tabs-funcionarios.js'
            ],
            refresh: true,
        }),
    ],
});
