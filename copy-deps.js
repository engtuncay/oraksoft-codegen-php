import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

// __dirname alternatifi (ESM kullanıyorsan)
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const projectRoot = path.resolve(__dirname, './');
//const modulesToCopy = ['axios'];
const srcDir = path.join(projectRoot, 'node_modules');

// Proje kök dizinini belirle
//const projectRoot = path.resolve('.');

// package.json dosyasını oku
const packageJsonPath = path.join(projectRoot, 'package.json');
const packageJson = JSON.parse(fs.readFileSync(packageJsonPath, 'utf-8'));

if (!packageJson.modulesToCopy || !Array.isArray(packageJson.modulesToCopy)) {
    console.error("Error: 'modulesToCopy' alanı package.json içinde bir dizi olarak tanımlanmalıdır.");
    process.exit(1);
}

if (typeof packageJson.copyDepsLibFolder !== "string") {
    console.error("Error: 'copyDepsLibFolder' alanı package.json içinde bir string olarak tanımlanmalıdır.");
    process.exit(1);
}

// dist klasör adını package.json içindeki "copyDepsLibFolder" alanına göre belirle
const destDir = path.join(projectRoot, packageJson.copyDepsLibFolder);

//const destDir = path.join(projectRoot, 'app/libs');

//const destDir = path.resolve('dist/libs'); // Silinecek klasör

if (fs.existsSync(destDir)) {
    fs.rmSync(destDir, { recursive: true, force: true });
    console.log(`Deleted ${destDir} and its contents.`);
}

// Yeniden oluştur
//fs.mkdirSync(destDir, { recursive: true });

// Klasörü oluştur
if (!fs.existsSync(destDir)) {
    fs.mkdirSync(destDir, { recursive: true });
}

// const modulesToCopy = [
//     { name: 'axios', file: 'dist/axios.min.js' },
////     { name: 'lodash', file: 'lodash.min.js' }
// ];

const modulesToCopy = packageJson.modulesToCopy;

modulesToCopy.forEach(({ name, file }) => {
    const modPath = path.join(srcDir, name, file); // Doğru dosyayı seç
    const destPath = path.join(destDir, path.basename(file)); // Sadece gerekli dosyayı al

    if (fs.existsSync(modPath)) {
        fs.cpSync(modPath, destPath, { recursive: false });
        console.log(`Copied ${modPath} to ${destPath}`);
    } else {
        console.error(`Error: ${modPath} not found!`);
    }
});