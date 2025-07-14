<?php

namespace App\Controllers;

use Codegen\ficols\FicFiCol;
use Codegen\modals\CgmFiColClass;
use Codegen\modals\CgmFiColUtil;
use Codegen\Modals\CogFicCsharpSpecs;
use Codegen\Modals\DtoCodeGen;
use Codegen\Modals\ICogFicSpecs;
use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\Excel\FiExcel;
use Engtuncay\Phputils8\FiCsv\FiCsv;
use Engtuncay\Phputils8\Meta\Fdr;
use Engtuncay\Phputils8\Meta\FkbList;

class CodegenCont extends BaseController
{

  public function index()
  {
    log_message('info', 'Codegen index method called.');

    // HTTP metod kontrolü için alternatif yöntemler:
    // Yöntem 1: getMethod() - büyük harfle döner
    if ($this->request->getMethod() === 'POST') {
      log_message('info', 'Codegen form submitted.');
      return $this->processCodegen();
    }

    // Yöntem 2: isPost() - boolean döner (tercih edilen)
    // if ($this->request->is('post')) {
    //   log_message('info', 'Codegen form submitted.');
    //   return $this->processCodegen();
    // }

    // Yöntem 3: getMethod(true) - küçük harfle döner  
    // if ($this->request->getMethod(true) === 'post') {
    //   log_message('info', 'Codegen form submitted.');
    //   return $this->processCodegen();
    // }

    log_message('info', 'Codegen index page accessed without form submission.');
    $data = [];
    // GET isteği için codegen sayfasını göster
    return view('codegen', $data);
  }

  private function processCodegen()
  {
    log_message('info', 'Codegen process started.');
    $fdrData = new Fdr();

    $txCodeGenExtra = "";
    /** @var DtoCodeGen[] $arrDtoCodeGenPack */
    $arrDtoCodeGenPack = [];
    $sbTxCodeGen = new FiStrbui();
    $fkbListData = new FkbList();

    // Form verilerini al
    $selCsharp = $this->request->getPost('selCsharp');
    $selTs = $this->request->getPost('selTs');
    $selPhp = $this->request->getPost('selPhp');
    $selJava = $this->request->getPost('selJava');

    log_message('info', 'Selected options: Csharp: ' . $selCsharp . ', Ts: ' . $selTs . ', Php: ' . $selPhp . ', Java: ' . $selJava);

    //$excelFile = $this->request->getFile('excelFile');

    $uploadedFile = $this->request->getFile('excelFile'); // $_FILES['excelFile'];

    log_message('info', 'File uploaded: ' . print_r($uploadedFile, true));

    // Dosya geçici olarak kaydediliyor
    // $_SESSION["uploaded_file"] = $_FILES["excelFile"]["name"];

    // if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
    //   //die('Dosya yüklenirken hata oluştu.');
    //   $fdrData->setMessage("Dosya yüklenirken Hata oluştu.");
    //   //goto endExcelOkuma;
    // }

    // Dosya uzantısını al
    $fileExtension = pathinfo($uploadedFile->getClientPath(), PATHINFO_EXTENSION);
    
    log_message('info', 'file extension:' . $fileExtension);

    $allowedExtensions = ['xlsx', 'xls', 'csv'];

    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
      $mess = 'Geçersiz dosya formatı. Sadece .xlsx, .xls veya .csv dosyaları yükleyebilirsiniz.';
      //die($mess);
      $fdrData->setMessage($mess);
      goto endExcelOkuma;
    }

    //$inputFileName = $uploadedFile['tmp_name'];

    //print_r($fdr);
    //echo var_export($fdr->getFkbList(), true);
    //echo PHP_EOL;
    //print_r($fdr->getFkbList());

    // Formdan gelen POST verilerini al
    //$formData = $_POST;

    // stdClass nesnesine dönüştür
    //$formObject = (object)$formData;

    //if ($formObject->selPhp == "1") {
    //  $fiExcel = new FiExcel();
    //  $fdrData = $fiExcel::readExcelFile($inputFileName, FicFiCol::GenTableCols());
    //  $fkbListData = $fdrData->getFkbListInit();
    //
    //  $sbTxCodeGen->append("// Php FiCol Class Generation v1\n");
    //  $sbTxCodeGen->append(CgmPhp::actGenFiColClassByFkbList($fkbListData));
    //  $sbTxCodeGen->append("\n");
    //  //$txCodeGenExtra .= json_encode($fdrExcel->getFkbListInit()->getAsMultiArray());
    //}

    if ($selCsharp == 1) {

      log_message('info', 'selCsharp');

      $cogSpecs = new CogFicCsharpSpecs();

      list($fdrData, $arrDtoCodeGenPack) = self::generateDtoCodeFromFile($fileExtension, $uploadedFile, $fkbListData, $cogSpecs, $arrDtoCodeGenPack);
      
    }

    // if ($formObject->selPhp == "2") {

    //   $iFiColClass = new CogFicCsharpSpecs();

    //   list($fdrData, $arrDtoCodeGenPack) = generateDtoCodeFromFile($fileExtension, $inputFileName, $fkbListData, $iFiColClass, $arrDtoCodeGenPack);
    //   //$fkbListData

    //   //    $fiExcel = new FiExcel();
    //   //    $cols = FicFiMeta::GenTableCols();
    //   //    $cols->add(FicFiCol::ofcTxEntityName());
    //   //    $fdrData = $fiExcel::readExcelFile($inputFileName, $cols);
    //   //    $fkbListData = $fdrData->getFkbListInit();
    //   //
    //   //    $sbTxCodeGen->append("// Php FiMeta Class Generation v1\n");
    //   //    $sbTxCodeGen->append(CgmPhp::actGenFiMetaClass($fkbListData));
    //   //    $sbTxCodeGen->append("\n");
    //   //    $txCodeGenExtra .= json_encode($fdrData->getFkbListInit()->getAsMultiArray());
    // }

    // if ($formObject->selJava == "1") {

    //   $iFiColClass = new CgmJavaSpecs();

    //   list($fdrData, $arrDtoCodeGenPack) = generateDtoCodeFromFile($fileExtension, $inputFileName, $fkbListData, $iFiColClass, $arrDtoCodeGenPack);
    //   //$fkbListData
    // }

    // Nesne olarak verileri görüntüle
    //  echo "Ad: " . $formObject->name . "\n";
    //  echo("<br/>");
    //  echo "Email: " . $formObject->email . "\n";
    //  echo("<br/>");
    //print_r($formObject);
    //} else {
    //$fdrData = new Fdr(false, "No Excel Upload File");
    //}
    endExcelOkuma:

    $data = [
      'fdrData' => $fdrData,
      'arrDtoCodeGenPack' => $arrDtoCodeGenPack,
      'sbTxCodeGen' => $sbTxCodeGen,
      'txCodeGenExtra' => $txCodeGenExtra,
      'formData' => $formObject ?? null
    ];
    // [[../Views/codegen.php]] 
    return view('codegen', $data);

    // Dosya yükleme işlemi


    // if ($excelFile && $excelFile->isValid() && !$excelFile->hasMoved()) {
    //     // Dosya işleme mantığı burada olacak
    //     $uploadPath = FCPATH . 'uploads/';
    //     $fileName = $excelFile->getRandomName();
    //     $excelFile->move($uploadPath, $fileName);

    //     // Code generation işlemi burada yapılacak
    //     // ...

    //     // Sonuç sayfasını göster veya JSON response döndür
    //     return $this->response->setJSON([
    //         'status' => 'success',
    //         'message' => 'Code generation completed',
    //         'file' => $fileName
    //     ]);
    // }

    // return $this->response->setJSON([
    //     'status' => 'error',
    //     'message' => 'File upload failed'
    // ]);
  }

  /**
   * @param array|string $fileExtension
   * @param mixed $sourceFile
   * @param FkbList $fkbListData
   * @param CogFicCsharpSpecs $IFiColClass
   * @param array $arrDtoCodeGen
   * @return array
   */
  public function generateDtoCodeFromFile(array|string $fileExtension, mixed $sourceFile, FkbList $fkbListData, ICogFicSpecs $iFiColClass, array $arrDtoCodeGen): array
  {

    if ($fileExtension == "csv") {
      $fiCsv = new FiCsv();
      $fdrData = $fiCsv::read($sourceFile, FicFiCol::GenTableCols());
      $fkbListData = $fdrData->getFkbListInit();
    }

    if ($fileExtension == "xlsx" || $fileExtension == "xls") {
      $fiExcel = new FiExcel();
      $fdrData = $fiExcel::readExcelFile($sourceFile, FicFiCol::GenTableCols());
      $fkbListData = $fdrData->getFkbListInit();
    }

    //echo var_export($fkbListExcel, true);

    /** @var FkbList[] $arrFkbListExcel */
    $arrFkbListExcel = CgmFiColUtil::arrEntityFkbExcel($fkbListData);

    log_message('info', 'arrFkbListExcel' . print_r($arrFkbListExcel,true));
    $txIdPref = "java";
    $lnForIndex = 0;

    foreach ($arrFkbListExcel as $fkbExcel) {
      $lnForIndex++;
      $dtoCodeGen = new DtoCodeGen();
      $sbTxCodeGen1 = new FiStrbui();
      $sbTxCodeGen1->append("// FiCol Class Generation v1\n");
      $sbTxCodeGen1->append(CgmFiColClass::actGenFiColClassByFkb($fkbExcel, $iFiColClass));
      $sbTxCodeGen1->append("\n");
      $dtoCodeGen->setSbCodeGen($sbTxCodeGen1);
      $dtoCodeGen->setDcgId($txIdPref . $lnForIndex);
      $arrDtoCodeGen[] = $dtoCodeGen;
    }

    log_message('info', 'arrDtoCodeGen: ' . print_r($arrDtoCodeGen,true));
    log_message('info', 'fdrData: ' . print_r($fdrData,true));

    return array($fdrData, $arrDtoCodeGen); //$fiExcel $fkbListData
  }
}
