import fs from "fs";
import archiver from "archiver";
import { Client } from "basic-ftp";

async function createZip(zipFileName, sourceDir) {
    return new Promise((resolve, reject) => {
        const output = fs.createWriteStream(zipFileName);
        const archive = archiver("zip", { zlib: { level: 9 } });

        output.on("close", () => resolve(zipFileName));
        archive.on("error", (err) => reject(err));

        archive.pipe(output);
        archive.directory(sourceDir, false);
        archive.finalize();
    });
}

async function uploadZip(zipFileName) {
    const client = new Client();
    client.ftp.verbose = true;

    try {
        await client.access({
            host: "ftp.siteniz.com",
            user: "ftp_kullanici",
            password: "ftp_sifre",
            secure: false,
        });

        console.log("FTP bağlantısı başarılı. ZIP yükleniyor...");
        await client.uploadFrom(zipFileName, `public_html/${zipFileName}`);
        console.log("ZIP dosyası başarıyla yüklendi!");

    } catch (err) {
        console.error("FTP yükleme hatası:", err);
    } finally {
        client.close();
    }
}

const zipFileName = "deploy.zip";
const sourceDir = "./build";

try {
    const zipPath = await createZip(zipFileName, sourceDir);
    console.log(`ZIP oluşturuldu: ${zipPath}`);
    await uploadZip(zipPath);
} catch (err) {
    console.error("İşlem sırasında hata oluştu:", err);
}