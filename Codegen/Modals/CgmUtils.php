<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCores\FiText;
use Engtuncay\Phputils8\FiCores\FiwArray;
use Engtuncay\Phputils8\Log\FiLog;
use Engtuncay\Phputils8\FiDtos\FicList;
use Engtuncay\Phputils8\FiDtos\FiCol;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FiMeta;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiDtos\FmtList;
use Engtuncay\Phputils8\FiMetas\FimFiCol;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCsvs\FiCsv;
use Engtuncay\Phputils8\FiDtos\Fdr;
use Engtuncay\Phputils8\FiExcels\FiExcel;
use CodeIgniter\HTTP\Files\UploadedFile;
use Engtuncay\Phputils8\FiDtos\FimList;

class CgmUtils
{

  /**
   * UBOM Fkb yi FiCola çevir
   *
   * @param FkbList $fkbList
   * @return FicList
   */
  public static function getFiColListFromFkbList(FkbList $fkbList): FicList
  {
    $ficols = new FicList();

    /**
     *
     *
     * @var FiKeybean $fkbItem
     */
    foreach ($fkbList->getItems() as $fkbItem) {

      $ficol = new FiCol();

      $txFieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
      $ficol->fcTxFieldName = $txFieldName;

      $txHeader = $fkbItem->getValueByFiCol(FicFiCol::fcTxHeader());
      $ficol->fcTxHeader = $txHeader;

      $txEntName = $fkbItem->getValueByFiCol(FicFiCol::fcTxEntityName());
      $ficol->fcTxEntityName = $txEntName;

      $boTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoTransient());
      $ficol->fcBoTransient = $boTransient;

      $ficols->add($ficol);
    }

    return $ficols;
  }

  public static function getFiMetaListFromFkbList(FkbList $fkbList): FimList
  {

    $fmtList = new FimList();

    /**
     *
     *
     * @var FiKeybean $fkbItem
     */
    foreach ($fkbList->getItems() as $fkbItem) {

      $fiMeta = new FiMeta();

      $txFieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
      $fiMeta->setTxKey($txFieldName);

      $txEntityName = $fkbItem->getValueByFiCol(FicFiCol::fcTxEntityName());
      $fiMeta[FicFiCol::fcTxEntityName()->getFcTxFieldNameNtn()] = $txEntityName;

      // $txHeader = $fkbItem->getValueByFiCol(FicFiCol::fcTxHeader());
      // $fiMeta->fcTxHeader = $txHeader;

      $fmtList->add($fiMeta);
    }

    return $fmtList;
  }

  /**
   * key: txEntityName
   * value: FkbList (entity'ye ait alanlar)
   * 
   * Excel/Csv'den gelen listeyi entity'lere göre grupla
   * 
   * @param FkbList $fkbListData
   * @return FiKeybean (key: entityName, value: FkbList)
   */
  public static function genFkbAsEntityToFkbList(FkbList $fkbListData): FiKeybean
  {
    $fkbMap = new FiKeybean();

    //OcgLogger::info("Generating FkbMap from FkbListData:\n". print_r($fkbListData->getItems(), true) );
    //OcgLogger::info("Generating FkbMap from FkbListData , total item count:" . count($fkbListData->getItems()));  

    /** @var FiKeybean $fkbItem */
    foreach ($fkbListData as $fkbItem) {
      //OcgLogger::info("Processing FkbItem: " . print_r($fkbItem->getArr(), true));
      //OcgLogger::info("Processing FkbItem EntityName: " . $fkbItem->getFimValue(FimFiCol::fcTxEntityName()));
      //OcgLogger::info("Processing FkbItem EntityName: " . $fkbItem->getArr()[FimFiCol::fcTxEntityName()->getTxKey()]);
      //FiLog::$log?->debug(implode(":", $fkbItem->getArr()));
      $txEntityName = $fkbItem->getFimValue(FimFiCol::fcTxEntityName());
      //FiLog::$log?->debug("$txEntityName : ". $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName()));
      if ($txEntityName != null) {
        //OcgLogger::info("Adding FkbItem to entity:$txEntityName , field:" . $fkbItem->getFcTxFn());
        $txEntityName = trim($txEntityName);
        $fkbMap->putInFkbList($txEntityName, $fkbItem);
      }
    }

    //FiLog::$log?->debug(FiText::textArrayFkbList($fkbMap->getArr()));

    return $fkbMap; //->getArr();
  }

  public static function genFkbAsEntityList(FkbList $fkbListData): FiwArray
  {
    $farData = new FiwArray();

    //OcgLogger::info("Generating FkbMap from FkbListData:\n". print_r($fkbListData->getItems(), true) );
    //OcgLogger::info("Generating FkbMap from FkbListData , total item count:" . count($fkbListData->getItems()));  

    /** @var FiKeybean $fkbItem */
    foreach ($fkbListData as $fkbItem) {
      //OcgLogger::info("Processing FkbItem: " . print_r($fkbItem->getArr(), true));
      //OcgLogger::info("Processing FkbItem EntityName: " . $fkbItem->getFimValue(FimFiCol::fcTxEntityName()));
      //OcgLogger::info("Processing FkbItem EntityName: " . $fkbItem->getArr()[FimFiCol::fcTxEntityName()->getTxKey()]);
      //FiLog::$log?->debug(implode(":", $fkbItem->getArr()));
      $txEntityName = $fkbItem->getFimValue(FimFiCol::fcTxEntityName());
      //FiLog::$log?->debug("$txEntityName : ". $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName()));
      if ($txEntityName != null) {
        //OcgLogger::info("Adding FkbItem to entity:$txEntityName , field:" . $fkbItem->getFcTxFn());
        if (!$farData->existValue(trim($txEntityName))) {
          $farData->addValue(trim($txEntityName));
        }
      }
    }

    //FiLog::$log?->debug(FiText::textArrayFkbList($farData->getArr()));

    return $farData; //->getArr();
  }

  /**
   * Converts a field name to standard method name format.
   *
   * @param string|null $fieldName
   * @return string
   */
  public static function convertToLowerCamelCase(string|null $fieldName): string
  {
    // Başlangıçta eğer fieldName boşsa direkt döndür
    if (FiString::isEmpty($fieldName)) return "";

    if (!FiString::hasLowercaseLetter($fieldName)) {
      $fieldName = strtolower($fieldName);
      return lcfirst($fieldName);
    } else {

      $characters = str_split($fieldName); // Dizeyi karakterlere böl
      $result = ''; // Sonuç dizesi oluştur
      $length = count($characters);

      for ($i = 0; $i < $length; $i++) {
        // İlk harf her zaman küçük kalacak
        if ($i === 0) {
          $result .= strtolower($characters[$i]);
          $characters[$i] = strtolower($characters[$i]);
          continue;
        }

        // Kendinden önceki küçükse, aynen ekle
        if (ctype_lower($characters[$i - 1])) { // && ctype_lower($characters[$i])
          $result .= $characters[$i];
        } // Kendinden önceki büyükse küçült
        elseif (ctype_upper($characters[$i - 1])) {
          $result .= strtolower($characters[$i]);
        } elseif ($characters[$i - 1] == '_') {
          $result .= strtolower($characters[$i]);
        } else {  // Kendinden önceki sayı vs (_ dışında karakterse) ise büyült
          $result .= strtoupper($characters[$i]);
        }
      }

      return $result;
    }
  }

  /**
   * @param mixed $sourceFile
   * @return Fdr
   */
  public static function convertFileToFkbList(mixed $sourceFile): Fdr
  {
    // Tip kontrolü: tercihen `UploadedFile` olmalı, diğer durumlarda `getClientPath()` metodu aranır
    if (!($sourceFile instanceof UploadedFile)) {
      $fdrData = new Fdr();
      $fdrData->setMessage('Geçersiz dosya nesnesi. Beklenen UploadedFile veya getClientPath() metodu olan bir nesne.');
      $fdrData->setFkbList(new FkbList());
      return $fdrData;
    }

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
   * CLI parametrelerini key-value array'e dönüştür
   * "--cmd excel--table VIEWMUSTERI" => ['cmd' => 'excel', 'table' => 'VIEWMUSTERI']
   *
   * @param string $argv
   * @return array
   */
  public static function parseCliParameters(string $argv): array
  {
    $params = [];
    $parts = explode('--', trim($argv));

    foreach ($parts as $part) {
      $part = trim($part);
      if (empty($part)) {
        continue;
      }

      $keyValue = explode(' ', $part, 2);
      $key = $keyValue[0];
      $value = isset($keyValue[1]) ? trim($keyValue[1]) : true;

      $params[$key] = $value;
    }

    return $params;
  }
}
