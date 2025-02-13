import { defineConfig } from 'vite';

export default defineConfig({
    build: {
        rollupOptions: {
            input: 'index.html',  // Giriş noktası olarak TypeScript dosyasını belirt
            output: {
                entryFileNames: 'main.js',        // Hash olmadan dosya adı
                chunkFileNames: 'chunk-[name].js',  // Diğer parçalar için isimlendirme
                assetFileNames: '[name].[ext]'    // CSS veya diğer varlıklar için isimlendirme
            }
        },
        outDir: 'app/assets',
        emptyOutDir: true
    }
});