<?php

namespace codegen\modals;

use codegen\ficols\FicFiCol;
use Engtuncay\Phputils8\Core\FiArray;
use Engtuncay\Phputils8\Core\FiwArray;
use Engtuncay\Phputils8\Meta\FclList;
use Engtuncay\Phputils8\Meta\FiCol;
use Engtuncay\Phputils8\Meta\FiKeybean;
use Engtuncay\Phputils8\Meta\FiMeta;
use Engtuncay\Phputils8\Meta\FkbList;
use Engtuncay\Phputils8\Meta\FmtList;

class CgmFiColUtil
{

  /**
   * UBOM Fkb yi FiCola çevir
   *
   * @param FkbList $fkbList
   * @return FclList
   */
  public static function getFiColListFromFkbList(FkbList $fkbList): FclList
  {
    $ficols = new FclList();

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
   * @return FiwArray<FiKeybean>
   */
  public static function arrEntityFkbExcel(FkbList $fkbListExcel): array
  {
    /** @var FiwArray<FiKeybean> $arrFkbEntity */
    $arrFkbEntity = new FiwArray();

    /** @var FiKeybean $fkbItem */
    foreach ($fkbListExcel->getItems() as $fkbItem) {

      $txEntityName = $fkbItem->getValueByFiCol(FicFiCol::ofcTxEntityName());

      if($txEntityName!=null){
        if (!$arrFkbEntity->existKey($txEntityName)) {
          $arrFkbEntity->put($txEntityName, []);
        }
        $arrFkbEntity->putInArray($txEntityName, $fkbItem);
      }
    }

    return $arrFkbEntity->getArrValue();
  }
}