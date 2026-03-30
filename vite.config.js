import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [vue()],
    build: {
        outDir: 'resources/dist',
        emptyOutDir: false,
        lib: {
            entry: 'resources/js/cp.js',
            name: 'LocalizeAddon',
            formats: ['umd'],
            fileName: () => 'js/cp.js',
        },
        rollupOptions: {
            // Vue is provided by Statamic's CP runtime — exclude from bundle
            external: ['vue'],
            output: {
                globals: {
                    vue: 'Vue',
                },
            },
        },
    },
    css: {
        postcss: {
            plugins: [
                require('tailwindcss'),
                require('autoprefixer'),
            ],
        },
    },
})
