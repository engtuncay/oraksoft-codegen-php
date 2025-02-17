<?php
require 'vendor/autoload.php';

use Engtuncay\Phputils8\meta\FiCol;
use Engtuncay\Phputils8\meta\FiColList;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Engtuncay\Phputils8\log\FiLog;

FiLog::initLogger('filog');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excelFile'])) {
  $uploadedFile = $_FILES['excelFile'];

  if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
    die('Dosya yüklenirken hata oluştu.');
  }

  $fileExtension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
  $allowedExtensions = ['xlsx', 'xls', 'csv'];

  if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
    die('Geçersiz dosya formatı. Sadece .xlsx, .xls veya .csv dosyaları yükleyebilirsiniz.');
  }

  $inputFileName = $uploadedFile['tmp_name'];

  $fiExcel = new \Engtuncay\Phputils8\excel\FiExcel();

  $fiCols = new FiColList();
  $fiCol = new FiCol();
  $fiCol->ofcTxFieldName = "A1";
  $fiCol->ofcTxHeader = "A1";

  $fiCols->add($fiCol);

  $fdr = $fiExcel::readExcelFile($inputFileName, $fiCols);

  //print_r($fdr);
  //echo var_export($fdr->getFkbList(), true);
  //echo PHP_EOL;
  //print_r($fdr->getFkbList());

  // Formdan gelen POST verilerini al
  $formData = $_POST;

  // stdClass nesnesine dönüştür
  $formObject = (object)$formData;

  // Nesne olarak verileri görüntüle
//  echo "Ad: " . $formObject->name . "\n";
//  echo("<br/>");
//  echo "Email: " . $formObject->email . "\n";
//  echo("<br/>");
  //print_r($formObject);
}
?>
    <!doctype html>
    <html lang="tr">
    <head>
      <?php require 'fiHead.php'; ?>
        <link rel="stylesheet" href="codeblock.css">
    </head>
    <body>
    <h1>Codegen</h1>

    <div class="position-relative">
    <pre class="p-3 rounded"><code id="code-snippet">
const hello = "Hello, Bootstrap!";
console.log(hello);
    </code></pre>
        <button class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-2 copy-btn" onclick="copyCode()">
            Copy
        </button>
    </div>

    <div>
      <?php print_r($fdr->getFkbList()->getAsMultiArray()) ?>
    </div>

    <script>

        function copyCode() {
            const code = document.getElementById("code-snippet").innerText;
            navigator.clipboard.writeText(code).then(() => {
                alert("Copied!");
            });
        }

        // Butonun tıklanmasıyla fonksiyonu çalıştır
        document.getElementById("copy-btn").addEventListener("click", copyCode);

        var fkb =<?php echo json_encode($fdr->getFkbList()->getAsMultiArray()); ?>;
        //document.getElementById("#txaOutput").textContent = fkb.toString();
        for (const fkbElement of fkb) {
            console.log(fkbElement);
            document.getElementById("#txaOutput").textContent = fkbElement;
        }


        console.log(fkb);

    </script>
    </body>
</html>
<?php
//    try {
//        $spreadsheet = IOFactory::load($inputFileName);
//        $sheet = $spreadsheet->getActiveSheet();
//
//        // En yüksek satır ve sütun numaralarını al
//        $highestRow = $sheet->getHighestRow(); // Toplam satır sayısı
//        $highestColumn = $sheet->getHighestColumn(); // En yüksek sütun harfi (örneğin, "D")
//        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // Sütun harfini indekse çevir (örneğin, "D" -> 4)
//
//        // Satırları dolaş
//        for ($row = 1; $row <= $highestRow; $row++) {
//            // Sütunları dolaş
//            $fkb = new \Engtuncay\Phputils\core\FiKeybean();
//            for ($col = 1; $col <= $highestColumnIndex; $col++) {
//                // Hücre değerini al
//                $cellValue = $sheet->getCellByColumnAndRow($col, $row)->getValue();
//                echo $cellValue . "\t";
//            }
//            echo PHP_EOL; // Satır sonu
//        }
//
////        foreach ($sheet->getRowIterator() as $row) {
////            $cellIterator = $row->getCellIterator();
////            $cellIterator->setIterateOnlyExistingCells(false);
////
////            foreach ($cellIterator as $cell) {
////                echo $cell->getValue() . "\t";
////            }
////            echo PHP_EOL;
////        }
//    } catch (Exception $e) {
//        echo 'Excel dosyası okunurken hata oluştu: ', $e->getMessage(), PHP_EOL;
//    }
//} else {
//    echo 'Lütfen bir dosya seçin.';

//}
