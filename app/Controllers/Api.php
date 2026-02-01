<?php

namespace App\Controllers;

use Codegen\Modals\CgmUtils;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;
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

  public function getEntities()
  {
    log_message('info', 'Api::getEntities called');

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
}
