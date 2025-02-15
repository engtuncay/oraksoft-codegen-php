import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

// __dirname alternatifi (ESM kullanıyorsan)
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const projectRoot = path.resolve(__dirname, './');
const srcDir = path.join(projectRoot, 'node_modules');

// package.json dosyasını oku
const packageJsonPath = path.join(projectRoot, 'package.json');
const packageJson = JSON.parse(fs.readFileSync(packageJsonPath, 'utf-8'));

if (!packageJson.copyDepsModulesToCopy || !Array.isArray(packageJson.copyDepsModulesToCopy)) {
    console.error("Error: 'modulesToCopy' alanı package.json içinde bir dizi olarak tanımlanmalıdır.");
    process.exit(1);
}

if (typeof packageJson.copyDepsLibFolder !== "string") {
    console.error("Error: 'copyDepsLibFolder' alanı package.json içinde bir string olarak tanımlanmalıdır.");
    process.exit(1);
}

// copyDepsLibFolder dizinini belirle
const destDir = path.join(projectRoot, packageJson.copyDepsLibFolder);

if(packageJson.copyDepsLibFolderEmpty) {
    if (fs.existsSync(destDir)) {
        fs.rmSync(destDir, { recursive: true, force: true });
        console.log(`Deleted ${destDir} and its contents.`);
    }
}

// Klasörü oluştur
if (!fs.existsSync(destDir)) {
    fs.mkdirSync(destDir, { recursive: true });
}

// 📌 Kopyalanacak dosyaları belirle
const modulesToCopy = packageJson.copyDepsModulesToCopy;
const filesToKeep = modulesToCopy.map(({ file }) => path.basename(file));

// 📌 1. Fazlalık dosyaları temizle (Eğer copyDepsLibFolder içinde olup modulesToCopy listesinde yoksa sil)
fs.readdirSync(destDir).forEach(file => {
    if (!filesToKeep.includes(file)) {
        const filePath = path.join(destDir, file);
        fs.unlinkSync(filePath);
        console.log(`🗑️ Deleted unnecessary file: ${filePath}`);
    }
});

// 📌 2. Eksik dosyaları kopyala
modulesToCopy.forEach(({ name, file }) => {
    const modPath = path.join(srcDir, name, file); // Kaynak dosya
    const destPath = path.join(destDir, path.basename(file)); // Hedef dosya

    if (!fs.existsSync(destPath)) {  // Eğer dosya yoksa kopyala
        if (fs.existsSync(modPath)) {
            fs.cpSync(modPath, destPath, { recursive: false });
            console.log(`✅ Copied: ${modPath} → ${destPath}`);
        } else {
            console.error(`❌ Error: ${modPath} not found!`);
        }
    } else {
        console.log(`⚠️ Skipped (already exists): ${destPath}`);
    }
});

console.log("🎉 Dependency sync process completed!");