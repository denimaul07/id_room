import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import path from "path";

export default defineConfig({
    base: "/",
    plugins: [
        vue()
    ],
    resolve: {
        alias: {
        "@": path.resolve(__dirname, "./src"),
        },
    },
    build: {
        rollupOptions: {
        output: {
            // Hapus komentar yang berisi informasi library
            banner: '',
            // Ubah nama file output agar tidak mengandung hash yang predictable
            entryFileNames: 'assets/[name].[hash].js',
            chunkFileNames: 'assets/[name].[hash].js',
            assetFileNames: 'assets/[name].[hash].[ext]',
            // Minify dan hapus komentar
            compact: true
        }
        },
        minify: 'terser',
        terserOptions: {
        compress: {
            drop_console: true,
            drop_debugger: true
        },
        format: {
            comments: false // Hapus semua komentar
        }
        }
    }
});
