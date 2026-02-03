<?php

namespace Codegen\Modals;

use App\Controllers\CodegenCont;
use Codegen\OcdConfig\OcgLogger;
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
   * @param CogSpecsPhp|null $iCogSpecs
   * @param CogSpecsPhpFiCol|null $iCogSpecsFiCol
   * @return Fdr
   */
  public static function genFiColClass(FkbList $fkbListData, ICogSpecs $iCogSpecs, ICogSpecsFiCol $iCogSpecsFiCol, string $txEntity =''): Fdr
  {
    $fdrData = new Fdr();

    // $fdrData = self::convertFileToFkbList($sourceFile);
    // $fkbListData = $fdrData->getFkbListInit();
    //echo var_export($fkbListExcel, true);

    /** @var FiKeybean $fkbEntityToFkbList */
    $fkbEntityToFkbList = CgmUtils::genFkbAsEntityToFkbList($fkbListData);

    if($fkbEntityToFkbList->has($txEntity)){
      $fkbList = $fkbEntityToFkbList->getValue($txEntity);
      $sbTxCodeGen1 = new FiStrbui();
      $sbTxCodeGen1->append("// FiCol Class Generation v1\n");
      $sbTxCodeGen1->append(CgmFiColClass::actGenFiColClassByFkb($fkbList, $iCogSpecs, $iCogSpecsFiCol));
      $sbTxCodeGen1->append("\n");
      $fdrData->setTxValue($sbTxCodeGen1->toString());

    } else {
      $fdrData->setTxValue("Entity not found: " . $txEntity);
      return $fdrData;
    }

    // log_message('info', 'arrFkbListExcel' . print_r($fkbEntityToFkbList, true));
    
    // log_message('info', 'arrDtoCodeGen: ' . print_r($arrDtoCodeGen, true));
    // log_message('info', 'fdrData: ' . print_r($fdrData, true));

    //$fdrData->setTxValue()

    return $fdrData; // array($fdrData, $arrDtoCodeGen); //$fiExcel $fkbListData
  }

  /**
   * 
   * @param mixed $sourceFile
   * @param ICogSpecs $iCogSpecs
   * @return array
   */
  public function genFkbColClassesFromFile(FkbList $fkbListData, ICogSpecs $iCogSpecs, ICogSpecsFkbCol $iCogSpecsFkbCol): array
  {

    $fdrData = new Fdr();
    // $fdrData = self::convertFileToFkbList($sourceFile);
    // $fkbListData = $fdrData->getFkbListInit();
    
    // OcgLogger::info("fkbListData:" . print_r($fkbListData->getItems(), true));

    /** @var FiKeybean $fkbEntityToFkbList */
    $fkbEntityToFkbList = CgmUtils::genFkbAsEntityToFkbList($fkbListData);

    //log_message('info', 'arrFkbListExcel' . print_r($fkbEntityToFkbList, true));
    $txIdPref = "codegen";
    $lnForIndex = 0;

    OcgLogger::info("fkblist count:" . count($fkbListData->getAsMultiArray()));
    OcgLogger::info("fkbEntityToFkbList count:" . count($fkbEntityToFkbList->getParams()));
    // fkbList, Excelde bir entity için tanımlanmış alanların listesi

    $arrDtoCodeGen =  [];
    $txVer = CodegenCont::getTxVer();

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
  public static function convertFileToFkbList(mixed $sourceFile): Fdr
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
  public function genFiMetaClassesFromFile(FkbList $fkbListData, ICogSpecs $iCogSpecs, ICogSpecsFiMeta $iCogSpecsFiMeta): array
  {
    $fdrData = new Fdr();
    //array|string $fileExtension,

    // $fdrData = self::convertFileToFkbList($sourceFile);
    // $fkbListData = $fdrData->getFkbListInit();

    //echo var_export($fkbListExcel, true);

    /** @var FkbList[] $mapEntityToFkbList */
    $mapEntityToFkbList = CgmUtils::genFkbAsEntityToFkbList($fkbListData);

    log_message('info', 'arrFkbListExcel' . print_r($mapEntityToFkbList, true));
    $txIdPref = "codegen";
    $lnForIndex = 0;

    $txVer = CodegenCont::getTxVer();

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
  public static function genFiMetaClassByDmlTemplateFromFile(FkbList $fkbListData, ICogSpecs $iCogSpecs, ICogSpecsFiMeta $iSpecsFiMeta): array
  {
    $fdrData = new Fdr();
    //array|string $fileExtension,

    // $fdrData = self::convertFileToFkbList($sourceFile);
    // $fkbListData = $fdrData->getFkbListInit();

    //echo var_export($fkbListExcel, true);

    /** @var FkbList[] $mapEntityToFkbList */
    $mapEntityToFkbList = CgmUtils::genFkbAsEntityToFkbList($fkbListData);

    //log_message('info', 'arrFkbListExcel' . print_r($mapEntityToFkbList, true));
    $txIdPref = "codegen";
    $lnForIndex = 0;

    $txVer = CodegenCont::getTxVer();

    foreach ($mapEntityToFkbList as $entity => $fkbList) {
      $lnForIndex++;
      $dtoCodeGen = new DtoCodeGen();
      $sbTxCodeGen1 = new FiStrbui();
      $sbTxCodeGen1->append("// Codegen " . $txVer . "\n");
      $sbTxCodeGen1->append(CgmFiMetaClassByDmlTemplate::actGenFiMetaClassByFkbList($fkbList, $iCogSpecs, $iSpecsFiMeta));
      $sbTxCodeGen1->append("\n");
      $dtoCodeGen->setSbCodeGen($sbTxCodeGen1);
      $dtoCodeGen->setDcgId($txIdPref . $lnForIndex);
      $arrDtoCodeGen[] = $dtoCodeGen;
    }

    //log_message('info', 'arrDtoCodeGen: ' . print_r($arrDtoCodeGen, true));
    //log_message('info', 'fdrData: ' . print_r($fdrData, true));

    return array($fdrData, $arrDtoCodeGen); //$fiExcel $fkbListData
  }
}
