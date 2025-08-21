<?php

namespace App\codegen\Modals;

use Codegen\Modals\CgmFiColUtil;
use Codegen\Modals\DtoCodeGen;
use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\Core\FiString;
use Engtuncay\Phputils8\Core\FiTemplate;
use Engtuncay\Phputils8\FiDto\Fdr;
use Engtuncay\Phputils8\FiDto\FiKeybean;
use Engtuncay\Phputils8\FiDto\FkbList;
use Engtuncay\Phputils8\FiMeta\FimFiCol;
use Engtuncay\Phputils8\FiMeta\FimOksCoding;

class CgmCdgSqlserver
{
  public static function actGenSqlCreateTable(FkbList $fkbList): Fdr
  {
    $fdrMain = new Fdr();

    /** @var FkbList[] $arrFkbListByEntity */
    $arrFkbListByEntity = CgmFiColUtil::mapEntityToFkbList($fkbList);

    //log_message('info', 'arrFkbListExcel' . print_r($arrFkbListByEntity, true));
    $txIdPref = "sql";
    $lnForIndex = 0;
    $arrDtoCodeGen = [];

    $fkbDtoCodeGen = new FiKeybean();

    foreach ($arrFkbListByEntity as $fkbList) {
      $lnForIndex++;
      $dtoCodeGen = new DtoCodeGen();
      $sbTxCodeGen1 = new FiStrbui();
      $sbTxCodeGen1->append("-- Sql Create Table Code Gen v1\n");
      $sbTxCodeGen1->append(CgmCdgSqlserver::actGenSqlCreate($fkbList));
      $sbTxCodeGen1->append("\n");
      $dtoCodeGen->setSbCodeGen($sbTxCodeGen1);
      $dtoCodeGen->setDcgId($txIdPref . $lnForIndex);

      $arrDtoCodeGen[] = $dtoCodeGen;

      $fkbDtoCodeGen->addValue($dtoCodeGen);
    }

    $fdrMain->setRefValue($arrDtoCodeGen);
    $fdrMain->setFkbValue($fkbDtoCodeGen);

    return $fdrMain;
  }

  public static function actGenSqlCreate(FkbList $fkbList): string
  {
    $fkbFirstItem = $fkbList->get(0);

    // Tpr: Template Param
    $tprTableName = FimOksCoding::oscTxTableName()->getTxKeyAsTemp();
    $tprColumns = FimOksCoding::oscTxTableFields()->getTxKeyAsTemp();

    $sbColDefs = new FiStrbui();

    /** @var FkbList $fkbList 
     *  @var FiKeybean $fkbItem
     */
    foreach ($fkbList as $fkbItem) {

      $fkbFieldName = $fkbItem->getValueByFiMeta(FimFiCol::ofcTxFieldName());

      $sqlTypeDef = self::genSqlColTypeDef($fkbItem);

      $sbColDef = new FiStrbui();
      $sbColDef->append($fkbFieldName . ' ' . $sqlTypeDef . ",\n");

      //$sbColDef->append($fkbFieldName . ' ');

      // $sqlTemplate = str_replace(
      //   FimOksCoding::oscTxTableFields()->getTxKeyAsTemp(),
      //   $field->getOfcTxFieldName() . ' ' . $field->getSqlFieldDefinition(),
      //   $sqlTemplate
      // );
      $sbColDefs->append($sbColDef->toString());
    }

    $fkbSqlCreateParam = new FiKeybean();
    $fkbSqlCreateParam->addFieldMeta(FimOksCoding::oscTxTableName(), $fkbFirstItem->getValueByFiMeta(FimFiCol::ofcTxEntityName()));
    $fkbSqlCreateParam->addFieldMeta(FimOksCoding::oscTxTableFields(), rtrim($sbColDefs->toString(), ",\n"));

    $sqlTemplate = <<<EOD
CREATE TABLE $tprTableName (
  $tprColumns
)
EOD;

    $txResult = FiTemplate::replaceParams($sqlTemplate, $fkbSqlCreateParam);


    // assignSqlTypeAndDef(listFields);

    // int index = 0;
    // for (FiField field : listFields) {

    //   // Sql Tipi Belirlenmeyenler için
    //   if (field.getSqlFieldDefinition() == null) {
    //     query.append("\n-- " + field.getOfcTxFieldName() + " " + field.getClassNameSimple()
    //         + (field.getOfcLnLength() != null ? " -- Length:" + field.getOfcLnLength() : "")
    //         + (field.getOfcLnPrecision() != null ? " -- Prec.:" + field.getOfcLnPrecision() : "")
    //         + (field.getOfcLnScale() != null ? "Scale :" + field.getOfcLnScale() : ""));
    //     continue;
    //   }

    //   index++;
    //   if (index != 1) query.append("\n, ");
    //   query.append(field.getOfcTxFieldName() + " " + field.getSqlFieldDefinition());


    return $txResult;
  }

  public static function genSqlColTypeDef(FiKeybean $fkbItem): string
  {
    $fkbType = $fkbItem->getValueByFiMeta(FimFiCol::ofcTxFieldType());
    $fkbLength = $fkbItem->getValueByFiMeta(FimFiCol::ofcLnLength());

    $sbTypeDef = new FiStrbui();

    if($fkbType == 'string'){
      
      if(FiString::isEmpty($fkbLength)){
        $fkbLength = 50;
      } 
      $sbTypeDef->append("varchar($fkbLength)");
    }

    if($fkbType == 'int'){
      $sbTypeDef->append("int");
    }

    if($fkbType == 'datetime'){
      $sbTypeDef->append("datetime");
    }

    return $sbTypeDef->toString();
  }

}
