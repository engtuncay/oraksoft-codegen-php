<?php

namespace App\Controllers;

use Codegen\Modals\CgmCodegen;
use Codegen\Modals\CgmMssqlserver;
use Codegen\modals\CgmFiColClass;
use Codegen\modals\CgmUtils;
use Codegen\Modals\CgmFiMetaClass;
use Codegen\Modals\CgmFiMetaClassByDml;
use Codegen\Modals\CgmFkbColClass;
use Codegen\Modals\DtoCodeGen;
use Codegen\Modals\ICogSpecs;
use Codegen\Modals\ICogSpecsFiMeta;
use Codegen\Modals\ICogSpecsFiCol;
use Codegen\Modals\ICogSpecsFkbCol;
use Codegen\Modals\CogSpecsCsharp;
use Codegen\Modals\CogSpecsCSharpFiCol;
use Codegen\Modals\CogSpecsCsharpFiMeta;
use Codegen\Modals\CogSpecsCSharpFkbCol;
use Codegen\Modals\CogSpecsJava;
use Codegen\Modals\CogSpecsJavaFiCol;
use Codegen\Modals\CogSpecsJavaFiMeta;
use Codegen\Modals\CogSpecsJavaFkbCol;
use Codegen\Modals\CogSpecsPhp;
use Codegen\Modals\CogSpecsPhpFiCol;
use Codegen\Modals\CogSpecsPhpFiMeta;
use Codegen\Modals\CogSpecsPhpFkbCol;
use Codegen\Modals\CogSpecsTsFiMeta;
use Codegen\Modals\CogSpecsTsFkbCol;
use Codegen\Modals\CogSpecsTs;
use Codegen\Modals\ICogSpecsGenCol;
use Codegen\OcgConfigs\OcgLogger;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiExcels\FiExcel;
use Engtuncay\Phputils8\FiCsvs\FiCsv;
use Engtuncay\Phputils8\FiDtos\Fdr;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;

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
    $selSql = $this->request->getPost('selSql');

    // log_message('info', 'Selected Options:');
    // log_message('info', 'Csharp: ' . $selCsharp);
    // log_message('info', 'Ts: ' . $selTs);
    // log_message('info', 'Php: ' . $selPhp);
    // log_message('info', 'Java: ' . $selJava);
    // log_message('info', 'Sql: ' . $selSql);

    //log_message('info', 'Db Active Checkbox: ' . $this->request->getPost('chkEnableDb'));
    //log_message('info', 'Request Object: ' . print_r($this->request, true));

    //$excelFile = $this->request->getFile('excelFile');

    $uploadedFile = $this->request->getFile('excelFile'); // $_FILES['excelFile'];

    //log_message('info', 'File uploaded: ' . print_r($uploadedFile, true));

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
      $mess = 'Geçersiz dosya formatı. Sadece csv dosyaları yükleyebilirsiniz.';
      $fdrData->setMessage($mess);
      goto endExcelOkuma;
    }

    $fdrData = self::convertFileToFkbList($uploadedFile);
    $fkbListData = $fdrData->getFkbListInit();

    // print_r($fdr);
    // echo var_export($fdr->getFkbList(), true);
    // echo PHP_EOL;

    //stdClass nesnesine dönüştür
    //$formObject = (object)$formData;

    $cogSpecs = null;
    $cogSpecsFiCol = null;
    $cogSpecsFkbCol = null;
    $cogSpecsFiMeta = null;

    // FiCol
    if ($selCsharp == 1) {
      $cogSpecs = new CogSpecsCsharp();
      $cogSpecsFiCol = new CogSpecsCSharpFiCol();
    }

    // genFiMetaClassByFiColTempFromFile
    if ($selCsharp == 2 || $selCsharp == 4) {
      $cogSpecs = new CogSpecsCsharp();
      $cogSpecsFiMeta = new CogSpecsCsharpFiMeta();
    }

    // FkbCol
    if ($selCsharp == 3) {
      $cogSpecs = new CogSpecsCsharp();
      $cogSpecsFkbCol = new CogSpecsCSharpFkbCol();
    }

    #region Php CogSpecs

    if ($selPhp == 1) {
      $cogSpecs = new CogSpecsPhp();
      $cogSpecsFiCol = new CogSpecsPhpFiCol();
    }

    if ($selPhp == 2 || $selPhp == 4) {
      $cogSpecs = new CogSpecsPhp();
      $cogSpecsFiMeta = new CogSpecsPhpFiMeta();
    }

    if ($selPhp == 3) {
      $cogSpecs = new CogSpecsPhp();
      $cogSpecsFkbCol = new CogSpecsPhpFkbCol();
    }

    #endregion

    if ($selJava == 1) {
      $cogSpecs = new CogSpecsJava();
      $cogSpecsFiCol = new CogSpecsJavaFiCol();
    }

    if ($selJava == 2) {
      $cogSpecs = new CogSpecsJava();
      $cogSpecsFiMeta = new CogSpecsJavaFiMeta();
    }

    if ($selJava == 3) {
      $cogSpecs = new CogSpecsJava();
      $cogSpecsFkbCol = new CogSpecsJavaFkbCol();
    }

    //---- Typescript

    if ($selTs == 3) {
      $cogSpecs = new CogSpecsTs();
      $cogSpecsFkbCol = new CogSpecsTsFkbCol();
    }

    if ($selTs == 2 ||  $selTs == 4) {
      $cogSpecs = new CogSpecsTs();
      $cogSpecsFiMeta = new CogSpecsTsFiMeta();
    }

    //---- Code Üretimi

    // FiColClass üretimi (C#, Java, Php)
    if ($selPhp == 1 || $selJava == 1 || $selCsharp == 1 || $selTs == 1) {
      //list($fdrData2, $arrDtoCodeGenPack) = self::genFiColClassesFromFile($fkbListData, $cogSpecs, $cogSpecsFiCol);
    }

    if ($selPhp == 2 || $selJava == 2 || $selCsharp == 2 || $selTs == 2) {
      //list($fdrData2, $arrDtoCodeGenPack) = self::genFiMetaClassByDmlTemplateFromFile($fkbListData, $cogSpecs, $cogSpecsFiMeta);
    }

    // FkbColClass üretimi
    if ($selPhp == 3 || $selJava == 3 || $selCsharp == 3 || $selTs == 3) {
      //list($fdrData2, $arrDtoCodeGenPack) = self::genFkbColClassesFromFile($fkbListData, $cogSpecs, $cogSpecsFkbCol);
    }

    if ($selPhp == 4 || $selJava == 4 || $selCsharp == 4 || $selTs == 4) {
      //list($fdrData2, $arrDtoCodeGenPack) = self::genFiMetaClassesFromFile($fkbListData, $cogSpecs, $cogSpecsFiMeta);
    }

    //------ Diger

    if ($selSql == 1) {
      $fdrData = self::convertFileToFkbList($uploadedFile);
      $fdrCdgSql = CgmMssqlserver::actGenSqlCreate($fdrData->getFkbListInit());

      $data = [
        'fdrData' => $fdrCdgSql,
        'arrDtoCodeGenPack' => $fdrCdgSql->getFkbValue()->getParams(),
        'sbTxCodeGen' => $sbTxCodeGen,
        'txCodeGenExtra' => $txCodeGenExtra,
        'formData' => $formObject ?? null
      ];

      // (codegen)[../Views/codegen.php]
      return view('codegen', $data);
    }

    endExcelOkuma:

    $data = [
      'fdrData' => $fdrData,
      'arrDtoCodeGenPack' => $arrDtoCodeGenPack,
      'sbTxCodeGen' => $sbTxCodeGen,
      'txCodeGenExtra' => $txCodeGenExtra,
      'formData' => $formObject ?? null
    ];

    // (codegen)[../Views/codegen.php]
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
   * @param CogSpecsCsharp|null $iCogSpecs
   * @param CogSpecsCSharpFiCol|null $iCogSpecsFiCol
   * @return array
   */
  public function genFiColClassesFromFile(FkbList $fkbListData, ICogSpecs $iCogSpecs, ICogSpecsGenCol $iCogSpecsFiCol): array
  {
    $fdrData = new Fdr();

    // $fdrData = self::convertFileToFkbList($sourceFile);
    // $fkbListData = $fdrData->getFkbListInit();
    //echo var_export($fkbListExcel, true);

    /** @var FkbList[] $mapEntityToFkbList */
    $mapEntityToFkbList = CgmUtils::genFkbMapAsTxEntityToFkl($fkbListData);

    log_message('info', 'arrFkbListExcel' . print_r($mapEntityToFkbList, true));
    $txIdPref = "java";
    $lnForIndex = 0;
    $arrDtoCodeGen = [];

    foreach ($mapEntityToFkbList as $entity => $fkbList) {
      $lnForIndex++;
      $dtoCodeGen = new DtoCodeGen();
      $sbTxCodeGen1 = new FiStrbui();
      $sbTxCodeGen1->append("// FiCol Class Generation v1\n");
      $sbTxCodeGen1->append(CgmFiColClass::actGenFiColClassByFkb($fkbList, $iCogSpecs, $iCogSpecsFiCol));
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
   * @param ICogSpecs $iCogSpecs
   * @return array
   */
  public function genFkbColClassesFromFile(FkbList $fkbListData, ICogSpecs $iCogSpecs, ICogSpecsGenCol $iCogSpecsFkbCol): array
  {

    $fdrData = new Fdr();
    // $fdrData = self::convertFileToFkbList($sourceFile);
    // $fkbListData = $fdrData->getFkbListInit(); 
    // OcgLogger::info("fkbListData:" . print_r($fkbListData->getItems(), true));

    /** @var FiKeybean $fkbEntityToFkbList */
    $fkbEntityToFkbList = CgmUtils::genFkbMapAsTxEntityToFkl($fkbListData);

    //log_message('info', 'arrFkbListExcel' . print_r($fkbEntityToFkbList, true));
    $txIdPref = "codegen";
    $lnForIndex = 0;

    OcgLogger::info("fkblist count:" . count($fkbListData->getAsMultiArray()));
    OcgLogger::info("fkbEntityToFkbList count:" . count($fkbEntityToFkbList->getParams()));
    // fkbList, Excelde bir entity için tanımlanmış alanların listesi

    $arrDtoCodeGen =  [];
    $txVer = $this->getTxVer();

    foreach ($fkbEntityToFkbList as $entity => $fkbList) {
      $lnForIndex++;
      $dtoCodeGen = new DtoCodeGen();
      $sbTxCodeGen1 = new FiStrbui();
      $sbTxCodeGen1->append("// Codegen " . $txVer . "\n");
      $sbTxCodeGen1->append(CgmFkbColClass::actGenClassByFkbList($fkbList, $iCogSpecs, $iCogSpecsFkbCol));
      $sbTxCodeGen1->append("\n");
      $dtoCodeGen->setSbCodeGen($sbTxCodeGen1);
      $dtoCodeGen->setDcgId($txIdPref . $lnForIndex);
      array_push($arrDtoCodeGen, $dtoCodeGen);
    }

    //log_message('info', 'arrDtoCodeGen: ' . print_r($arrDtoCodeGen, true));
    //log_message('info', 'fdrData: ' . print_r($fdrData, true));

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
      //$fiCols = FicFiCol::GenTableCols();
      //$fiCols->add(FicFiMeta::ftTxKey());
      $fdrData = $fiCsv::readByFirstRowHeader($sourceFile);
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
   * @param ICogSpecs $iCogSpecs
   * @param ICogSpecs $iCogSpecs
   * @return array
   */
  public function genFiMetaClassesFromFile(FkbList $fkbListData, ICogSpecs $iCogSpecs, ICogSpecsGenCol $iCogSpecsFiMeta): array
  {
    $fdrData = new Fdr();
    //array|string $fileExtension,

    // $fdrData = self::convertFileToFkbList($sourceFile);
    // $fkbListData = $fdrData->getFkbListInit();

    //echo var_export($fkbListExcel, true);

    /** @var FkbList[] $mapEntityToFkbList */
    $mapEntityToFkbList = CgmUtils::genFkbMapAsTxEntityToFkl($fkbListData);

    log_message('info', 'arrFkbListExcel' . print_r($mapEntityToFkbList, true));
    $txIdPref = "codegen";
    $lnForIndex = 0;

    $txVer = $this->getTxVer();

    foreach ($mapEntityToFkbList as $entity => $fkbList) {
      $lnForIndex++;
      $dtoCodeGen = new DtoCodeGen();
      $sbTxCodeGen1 = new FiStrbui();
      $sbTxCodeGen1->append("// Codegen " . $txVer . "\n");

      $sbTxCodeGen1->append(CgmFiMetaClass::actGenFiMetaClassByFkb($fkbList, $iCogSpecs, $iCogSpecsFiMeta));
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
   * @param ICogSpecs $iCogSpecs
   * @return array
   */
  public function genFiMetaClassByDmlTemplateFromFile(FkbList $fkbListData, ICogSpecs $iCogSpecs, ICogSpecsGenCol $iSpecsFiMeta): array
  {
    $fdrData = new Fdr();
    //array|string $fileExtension,

    // $fdrData = self::convertFileToFkbList($sourceFile);
    // $fkbListData = $fdrData->getFkbListInit();

    //echo var_export($fkbListExcel, true);

    /** @var FkbList[] $mapEntityToFkbList */
    $mapEntityToFkbList = CgmUtils::genFkbMapAsTxEntityToFkl($fkbListData);

    //log_message('info', 'arrFkbListExcel' . print_r($mapEntityToFkbList, true));
    $txIdPref = "codegen";
    $lnForIndex = 0;

    $txVer = $this->getTxVer();

    foreach ($mapEntityToFkbList as $entity => $fkbList) {
      $lnForIndex++;
      $dtoCodeGen = new DtoCodeGen();
      $sbTxCodeGen1 = new FiStrbui();
      $sbTxCodeGen1->append("// Codegen " . $txVer . "\n");
      $sbTxCodeGen1->append(CgmFiMetaClassByDml::actGenFiMetaClassByFkbList($fkbList, $iCogSpecs, $iSpecsFiMeta));
      $sbTxCodeGen1->append("\n");
      $dtoCodeGen->setSbCodeGen($sbTxCodeGen1);
      $dtoCodeGen->setDcgId($txIdPref . $lnForIndex);
      $arrDtoCodeGen[] = $dtoCodeGen;
    }

    //log_message('info', 'arrDtoCodeGen: ' . print_r($arrDtoCodeGen, true));
    //log_message('info', 'fdrData: ' . print_r($fdrData, true));

    return array($fdrData, $arrDtoCodeGen); //$fiExcel $fkbListData
  }

  public static function getTxVer()
  {
    return "0.4";
  }
}
