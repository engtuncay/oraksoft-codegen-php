<?php

namespace Codegen\Modals;

use App\Controllers\CodegenCont;
use Codegen\OcgConfigs\OcgLogger;
//use Codegen\OcdConfig\OcgLogger;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCsvs\FiCsv;
use Engtuncay\Phputils8\FiDtos\Fdr;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiExcels\FiExcel;

/**
 * CgmCodegen : Code Generation Models
 */
class CgmCodegen
{
  /**
   * 
   * @param FkbList $fkbListData
   * @param ICogSpecs|null $iCogSpecs
   * @param ICogSpecsGenCol|null $iCogSpecsGenCol
   * @return Fdr
   */
  public static function genCodeColClass(FkbList $fkbList, ICogSpecs $iCogSpecs, ICogSpecsGenCol $iCogSpecsGenCol, int $inClassType = 0): Fdr
  {
    $fdrData = new Fdr();

    // $fdrData = self::convertFileToFkbList($sourceFile);
    // $fkbListData = $fdrData->getFkbListInit();
    //echo var_export($fkbListExcel, true);

    /** @var FiKeybean $fkbEntityToFkbList */
    //$fkbEntityToFkbList = CgmUtils::genFkbAsEntityToFkbList($fkbListData);
    $txVer = CodegenCont::getTxVer();

    //if ($fkbEntityToFkbList->has($txEntity)) {
    //$fkbList = $fkbEntityToFkbList->getValue($txEntity);
    $sbTxCodeGen1 = new FiStrbui();

    // addOption(elementById, "1", "FiCol Sınıf");
    // addOption(elementById, "2", "FiMeta By DML Template");
    // addOption(elementById, "3", "FkbCol Sınıf");
    // addOption(elementById, "4", "FiMeta Sınıf");

    if ($inClassType == 1) {
      $sbTxCodeGen1->append("// FiCol Class Generation - v$txVer \n");
      $sbTxCodeGen1->append(CgmFiColClass::actGenFiColClassByFkb($fkbList, $iCogSpecs, $iCogSpecsGenCol));
    }
    if ($inClassType == 2) {
      $sbTxCodeGen1->append("// FiMeta Class Generation (By Dml) - v$txVer \n");
      $sbTxCodeGen1->append(CgmFiMetaClassByDml::actGenFiMetaClassByFkbList($fkbList, $iCogSpecs, $iCogSpecsGenCol));
    }
    if ($inClassType == 3) {
      $sbTxCodeGen1->append("// FkbCol Class Generation - v$txVer \n");
      $sbTxCodeGen1->append(CgmFkbColClass::actGenClassByFkbList($fkbList, $iCogSpecs, $iCogSpecsGenCol));
    }
    if ($inClassType == 4) {
      $sbTxCodeGen1->append("// FiMeta Class Generation - v$txVer \n");
      $sbTxCodeGen1->append(CgmFiMetaClass::actGenFiMetaClassByFkb($fkbList, $iCogSpecs, $iCogSpecsGenCol));
    }

    $sbTxCodeGen1->append("\n");
    $fdrData->setTxValue($sbTxCodeGen1->toString());
    // } else {
    //   $fdrData->setTxValue("Entity not found: " . $txEntity);
    //   return $fdrData;
    // }

    // log_message('info', 'arrFkbListExcel' . print_r($fkbEntityToFkbList, true));

    // log_message('info', 'arrDtoCodeGen: ' . print_r($arrDtoCodeGen, true));
    // log_message('info', 'fdrData: ' . print_r($fdrData, true));

    //$fdrData->setTxValue()

    return $fdrData; // array($fdrData, $arrDtoCodeGen); //$fiExcel $fkbListData
  }

  /**
   * @param mixed $sourceFile
   * @return Fdr
   */
  public static function convertFileToFkbList(mixed $sourceFile): Fdr
  {
    $fileExtension = pathinfo($sourceFile->getClientPath(), PATHINFO_EXTENSION);

    if ($fileExtension == "csv") {
      $fiCsv = new FiCsv();
      //$fiCols = FicFiCol::GenTableCols();
      $fdrData = $fiCsv::readByFirstRowHeader($sourceFile);
      $fkbListData = $fdrData->getFkbListInit();
      return $fdrData;
    }

    if ($fileExtension == "xlsx" || $fileExtension == "xls") {
      OcgLogger::info("Excel dosyası algılandı, işleniyor...");
      $fiExcel = new FiExcel();
      $fdrData = $fiExcel::readExcelFileByFirstRowHeader($sourceFile);
      $fkbListData = $fdrData->getFkbListInit();
      return $fdrData;
    }

    $fdrData = new Fdr();
    $fdrData->setMessage("Geçersiz dosya formatı. Sadece .xlsx, .xls veya .csv dosyaları yükleyebilirsiniz.");
    $fdrData->setFkbList(new FkbList());

    return $fdrData; // Boş FkbList döndür
  }
}
