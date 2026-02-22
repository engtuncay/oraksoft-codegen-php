<?php

namespace Codegen\Modals;

use App\Controllers\CodegenCont;
use Codegen\FiMetas\App\FimCdgMssqlOpts;
use Codegen\FiMetas\App\FimOcgFieldTypes;
use Codegen\FiMetas\App\FimOcgForm;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCores\FiTemplate;
use Engtuncay\Phputils8\FiDtos\Fdr;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCol;
use Engtuncay\Phputils8\FiMetas\FimOcgSql;
use Engtuncay\Phputils8\FiMetas\FimOksCoding;

/**
 * SQL Server Code Generation Model
 */
class CgmMssqlserver
{

  public static function actGenCreateTableByEntity(FkbList $fkbList): Fdr
  {
    $fdrMain = new Fdr();

    $sbTxCodeGen1 = new FiStrbui();
    $txVer = CodegenCont::getTxVer();
    $sbTxCodeGen1->append("-- Sql Create Table Code Gen v$txVer\n");
    $sbTxCodeGen1->append(CgmMssqlserver::actGenSqlCreate($fkbList));
    $sbTxCodeGen1->append("\n");

    $fdrMain->setTxValue($sbTxCodeGen1->toString());

    return $fdrMain;
  }


  public static function actGenSqlCreate(FkbList $fkbList): string
  {
    $fkbFirstItem = $fkbList->get(0);

    $sbColDefs = new FiStrbui();

    /** @var FkbList $fkbList 
     *  @var FiKeybean $fkbItem
     */
    foreach ($fkbList as $fkbItem) {

      $fkbFieldName = $fkbItem->getValueByFiMeta(FimFiCol::fcTxFieldName());

      $sqlTypeDef = self::genSqlColTypeDef($fkbItem);

      $sbColDef = new FiStrbui();
      $sbColDef->append("$fkbFieldName $sqlTypeDef,\n");
      $sbColDefs->append($sbColDef->toString());
    }

    $fkbSqlCreateParam = new FiKeybean();
    $fkbSqlCreateParam->addFieldMeta(FimOcgSql::sfTableName(), $fkbFirstItem->getValueByFim(FimFiCol::fcTxEntityName()));
    // rtrim ile en sondaki , ve \n karakterleri silinir (!)
    $fkbSqlCreateParam->addFieldMeta(FimOcgSql::sfTableFields(), rtrim($sbColDefs->toString(), ",\n"));

    // Template Params (getTxKeyAsTemp is for Template Placeholders)
    $sfTableName = FimOcgSql::sfTableName()->getTxKeyAsTemp();
    $sfTableFields = FimOcgSql::sfTableFields()->getTxKeyAsTemp();

    $sqlTemplate = "
CREATE TABLE $sfTableName (
  $sfTableFields
)
";

    $txResult = FiTemplate::replaceParams($sqlTemplate, $fkbSqlCreateParam);


    // assignSqlTypeAndDef(listFields);

    // int index = 0;
    // for (FiField field : listFields) {

    //   // Sql Tipi Belirlenmeyenler için
    //   if (field.getSqlFieldDefinition() == null) {
    //     query.append("\n-- " + field.getfcTxFieldName() + " " + field.getClassNameSimple()
    //         + (field.getFcLnLength() != null ? " -- Length:" + field.getFcLnLength() : "")
    //         + (field.getFcLnPrecision() != null ? " -- Prec.:" + field.getFcLnPrecision() : "")
    //         + (field.getFcLnScale() != null ? "Scale :" + field.getFcLnScale() : ""));
    //     continue;
    //   }

    //   index++;
    //   if (index != 1) query.append("\n, ");
    //   query.append(field.getfcTxFieldName() + " " + field.getSqlFieldDefinition());


    return $txResult;
  }

  public static function genSqlColTypeDef(FiKeybean $fkbItem): string
  {
    $fkbType = $fkbItem->getValueByFiMeta(FimFiCol::fcTxFieldType());
    $fkbLength = $fkbItem->getValueByFiMeta(FimFiCol::fcLnLength());
    $fkbScale = $fkbItem->getValueByFiMeta(FimFiCol::fcLnScale());
    $fkbIdType = $fkbItem->getValueByFiMeta(FimFiCol::fcTxIdType());

    $sbTypeDef = new FiStrbui();

    if ($fkbType == FimOcgFieldTypes::string()->getTxKey() ) {

      if (FiString::isEmpty($fkbLength)) {
        $fkbLength = 50;
      }
      $sbTypeDef->append(" nvarchar($fkbLength)");
    }

    if ($fkbType == 'int') {
      $sbTypeDef->append(" int");
    }

    if ($fkbType == 'datetime') {
      $sbTypeDef->append(" datetime");
    }

    if ($fkbIdType == 'identity') {
      $sbTypeDef->append(" IDENTITY(1,1) NOT NULL PRIMARY KEY");
    }



    return $sbTypeDef->toString();
  }
}
