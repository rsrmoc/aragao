import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/sass/dashboard.scss',
                'resources/sass/chat.scss',

                'resources/js/app.js',

                'resources/js/pages/dashboard/obras.js',
                'resources/js/pages/dashboard/etapas-obras.js',
                'resources/js/pages/dashboard/reunioes.js',
                'resources/js/pages/dashboard/chat.js',

                'resources/js/views/tables-users.js',
                'resources/js/views/tables-users-localization.js',
                'resources/js/views/obras/etapas-tabs-usuarios.js',
                'resources/js/views/obras/etapas-tabs-funcionarios.js',
                'resources/js/views/obras/etapas-tabs-relatorios.js',
                'resources/js/views/obras/etapas-tabs-evolucoes.js',
                'resources/js/views/obras/etapas-tabs-projetos.js',
                'resources/js/views/obras/etapas-tabs-aditivos.js',
            ],
            refresh: true,
        }),
    ],
});
