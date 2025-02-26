<?php
require 'fiAppImports.php';

use Engtuncay\Phputils8\Excel\FiExcel;
use Engtuncay\Phputils8\Log\FiLog;
use Engtuncay\Phputils8\Meta\Fdr;
use Engtuncay\Phputils8\Meta\FiCol;
use Engtuncay\Phputils8\Meta\FiColList;
use codegen\modals\CgmPhp;

FiLog::initLogger('filog');

$fdrExcel = new Fdr();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excelFile'])) {

  $uploadedFile = $_FILES['excelFile'];
  // Dosya geçici olarak kaydediliyor
  $_SESSION["uploaded_file"] = $_FILES["excelFile"]["name"];

  if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
    //die('Dosya yüklenirken hata oluştu.');
    $fdrExcel->setMessage("Dosya yüklenirken Hata oluştu.");
    goto endExcelOkuma;
  }

  $fileExtension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
  $allowedExtensions = ['xlsx', 'xls', 'csv'];

  if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
    $mess = 'Geçersiz dosya formatı. Sadece .xlsx veya .xls dosyaları yükleyebilirsiniz.';
    //die($mess);
    $fdrExcel->setMessage($mess);
    goto endExcelOkuma;
  }

  $inputFileName = $uploadedFile['tmp_name'];

  $fiExcel = new FiExcel();

  $fiCols = new FiColList();
  $fiCol = new FiCol();
  $fiCol->ofcTxFieldName = "A1";
  $fiCol->ofcTxHeader = "A1";

  $fiCols->add($fiCol);

  $fdrExcel = $fiExcel::readExcelFile($inputFileName, $fiCols);

  $fkbExcel = $fdrExcel->getFkbListInit();

  //print_r($fdr);
  //echo var_export($fdr->getFkbList(), true);
  //echo PHP_EOL;
  //print_r($fdr->getFkbList());

  // Formdan gelen POST verilerini al
  $formData = $_POST;

  // stdClass nesnesine dönüştür
  $formObject = (object)$formData;

  if ($formObject->selCsharp == "1") {
    $message .= CgmPhp::actGenFiColListByFkbList($fkbExcel);
    $message .= "csharp1 seçildi";
    $message .= serialize($fdrExcel->getFkbListInit()->getAsMultiArray());
  }

  // Nesne olarak verileri görüntüle
  //  echo "Ad: " . $formObject->name . "\n";
  //  echo("<br/>");
  //  echo "Email: " . $formObject->email . "\n";
  //  echo("<br/>");
  //print_r($formObject);
} else {
  $fdrExcel = new Fdr(false, "No Excel Upload File");
}
endExcelOkuma:


?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <?php require 'fiHead.php'; ?>
    <link rel="stylesheet" href="codeblock.css">
</head>
<body class="fibody">
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">-->
<div class="container mt-3">
    <!--code blok -->
    <div class="position-relative">
    <pre class="p-3 rounded"><code id="code-snippet">
const hello = "Hello, Bootstrap!";
console.log(hello);
<?= $message; ?>
    </code></pre>
        <button class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-2 copy-btn" onclick="copyCode()">
            Copy
        </button>
    </div>
</div>

<script>

    function copyCode() {
        const code = document.getElementById("code-snippet").innerText;
        navigator.clipboard.writeText(code).then(() => {
            //alert("Copied!");
        });
    }

    // @flow
    // let elementById1 = document.getElementById("#txaOutput");

    // Butonun tıklanmasıyla fonksiyonu çalıştır
    //document.getElementById("copy-btn").addEventListener("click", copyCode);

    <?php
    //    var fkb =<?php echo json_encode($fdrExcel->getFkbListInit()->getAsMultiArray());
    //    var fdrResult =<?php echo json_encode($fdrExcel->getBoResult())
    ?>
    console.log(fkb);
    console.log(fdrResult);

    //document.getElementById("#txaOutput").textContent = fkb.toString();
    // for (const fkbElement of fkb) {
    //     console.log(fkbElement);
    //     document.getElementById("#txaOutput").textContent = fkbElement;
    // }
    //console.log(fkb);

    // function AppViewModel() {
    //     var self = this;
    //
    //     // Renk seçenekleri
    //     self.colors = [-
    //         {name: "Kırmızı", code: "#FF0000"},
    //         {name: "Yeşil", code: "#00FF00"},
    //         {name: "Mavi", code: "#0000FF"},
    //         {name: "Sarı", code: "#FFFF00"}
    //     ];
    //
    //     // Seçilen renk
    //     self.selectedColor = ko.observable(self.colors[0].code); // Varsayılan olarak ilk renk seçili
    //
    //     // Seçilen renk değeri değiştikçe burada bir şeyler yapılabilir.
    // }

    // ko.applyBindings(new AppViewModel());
</script>
<!--page scripts-->
<script src="libs/bootstrap.min.js"></script>
<!--<script type="module" src="main.mjs"></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>-->
<!--<script type="module" src="./assets/main.js"></script>-->
</body>
</html>