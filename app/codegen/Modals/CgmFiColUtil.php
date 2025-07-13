<?php

namespace Codegen\Modals;

use Codegen\ficols\FicFiCol;
use Engtuncay\Phputils8\Core\FiArray;
use Engtuncay\Phputils8\Core\FiText;
use Engtuncay\Phputils8\Core\FiwArray;
use Engtuncay\Phputils8\Log\FiLog;
use Engtuncay\Phputils8\Meta\FicList;
use Engtuncay\Phputils8\Meta\FiCol;
use Engtuncay\Phputils8\Meta\FiKeybean;
use Engtuncay\Phputils8\Meta\FiMeta;
use Engtuncay\Phputils8\Meta\FkbList;
use Engtuncay\Phputils8\Meta\FmtList;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;

class CgmFiColUtil
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
      $fiMeta->txKey = $txFieldName;

      $txEntityName = $fkbItem->getValueByFiCol(FicFiCol::ofcTxEntityName());
      $fiMeta[FicFiCol::ofcTxEntityName()->getOfcTxFieldNameNtn()] = $txEntityName;

//      $txHeader = $fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader());
//      $fiMeta->ofcTxHeader = $txHeader;

      $fmtList->add($fiMeta);
    }

    return $fmtList;

  }

  /**
   * @param FkbList $fkbListExcel
   * @return array
   */
  public static function arrEntityFkbExcel(FkbList $fkbListExcel): array
  {
    /** @var FiwArray<FkbList> $fwarFkbEntity */$fwarFkbEntity = new FiwArray();

    /** @var FiKeybean $fkbItem */
    foreach ($fkbListExcel as $fkbItem) {

      //FiLog::$log?->debug(implode(":", $fkbItem->getArr()));

      $txEntityName = $fkbItem->getValueByFiCol(FicFiCol::ofcTxEntityName());
      //FiLog::$log?->debug("$txEntityName : ". $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldName()));
      if ($txEntityName != null) {
        if (!$fwarFkbEntity->existKey($txEntityName)) {
          $fwarFkbEntity->put($txEntityName, new FkbList());
        }
        $fwarFkbEntity->putInFkbList($txEntityName, $fkbItem);
      }
    }

    //FiLog::$log?->debug(FiText::textArrayFkbList($fwarFkbEntity->getArrValue()));

    return $fwarFkbEntity->getArrValue();
  }
}