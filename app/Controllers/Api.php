<?php

namespace App\Controllers;

use Codegen\Modals\CgmCodegen;
use Codegen\Modals\CgmMssqlserver;
use Codegen\Modals\CgmUtils;
use Codegen\Modals\CogSpecsCsharp;
use Codegen\Modals\CogSpecsCSharpFiCol;
use Codegen\Modals\CogSpecsCsharpFiMeta;
use Codegen\Modals\CogSpecsCSharpFkbCol;
use Codegen\Modals\CogSpecsJava;
use Codegen\Modals\CogSpecsJavaFiCol;
use Codegen\Modals\CogSpecsJavaFiMeta;
use Codegen\Modals\CogSpecsJavaFkbCol;
use Codegen\Modals\CogSpecsJs;
use Codegen\Modals\CogSpecsJsFiMeta;
use Codegen\Modals\CogSpecsJsFkbCol;
use Codegen\Modals\CogSpecsPhp;
use Codegen\Modals\CogSpecsPhpFiCol;
use Codegen\Modals\CogSpecsPhpFiMeta;
use Codegen\Modals\CogSpecsPhpFkbCol;
use Codegen\Modals\CogSpecsTsFiMeta;
use Codegen\Modals\CogSpecsTsFkbCol;
use Codegen\Modals\CogSpecsTs;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCsvs\FiCsv;
use Engtuncay\Phputils8\FiDbs\FiQuery;
use Engtuncay\Phputils8\FiDtos\Fdr;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiPdos\FiPdo;

class Api extends ResourceController
{
  public function index()
  {
    return $this->respond(['message' => 'API is working (index)']);
  }

  public function testpost()
  {
    $request = Services::request();
    $data = $request->getJSON();
    //$musTXTEMAIL = $this->request->getJSON()->musTXTEMAIL;
    return $this->respond(['result' => ['refValue' => 'Gelen veri: ' . json_encode($data, JSON_UNESCAPED_UNICODE)]]);
  }

  public function testget()
  {
    return $this->respond(['message' => 'API is working (testget)']);
  }

  public function getEntityList()
  {
    log_message('info', 'Api::getEntityList called');

    $request = Services::request();
    $file = $request->getFile('excelFile');

    if ($file && $file->isValid() && !$file->hasMoved()) {
      $originalName = $file->getClientName();

      $fdr = $this->convertFileToFkbList($file);

      /** @var FkbList[] $mapEntityToFkbList */
      $fiwEntity = CgmUtils::genFkbAsEntityList($fdr->getFkbListInit());

      $satirBilgi = 'Satır Sayısı: ' . $fdr->getFkbListInit()->size();

      // if ($fdr->getFkbListInit()->size() > 0) {
      // }

      $fkbResponse = new FiKeybean();
      $fkbResponse->add('filename', $originalName);
      $fkbResponse->add('lnRows', $fdr->getFkbListInit()->size());
      $fkbResponse->add('entities', $fiwEntity->getArrValue());

      //return $this->respond(['lnRows' => $fdr->getFkbListInit()->size(), 'fileName' => $originalName ], status: 200);
      return $this->respond($fkbResponse->getParams(), status: 200);
    }

    return $this->respond(['error' => 'Dosya yok veya geçersiz'], 400);
  }

  public function convertFileToFkbList(mixed $sourceFile): Fdr
  {
    $fileExtension = pathinfo($sourceFile->getClientPath(), PATHINFO_EXTENSION);

    if ($fileExtension == "csv") {
      $fiCsv = new FiCsv();
      //$fiCols = FicFiCol::GenTableCols();
      //$fiCols->add(FicFiMeta::ofmTxKey());
      $fdrData = $fiCsv::readByFirstRowHeader($sourceFile);
      $fkbListData = $fdrData->getFkbListInit();
      return $fdrData;
    }

    // if ($fileExtension == "xlsx" || $fileExtension == "xls") {
    //   $fiExcel = new FiExcel();
    //   $fdrData = $fiExcel::readExcelFile($sourceFile, FicFiCol::GenTableCols());
    //   $fkbListData = $fdrData->getFkbListInit();
    //   return $fdrData;
    // }

    $fdrData = new Fdr();
    $fdrData->setMessage("Geçersiz dosya formatı. Sadece .xlsx, .xls veya .csv dosyaları yükleyebilirsiniz.");
    $fdrData->setFkbList(new FkbList());

    return $fdrData; // Boş FkbList döndür
  }

  public function genCode()
  {
    $request = Services::request();
    $uploadedFile = $request->getFile('excelFile');

    //log_message('info', print_r($request,true));

    //if ($file && $file->isValid() && !$file->hasMoved()) {

    log_message('info', 'genCode()');
    $fdrData = new Fdr();

    //$txCodeGenExtra = "";

    /** @var DtoCodeGen[] $arrDtoCodeGenPack */
    $arrDtoCodeGenPack = [];
    $sbTxCodeGen = new FiStrbui();
    //$fkbListData = new FkbList();

    //$data = $request->getPost();

    // Form verilerini al
    $selCsharp = $request->getPost('selCsharp');
    $selTs = $request->getPost('selTs');
    $selPhp = $request->getPost('selPhp');
    $selJava = $request->getPost('selJava');
    $selSql = $request->getPost('selSql');
    $selJs = $request->getPost('selJs');
    $formTxEntity = $request->getPost('selEntity');

    // log_message('info', 'Selected Options:');
    log_message('info', 'Csharp Sel: ' . $selCsharp);

    // log_message('info', 'Db Active Checkbox: ' . $this->request->getPost('chkEnableDb'));

    //$uploadedFile = $this->request->getFile('excelFile'); // $_FILES['excelFile'];

    //log_message('info', 'File uploaded: ' . print_r($uploadedFile, true));

    // Dosya geçici olarak kaydediliyor
    // $_SESSION["uploaded_file"] = $_FILES["excelFile"]["name"];

    // if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
    //   //die('Dosya yüklenirken hata oluştu.');
    //   $fdrData->setTxValue("Dosya yüklenirken Hata oluştu.");
    //   //goto endExcelOkuma;
    // }

    // Dosya uzantısını al
    $fileExtension = pathinfo($uploadedFile->getClientPath(), PATHINFO_EXTENSION);

    //log_message('info', 'file extension:' . $fileExtension);

    $allowedExtensions = ['xlsx', 'xls', 'csv'];

    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
      $mess = 'Geçersiz dosya formatı. Sadece csv dosyaları yükleyebilirsiniz.';
      $fdrData->setTxValue($mess);
      goto endExcelOkuma;
    }

    $fdrData = CgmCodegen::convertFileToFkbList($uploadedFile);
    $fkbListData = $fdrData->getFkbListInit();

    /** @var FiKeybean $fkbEntityToFkbList */
    $fkbEntityToFkbList = CgmUtils::genFkbAsEntityToFkbList($fkbListData);

    $fkbListEntity = null;

    if ($fkbEntityToFkbList->has($formTxEntity)) {
      $fkbListEntity = $fkbEntityToFkbList->getValue($formTxEntity);
      //$fdrCodegen = CgmMssqlserver::actGenSqlCreateTableByEntity($fkbListEntity);
    }

    if ($formTxEntity && !$fkbListEntity) {
      $fdrData->setTxValue("Seçilen entity bulunamadı: " . $formTxEntity);
      goto endExcelOkuma;
    }

    // print_r($fdr);
    // echo var_export($fdr->getFkbList(), true);
    // echo PHP_EOL;

    //stdClass nesnesine dönüştür
    //$formObject = (object)$formData;

    $cogSpecs = null;
    $cogSpecsGenCol = null;

    // FiCol
    if ($selCsharp > 0) $cogSpecs = new CogSpecsCsharp();
    if ($selPhp > 0) $cogSpecs = new CogSpecsPhp();
    if ($selJava > 0) $cogSpecs = new CogSpecsJava();
    if ($selTs > 0) $cogSpecs = new CogSpecsTs();
    if ($selJs > 0) $cogSpecs = new CogSpecsJs();

    #Csharp CogSpecs
    if ($selCsharp == 1) $cogSpecsGenCol = new CogSpecsCSharpFiCol();
    if ($selCsharp == 2 || $selCsharp == 4) $cogSpecsGenCol = new CogSpecsCsharpFiMeta();
    if ($selCsharp == 3) $cogSpecsGenCol = new CogSpecsCSharpFkbCol();

    #Php CogSpecs
    if ($selPhp == 1) $cogSpecsGenCol = new CogSpecsPhpFiCol();
    if ($selPhp == 2 || $selPhp == 4) $cogSpecsGenCol = new CogSpecsPhpFiMeta();
    if ($selPhp == 3) $cogSpecsGenCol = new CogSpecsPhpFkbCol();

    //---- Java
    if ($selJava == 1) $cogSpecsGenCol = new CogSpecsJavaFiCol();
    if ($selJava == 2) $cogSpecsGenCol = new CogSpecsJavaFiMeta();
    if ($selJava == 3) $cogSpecsGenCol = new CogSpecsJavaFkbCol();

    //---- Typescript
    if ($selTs == 3) $cogSpecsGenCol = new CogSpecsTsFkbCol();
    if ($selTs == 2 ||  $selTs == 4) $cogSpecsGenCol = new CogSpecsTsFiMeta();

    if ($selJs == 3) $cogSpecsGenCol = new CogSpecsJsFkbCol();
    if ($selJs == 2 ||  $selJs == 4) $cogSpecsGenCol = new CogSpecsJsFiMeta();

    //---- Code Üretimi
    $fdrCodegen =  new Fdr();

    // ColClass üretimi (C#, Java, Php, Js)
    $selClassType = max($selPhp, $selJava, $selCsharp, $selTs, $selJs);

    if ($selClassType > 0 && $cogSpecs && $cogSpecsGenCol) {
      $fdrCodegen = CgmCodegen::genCodeColClass($fkbListEntity, $cogSpecs, $cogSpecsGenCol, $selClassType);
    }

    if ($selSql == 1) {
      $fdrCodegen = CgmMssqlserver::actGenSqlCreateTableByEntity($fkbListEntity);
    }

    endExcelOkuma:

    //$fkbReturn = CgmApiUtil::genFkbReturn($fdrCodegen);

    // (codegen)[../Views/codegen.php] 
    return $this->respond($fdrCodegen->genArrReturn(), 200);

    // return $this->response->setJSON([
    //     'status' => 'error',
    //     'message' => 'File upload failed'
    // ]);
  }

  public function test1()
  {
    //$ocgAppConfig = new OcgCLogger
    //FiAppConfig::$fiConfig->getProfile()
    $fiPdo = FiPdo::buiWithProfile("");

    $fiQuery = new FiQuery();
    $sql = "SELECT * FROM settings"; 
    $fiQuery->setSql($sql);

    $fdr = $fiPdo->selectFkb($fiQuery);

    return $this->respond(['message' => 'API Test is working (test1)', 'data' => print_r($fdr,true)]);
  }


}
