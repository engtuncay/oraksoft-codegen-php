const fs = require("fs");
const archiver = require("archiver");
const ftp = require("basic-ftp");

function createZip(zipFileName, sourceDir) {
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
    const client = new ftp.Client();
    client.ftp.verbose = true;

    try {
        await client.access({
            host: "ftp.siteniz.com",
            user: "ftp_kullanici",
            password: "ftp_sifre",
            secure: false,
        });

        console.log("FTP bağlantısı başarılı. ZIP yükleniyor...");
        await client.uploadFrom(zipFileName, "public_html/proje.zip");
        console.log("ZIP dosyası başarıyla yüklendi!");

    } catch (err) {
        console.error(err);
    }

    client.close();
}

const zipFileName = "deploy.zip";
const sourceDir = "./app";

createZip(zipFileName, sourceDir)
    .then(uploadZip)
    .catch(console.error);