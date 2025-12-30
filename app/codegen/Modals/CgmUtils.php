<?php

namespace Codegen\Modals;

use Codegen\ficols\FicFiCol;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCores\FiText;
use Engtuncay\Phputils8\FiCores\FiwArray;
use Engtuncay\Phputils8\Log\FiLog;
use Engtuncay\Phputils8\FiDto\FicList;
use Engtuncay\Phputils8\FiDto\FiCol;
use Engtuncay\Phputils8\FiDto\FiKeybean;
use Engtuncay\Phputils8\FiDto\FiMeta;
use Engtuncay\Phputils8\FiDto\FkbList;
use Engtuncay\Phputils8\FiDto\FmtList;
use Engtuncay\Phputils8\FiMeta\FimFiCol;
use Codegen\OcdConfig\OcgLogger;

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

      $txFieldName = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldName());
      $ficol->ofcTxFieldName = $txFieldName;

      $txHeader = $fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader());
      $ficol->ofcTxHeader = $txHeader;

      $txEntName = $fkbItem->getValueByFiCol(FicFiCol::ofcTxEntityName());
      $ficol->ofcTxEntityName = $txEntName;

      $boTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoTransient());
      $ficol->ofcBoTransient = $boTransient;

      $ficols->add($ficol);
    }

    return $ficols;
  }

  public static function getFiMetaListFromFkbList(FkbList $fkbList): FmtList
  {

    $fmtList = new FmtList();

    /**
     *
     *
     * @var FiKeybean $fkbItem
     */
    foreach ($fkbList->getItems() as $fkbItem) {

      $fiMeta = new FiMeta();

      $txFieldName = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldName());
      $fiMeta->ofmTxKey = $txFieldName;

      $txEntityName = $fkbItem->getValueByFiCol(FicFiCol::ofcTxEntityName());
      $fiMeta[FicFiCol::ofcTxEntityName()->getOfcTxFieldNameNtn()] = $txEntityName;

      // $txHeader = $fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader());
      // $fiMeta->ofcTxHeader = $txHeader;

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
   * @return array
   */
  public static function genFkbAsEntityToFkbList(FkbList $fkbListData): FiKeybean
  {
    $fkbMap = new FiKeybean();

    //OcgLogger::info("Generating FkbMap from FkbListData:\n". print_r($fkbListData->getItems(), true) );
    //OcgLogger::info("Generating FkbMap from FkbListData , total item count:" . count($fkbListData->getItems()));  

    /** @var FiKeybean $fkbItem */
    foreach ($fkbListData as $fkbItem) {
      //OcgLogger::info("Processing FkbItem: " . print_r($fkbItem->getArr(), true));
      //OcgLogger::info("Processing FkbItem EntityName: " . $fkbItem->getFimValue(FimFiCol::ofcTxEntityName()));
      //OcgLogger::info("Processing FkbItem EntityName: " . $fkbItem->getArr()[FimFiCol::ofcTxEntityName()->getTxKey()]);
      //FiLog::$log?->debug(implode(":", $fkbItem->getArr()));
      $txEntityName = $fkbItem->getFimValue(FimFiCol::ofcTxEntityName());
      //FiLog::$log?->debug("$txEntityName : ". $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldName()));
      if ($txEntityName != null) {
        //OcgLogger::info("Adding FkbItem to entity:$txEntityName , field:" . $fkbItem->getOfcTxFn());
        $txEntityName = trim($txEntityName);
        $fkbMap->putInFkbList($txEntityName, $fkbItem);
      }
    }

    //FiLog::$log?->debug(FiText::textArrayFkbList($fkbMap->getArr()));

    return $fkbMap; //->getArr();
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
        else if (ctype_upper($characters[$i - 1])) {
          $result .= strtolower($characters[$i]);
        } else if ($characters[$i - 1] == '_') {
          $result .= strtolower($characters[$i]);
        } else {  // Kendinden önceki sayı vs (_ dışında karakterse) ise büyült
          $result .= strtoupper($characters[$i]);
        }
      }

      return $result;
    }
  }
}
