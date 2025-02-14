import { defineConfig } from 'vite';
import php from 'vite-plugin-php';

export default defineConfig({
    base: '', // Kök dizin yerine relative path kullan
    plugins: [
        php()
    ],
    build: {
        rollupOptions: {
            input: ['index.php'], // Giriş noktası olarak TypeScript dosyasını belirt
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