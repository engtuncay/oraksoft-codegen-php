<?php

namespace App\Controllers;

use Codegen\ficols\FicFiCol;
use Codegen\modals\CgmFiColClass;
use Codegen\modals\CgmFiColUtil;
use Codegen\Modals\CgmJavaSpecs;
use Codegen\Modals\CogCsharpSpecs;
use Codegen\Modals\CogPhpSpecs;
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
      //log_message('info', 'Codegen form submitted.');
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
    log_message('info', 'processCodegen()');
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

    //log_message('info', 'file extension:' . $fileExtension);

    $allowedExtensions = ['xlsx', 'xls', 'csv'];

    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
      $mess = 'Geçersiz dosya formatı. Sadece .xlsx, .xls veya .csv dosyaları yükleyebilirsiniz.';
      $fdrData->setMessage($mess);
      goto endExcelOkuma;
    }

    //print_r($fdr);
    //echo var_export($fdr->getFkbList(), true);
    //echo PHP_EOL;

    //stdClass nesnesine dönüştür
    //$formObject = (object)$formData;

    if ($selCsharp == 1) {
      log_message('info', 'selCsharp');
      $cogSpecs = new CogCsharpSpecs();
      list($fdrData, $arrDtoCodeGenPack) = self::generateDtoCodeFromFile($fileExtension, $uploadedFile, $fkbListData, $cogSpecs, $arrDtoCodeGenPack);
    }

    if ($selPhp == 1) {
      $iFiColClass = new CogPhpSpecs();
      list($fdrData, $arrDtoCodeGenPack) = self::generateDtoCodeFromFile($fileExtension, $uploadedFile, $fkbListData, $iFiColClass, $arrDtoCodeGenPack);
    }

    if ($selPhp == 2) {
      $iFiColClass = new CogPhpSpecs();
      list($fdrData, $arrDtoCodeGenPack) = self::generateCodeFiMetaFromFile($fileExtension, $uploadedFile, $fkbListData, $iFiColClass, $arrDtoCodeGenPack);
    }

    if ($selJava == 1) {
      $iFiColClass = new CgmJavaSpecs();
      list($fdrData, $arrDtoCodeGenPack) = self::generateDtoCodeFromFile($fileExtension, $uploadedFile, $fkbListData, $iFiColClass, $arrDtoCodeGenPack);
    }

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
   * @param CogCsharpSpecs $IFiColClass
   * @param array $arrDtoCodeGen
   * @return array
   */
  public function generateDtoCodeFromFile(array|string $fileExtension, mixed $sourceFile, FkbList $fkbListData, ICogFicSpecs $iCogSpecs, array $arrDtoCodeGen): array
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

    log_message('info', 'arrFkbListExcel' . print_r($arrFkbListExcel, true));
    $txIdPref = "java";
    $lnForIndex = 0;

    foreach ($arrFkbListExcel as $fkbExcel) {
      $lnForIndex++;
      $dtoCodeGen = new DtoCodeGen();
      $sbTxCodeGen1 = new FiStrbui();
      $sbTxCodeGen1->append("// FiCol Class Generation v1\n");
      $sbTxCodeGen1->append(CgmFiColClass::actGenFiColClassByFkb($fkbExcel, $iCogSpecs));
      $sbTxCodeGen1->append("\n");
      $dtoCodeGen->setSbCodeGen($sbTxCodeGen1);
      $dtoCodeGen->setDcgId($txIdPref . $lnForIndex);
      $arrDtoCodeGen[] = $dtoCodeGen;
    }

    log_message('info', 'arrDtoCodeGen: ' . print_r($arrDtoCodeGen, true));
    log_message('info', 'fdrData: ' . print_r($fdrData, true));

    return array($fdrData, $arrDtoCodeGen); //$fiExcel $fkbListData
  }

  /**
   * @param array|string $fileExtension
   * @param mixed $sourceFile
   * @param FkbList $fkbListData
   * @param CogCsharpSpecs $IFiColClass
   * @param array $arrDtoCodeGen
   * @return array
   */
  public function generateCodeFiMetaFromFile(array|string $fileExtension, mixed $sourceFile, FkbList $fkbListData, ICogFicSpecs $iCogSpecs, array $arrDtoCodeGen): array
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

    log_message('info', 'arrFkbListExcel' . print_r($arrFkbListExcel, true));
    $txIdPref = "java";
    $lnForIndex = 0;

    foreach ($arrFkbListExcel as $fkbExcel) {
      $lnForIndex++;
      $dtoCodeGen = new DtoCodeGen();
      $sbTxCodeGen1 = new FiStrbui();
      $sbTxCodeGen1->append("// FiCol Class Generation v1\n");
      $sbTxCodeGen1->append(CgmFiColClass::actGenFiMetaClassByFkb($fkbExcel, $iCogSpecs));
      $sbTxCodeGen1->append("\n");
      $dtoCodeGen->setSbCodeGen($sbTxCodeGen1);
      $dtoCodeGen->setDcgId($txIdPref . $lnForIndex);
      $arrDtoCodeGen[] = $dtoCodeGen;
    }

    log_message('info', 'arrDtoCodeGen: ' . print_r($arrDtoCodeGen, true));
    log_message('info', 'fdrData: ' . print_r($fdrData, true));

    return array($fdrData, $arrDtoCodeGen); //$fiExcel $fkbListData
  }


}
