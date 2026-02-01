<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Engtuncay\Phputils8\FiCsvs\FiCsv;
use Engtuncay\Phputils8\FiDtos\Fdr;
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

  public function testForm()
  {
    $request = Services::request();
    $file = $request->getFile('excelFile');

    if ($file && $file->isValid() && !$file->hasMoved()) {
      $originalName = $file->getClientName();

      $fdr = $this->convertFileToFkbList($file);

      $satirBilgi = 'Satır Sayısı: ' . $fdr->getFkbListInit()->size();

      if ($fdr->getFkbListInit()->size() > 0) {
      }

      return $this->respond(['filename' => $originalName, 'satirBilgi' => $satirBilgi]);
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
