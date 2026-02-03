<?php

namespace App\Controllers;

use Codegen\Modals\CgmApiUtil;
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
use Codegen\Modals\CogSpecsPhp;
use Codegen\Modals\CogSpecsPhpFiCol;
use Codegen\Modals\CogSpecsPhpFiMeta;
use Codegen\Modals\CogSpecsPhpFkbCol;
use Codegen\Modals\CogSpecsTsFiMeta;
use Codegen\Modals\CogSpecsTsFkbCol;
use Codegen\Modals\CogSpecsTypescript;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCsvs\FiCsv;
use Engtuncay\Phputils8\FiDtos\Fdr;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;

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
    $formTxEntity = $request->getPost('selEntity');
    // log_message('info', 'Selected Options:');
    // log_message('info', 'Csharp: ' . $selCsharp);

    // log_message('info', 'Db Active Checkbox: ' . $this->request->getPost('chkEnableDb'));
    // log_message('info', 'Request Object: ' . print_r($this->request, true));

    //$excelFile = $this->request->getFile('excelFile');

    //$uploadedFile = $this->request->getFile('excelFile'); // $_FILES['excelFile'];

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

    $fdrData = CgmCodegen::convertFileToFkbList($uploadedFile);
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
    if ($selCsharp == 1 || $selCsharp == 2 || $selCsharp == 3 || $selCsharp == 4) {
      $cogSpecs = new CogSpecsCsharp();
    }

    if ($selCsharp == 1) $cogSpecsFiCol = new CogSpecsCSharpFiCol();
    if ($selCsharp == 2 || $selCsharp == 4) $cogSpecsFiMeta = new CogSpecsCsharpFiMeta();
    if ($selCsharp == 3) $cogSpecsFkbCol = new CogSpecsCSharpFkbCol();

    #region Php CogSpecs

    if ($selPhp == 1 || $selPhp == 2 || $selPhp == 3 || $selPhp == 4) {
      $cogSpecs = new CogSpecsPhp();
    }

    if ($selPhp == 1) $cogSpecsFiCol = new CogSpecsPhpFiCol();
    if ($selPhp == 2 || $selPhp == 4) $cogSpecsFiMeta = new CogSpecsPhpFiMeta();
    if ($selPhp == 3) $cogSpecsFkbCol = new CogSpecsPhpFkbCol();

    //---- Java

    if ($selJava == 1 || $selJava == 2 || $selJava == 3 || $selJava == 4) {
      $cogSpecs = new CogSpecsJava();
    }

    if ($selJava == 1) $cogSpecsFiCol = new CogSpecsJavaFiCol();
    if ($selJava == 2) $cogSpecsFiMeta = new CogSpecsJavaFiMeta();
    if ($selJava == 3) $cogSpecsFkbCol = new CogSpecsJavaFkbCol();

    //---- Typescript

    if ($selTs == 1 || $selTs == 2 || $selTs == 3 || $selTs == 4) {
      $cogSpecs = new CogSpecsTypescript();
    }

    if ($selTs == 3) $cogSpecsFkbCol = new CogSpecsTsFkbCol();
    if ($selTs == 2 ||  $selTs == 4) $cogSpecsFiMeta = new CogSpecsTsFiMeta();

    //---- Code Üretimi

    $fdrCodegen =  new Fdr();


    // FiColClass üretimi (C#, Java, Php)
    if ($selPhp == 1 || $selJava == 1 || $selCsharp == 1 || $selTs == 1) {
      //list($fdrData2, $arrDtoCodeGenPack) = self::genFiColClassesFromFile($fkbListData, $cogSpecs, $cogSpecsFiCol);
      $fdrCodegen = CgmCodegen::genFiColClass($fkbListData, $cogSpecs, $cogSpecsFiCol, $formTxEntity);
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
      $fdrCdgSql = CgmMssqlserver::actGenSqlCreateTable($fdrData->getFkbListInit());

      $data = [
        'fdrData' => $fdrCdgSql,
        'arrDtoCodeGenPack' => $fdrCdgSql->getFkbValue()->getParams(),
        'sbTxCodeGen' => $sbTxCodeGen,
        //'txCodeGenExtra' => $txCodeGenExtra,
        'formData' => $formObject ?? null
      ];

      // (codegen)[../Views/codegen.php]
      //return view('codegen', $data);
    }

    endExcelOkuma:

    // $data = [
    //   'fdrData' => $fdrData,
    //   'arrDtoCodeGenPack' => $arrDtoCodeGenPack,
    //   'sbTxCodeGen' => $sbTxCodeGen,
    //   'txCodeGenExtra' => $txCodeGenExtra,
    //   'formData' => $formObject ?? null
    // ];

    $fkbReturn = CgmApiUtil::genFkbReturn($fdrCodegen);

    // (codegen)[../Views/codegen.php] 
    return $this->respond( $fkbReturn->getArr(), 200);

    // return $this->response->setJSON([
    //     'status' => 'error',
    //     'message' => 'File upload failed'
    // ]);
  }
}
