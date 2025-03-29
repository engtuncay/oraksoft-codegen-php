<?php
require 'fiAppImports.php';

use codegen\ficols\FicFiCol;
use codegen\ficols\FicFiMeta;
use codegen\modals\CgmCsharp;
use codegen\modals\CgmCsharpTempsForFiColClass;
use codegen\modals\CgmFiColClass;
use codegen\modals\CgmFiColUtil;
use codegen\modals\CgmJavaOld;
use codegen\modals\CgmJavaTempsForFiColClass;
use codegen\modals\DtoCodeGen;
use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\Core\FiString;
use Engtuncay\Phputils8\Core\FiwArray;
use Engtuncay\Phputils8\Excel\FiExcel;
use Engtuncay\Phputils8\Log\FiLog;
use Engtuncay\Phputils8\Meta\Fdr;
use codegen\modals\CgmPhp;
use Engtuncay\Phputils8\Meta\FiKeybean;
use Engtuncay\Phputils8\Meta\FkbList;

FiLog::initLogger('filog');

$fdrExcel = new Fdr();

$txCodeGenExtra = "";
/** @var DtoCodeGen[] $arrDtoCodeGen */
$arrDtoCodeGen = [];
$sbTxCodeGen = new FiStrbui();

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

  //print_r($fdr);
  //echo var_export($fdr->getFkbList(), true);
  //echo PHP_EOL;
  //print_r($fdr->getFkbList());

  // Formdan gelen POST verilerini al
  $formData = $_POST;

  // stdClass nesnesine dönüştür
  $formObject = (object)$formData;

  if ($formObject->selPhp == "1") {
    $fiExcel = new FiExcel();
    $fdrExcel = $fiExcel::readExcelFile($inputFileName, FicFiCol::GenTableCols());
    $fkbListExcel = $fdrExcel->getFkbListInit();

    $sbTxCodeGen->append("// Php FiCol Class Generation v1\n");
    $sbTxCodeGen->append(CgmPhp::actGenFiColClassByFkbList($fkbListExcel));
    $sbTxCodeGen->append("\n");
    //$txCodeGenExtra .= json_encode($fdrExcel->getFkbListInit()->getAsMultiArray());
  }

  if ($formObject->selCsharp == "1") {
    $fiExcel = new FiExcel();
    $fdrExcel = $fiExcel::readExcelFile($inputFileName, FicFiCol::GenTableCols());
    $fkbListExcel = $fdrExcel->getFkbListInit();

    /** @var FkbList[] $arrFkbListExcel */
    $arrFkbListExcel = CgmFiColUtil::arrEntityFkbExcel($fkbListExcel);
    $txIdPref = "java";
    $lnForIndex = 0;

    foreach ($arrFkbListExcel as $fkbExcel) {
      $lnForIndex++;
      $dtoCodeGen = new DtoCodeGen();
      $sbTxCodeGen1 = new FiStrbui();
      $sbTxCodeGen1->append("// Csharp FiCol Class Generation v1\n");
      $sbTxCodeGen1->append(CgmFiColClass::actGenFiColClassByFkb($fkbExcel, new CgmCsharpTempsForFiColClass()));
      $sbTxCodeGen1->append("\n");
      $dtoCodeGen->setSbCodeGen($sbTxCodeGen1);
      $dtoCodeGen->setDcgId($txIdPref . $lnForIndex);
      $arrDtoCodeGen[] = $dtoCodeGen;
    }

//    $sbTxCodeGen->append("// Csharp FiCol Class Generation v1\n");
//    //$sbTxCodeGen->append(CgmCsharp::actGenFiColClassByFkbList($fkbListExcel));
//    $sbTxCodeGen->append(CgmFiColClass::actGenFiColClassByFkb($fkbListExcel,new CgmCsharpTempsForFiColClass()));
//    $sbTxCodeGen->append("\n");
    //$txCodeGenExtra .= json_encode($fdrExcel->getFkbListInit()->getAsMultiArray());
  }

  if ($formObject->selPhp == "2") {
    $fiExcel = new FiExcel();
    $cols = FicFiMeta::GenTableCols();
    $cols->add(FicFiCol::ofcTxEntityName());
    $fdrExcel = $fiExcel::readExcelFile($inputFileName, $cols);
    $fkbListExcel = $fdrExcel->getFkbListInit();

    $sbTxCodeGen->append("// Php FiMeta Class Generation v1\n");
    $sbTxCodeGen->append(CgmPhp::actGenFiMetaClass($fkbListExcel));
    $sbTxCodeGen->append("\n");
    $txCodeGenExtra .= json_encode($fdrExcel->getFkbListInit()->getAsMultiArray());
  }

  if ($formObject->selJava == "1") {
    $fiExcel = new FiExcel();
    $fdrExcel = $fiExcel::readExcelFile($inputFileName, FicFiCol::GenTableCols());
    $fkbListExcel = $fdrExcel->getFkbListInit();

    $sbTxCodeGen->append("// Java FiMeta Class Generation v1\n");
    $sbTxCodeGen->append(CgmFiColClass::actGenFiColClassByFkb($fkbListExcel, new CgmJavaTempsForFiColClass()));
    $sbTxCodeGen->append("\n");
    $txCodeGenExtra .= json_encode($fdrExcel->getFkbListInit()->getAsMultiArray());
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
<?php foreach ($arrDtoCodeGen as $arrDtoGen) { ?>
    <div class="container mt-3">
        <!--code blok -->
        <div class="position-relative">
    <pre class="p-3 rounded"><code id="<?=$arrDtoGen->getDcgId()?>"><?= $arrDtoGen->getSbCodeGen()->toString(); ?></code></pre>
            <button class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-2 copy-btn"
                    onclick="copyCode('<?=trim($arrDtoGen->getDcgId())?>')">
                Copy
            </button>
        </div>
    </div>
<?php } ?>


<div class="container mt-3">
    <!--code blok -->
    <div class="position-relative">
    <pre class="p-3 rounded">
        <code id="code-snippet-extra"><?= $txCodeGenExtra; ?></code>
    </pre>
        <button class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-2 copy-btn"
                onclick="copyCodeExtra()">
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

    function copyCode(txIdName) {
        const code = document.getElementById(txIdName).innerText;
        navigator.clipboard.writeText(code).then(() => {
            //alert("Copied!");
        });
    }

    function copyCodeExtra() {
        const code = document.getElementById("code-snippet-extra").innerText;
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

    //console.log(fkb);
    //console.log(fdrResult);

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