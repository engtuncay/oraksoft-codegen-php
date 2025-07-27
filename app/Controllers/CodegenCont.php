<?php

namespace App\Controllers;

use Codegen\ficols\FicFiCol;
use Codegen\FiCols\FicFiMeta;
use Codegen\modals\CgmFiColClass;
use Codegen\modals\CgmFiColUtil;
use Codegen\Modals\CgmFiMetaClass;
use Codegen\Modals\CgmFkbColClass;
use Codegen\Modals\CgmJavaSpecs;
use Codegen\Modals\CogCsharpSpecs;
use Codegen\Modals\CogPhpSpecs;
use Codegen\Modals\DtoCodeGen;
use Codegen\Modals\ICogFicSpecs;
use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\FiExcel\FiExcel;
use Engtuncay\Phputils8\FiCsv\FiCsv;
use Engtuncay\Phputils8\FiDto\Fdr;
use Engtuncay\Phputils8\FiDto\FkbList;

class CodegenCont extends BaseController
{

  public function index()
  {
    log_message('info', 'Codegen Controller index method called.');

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
    //$fkbListData = new FkbList();

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
      list($fdrData, $arrDtoCodeGenPack) = self::genFiColClassesFromFile($uploadedFile, $cogSpecs);
    }

    if ($selPhp == 1) {
      $cogSpecs = new CogPhpSpecs();
      list($fdrData, $arrDtoCodeGenPack) = self::genFiColClassesFromFile($uploadedFile, $cogSpecs);
    }

    if ($selPhp == 2) {
      $cogSpecs = new CogPhpSpecs();
      list($fdrData, $arrDtoCodeGenPack) = self::genFiMetaClassesFromFile($uploadedFile, $cogSpecs);
    }

    if ($selPhp == 3) {
      $cogSpecs = new CogPhpSpecs();
      list($fdrData, $arrDtoCodeGenPack) = self::genFkbColClassesFromFile($uploadedFile, $cogSpecs);
    }

    if ($selJava == 1) {
      $cogSpecs = new CgmJavaSpecs();
      list($fdrData, $arrDtoCodeGenPack) = self::genFiColClassesFromFile($uploadedFile, $cogSpecs);
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
   * 
   * @param mixed $sourceFile
   * @param ICogFicSpecs $iCogSpecs
   * @return array
   */
  public function genFiColClassesFromFile(mixed $sourceFile, ICogFicSpecs $iCogSpecs): array
  {
    
    $fdrData = self::convertFileToFkbList($sourceFile);
    $fkbListData = $fdrData->getFkbListInit();
    //echo var_export($fkbListExcel, true);

    /** @var FkbList[] $mapEntityToFkbList */
    $mapEntityToFkbList = CgmFiColUtil::mapEntityToFkbList($fkbListData);

    log_message('info', 'arrFkbListExcel' . print_r($mapEntityToFkbList, true));
    $txIdPref = "java";
    $lnForIndex = 0;

    foreach ($mapEntityToFkbList as $fkbList) {
      $lnForIndex++;
      $dtoCodeGen = new DtoCodeGen();
      $sbTxCodeGen1 = new FiStrbui();
      $sbTxCodeGen1->append("// FiCol Class Generation v1\n");
      $sbTxCodeGen1->append(CgmFiColClass::actGenFiColClassByFkb($fkbList, $iCogSpecs));
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
   * 
   * @param mixed $sourceFile
   * @param ICogFicSpecs $iCogSpecs
   * @return array
   */
  public function genFkbColClassesFromFile(mixed $sourceFile, ICogFicSpecs $iCogSpecs): array
  {
    
    $fdrData = self::convertFileToFkbList($sourceFile);
    $fkbListData = $fdrData->getFkbListInit();
    //echo var_export($fkbListExcel, true);

    /** @var FkbList[] $mapEntityToFkbList */
    $mapEntityToFkbList = CgmFiColUtil::mapEntityToFkbList($fkbListData);

    log_message('info', 'arrFkbListExcel' . print_r($mapEntityToFkbList, true));
    $txIdPref = "codegen";
    $lnForIndex = 0;

    // fkbList, Excelde bir entity için tanımlanmış alanların listesi
    foreach ($mapEntityToFkbList as $fkbList) {
      $lnForIndex++;
      $dtoCodeGen = new DtoCodeGen();
      $sbTxCodeGen1 = new FiStrbui();
      $sbTxCodeGen1->append("// Codegen v2\n");
      $sbTxCodeGen1->append(CgmFkbColClass::actGenClassByFkbList($fkbList, $iCogSpecs));
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
   * @param mixed $sourceFile
   * @return Fdr
   */
  public function convertFileToFkbList(mixed $sourceFile): Fdr
  {
    $fileExtension = pathinfo($sourceFile->getClientPath(), PATHINFO_EXTENSION);

    if ($fileExtension == "csv") {
      $fiCsv = new FiCsv();
      $fiCols = FicFiCol::GenTableCols();
      $fiCols->add(FicFiMeta::ofmTxKey());
      $fdrData = $fiCsv::read($sourceFile, $fiCols);
      $fkbListData = $fdrData->getFkbListInit();
      return $fdrData;
    }

    if ($fileExtension == "xlsx" || $fileExtension == "xls") {
      $fiExcel = new FiExcel();
      $fdrData = $fiExcel::readExcelFile($sourceFile, FicFiCol::GenTableCols());
      $fkbListData = $fdrData->getFkbListInit();
      return $fdrData;
    }

    $fdrData = new Fdr();
    $fdrData->setMessage("Geçersiz dosya formatı. Sadece .xlsx, .xls veya .csv dosyaları yükleyebilirsiniz.");
    $fdrData->setFkbList(new FkbList());

    return $fdrData; // Boş FkbList döndür
  }

  /**
   * @param mixed $sourceFile
   * @param ICogFicSpecs $iCogSpecs
   * @return array
   */
  public function genFiMetaClassesFromFile(mixed $sourceFile, ICogFicSpecs $iCogSpecs): array
  { 
    //array|string $fileExtension,

    $fdrData = self::convertFileToFkbList($sourceFile);
    $fkbListData = $fdrData->getFkbListInit();

    //echo var_export($fkbListExcel, true);
    
    /** @var FkbList[] $mapEntityToFkbList */
    $mapEntityToFkbList = CgmFiColUtil::mapEntityToFkbList($fkbListData);

    log_message('info', 'arrFkbListExcel' . print_r($mapEntityToFkbList, true));
    $txIdPref = "codegen";
    $lnForIndex = 0;

    foreach ($mapEntityToFkbList as $fkbList) {
      $lnForIndex++;
      $dtoCodeGen = new DtoCodeGen();
      $sbTxCodeGen1 = new FiStrbui();
      $sbTxCodeGen1->append("// Codegen v2\n");
      $sbTxCodeGen1->append(CgmFiMetaClass::actGenFiMetaClassByFkb($fkbList, $iCogSpecs));
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
