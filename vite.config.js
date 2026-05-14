import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            // Di sinilah kita memberi tahu Vite untuk mengkompilasi file CSS dan JS ini, 
            // BUKAN mencari index.html
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true, // Untuk auto-reload saat development
        }),
        tailwindcss(), // Memuat plugin Tailwind v4
    ],
});