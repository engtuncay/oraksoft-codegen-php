import { defineConfig } from 'vite';

export default defineConfig({
    base: '', // Kök dizin yerine relative path kullan
    build: {
        rollupOptions: {
            input: 'src/main.ts',  // Giriş noktası olarak TypeScript dosyasını belirt
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