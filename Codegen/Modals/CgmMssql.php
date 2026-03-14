<?php

namespace Codegen\Modals;

use Codegen\FiMetas\App\FimOcgFieldTypes;
use Engtuncay\Phputils8\FiCores\FiBool;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCores\FiTemplate;
use Engtuncay\Phputils8\FiDtos\Fdr;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCol;
use Engtuncay\Phputils8\FiMetas\FimOcgSql;


/**
 * SQL Server Code Generation Model
 */
class CgmMssql
{

  public static function actGenCreateTableByEntity(FkbList $fkbList): Fdr
  {
    $fdrMain = new Fdr();

    $sbTxCodeGen1 = new FiStrbui();
    $txVer = CgmApiUtil::getTxVer();
    $sbTxCodeGen1->append("-- Sql Create Table Code Gen v$txVer\n");
    $sbTxCodeGen1->append(CgmMssql::actGenSqlCreate($fkbList));
    $sbTxCodeGen1->append("\n");

    $fdrMain->setTxValue($sbTxCodeGen1->toString());

    return $fdrMain;
  }

  public static function actGenAlterTableByEntity(FkbList $fkbList): Fdr
  {
    $fdrMain = new Fdr();

    $sbTxCodeGen = new FiStrbui();
    $txVer = CgmApiUtil::getTxVer();
    $sbTxCodeGen->append("-- Sql Alter Table Code Gen v$txVer\n");
    // $sbTxCodeGen1->append(CgmMssql::actGenSqlAlter($fkbList));

    $sfTxTableName = $fkbList->get(0)->getFimValue(FimFiCol::fcTxEntityName());

    // --ALTER TABLE STOK_HAREKETLERI ADD sthTxGuid nvarchar(40)
    
    /** @var FiKeybean $fkbItem */
    foreach ($fkbList as $fkbItem) {

      $sfTxFieldDef = self::genSqlColTypeDef($fkbItem);
      $sfTxFieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());
      
      $txAlterQueryTemp = "ALTER TABLE $sfTxTableName ADD $sfTxFieldName $sfTxFieldDef;";
      $sbTxCodeGen->append($txAlterQueryTemp);
      $sbTxCodeGen->append("\n");

    }
    $sbTxCodeGen->append("\n");

    $fdrMain->setBoResult(true);
    $fdrMain->setTxValue($sbTxCodeGen->toString());
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

      $fcTxFieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());
      $fcBoTransient = $fkbItem->getFimAsBool(FimFiCol::fcBoTransient());
      $fcTxIdType = $fkbItem->getFimValue(FimFiCol::fcTxIdType());

      // Transient Alanlar SQL Create Table'da yer almaz
      if (FiBool::isTrue($fcBoTransient)) {
        continue;
      }

      $sqlTypeDef = self::genSqlColTypeDef($fkbItem);

      $sbColDef = new FiStrbui();
      $sbColDef->append("$fcTxFieldName $sqlTypeDef,\n");
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

  /**
   * sqldeki sütunun field type ve diger özellikleri tanımlanır
   *
   * @param FiKeybean $fkbItem
   * @return string
   */
  public static function genSqlColTypeDef(FiKeybean $fkbItem): string
  {
    $fcTxFieldType = $fkbItem->getFimValue(FimFiCol::fcTxFieldType());
    $fcLnLength = $fkbItem->getFimValue(FimFiCol::fcLnLength());
    $fcLnScale = $fkbItem->getFimValue(FimFiCol::fcLnScale());
    $fcTxIdType = $fkbItem->getFimValue(FimFiCol::fcTxIdType());

    $sbTypeDef = new FiStrbui();

    if (
      $fcTxFieldType == FimOcgFieldTypes::string()->getTxKey()
      || $fcTxFieldType == FimOcgFieldTypes::nvarchar()->getTxKey()
    ) {

      if (FiString::isEmpty($fcLnLength)) {
        $fcLnLength = 50;
      }
      $sbTypeDef->append(" nvarchar($fcLnLength)");
    }

    if ($fcTxFieldType == FimOcgFieldTypes::varchar()->getTxKey()) {

      if (FiString::isEmpty($fcLnLength)) {
        $fcLnLength = 50;
      }
      $sbTypeDef->append(" varchar($fcLnLength)");
    }

    if (
      $fcTxFieldType == FimOcgFieldTypes::decimal()->getTxKey()
      || $fcTxFieldType == FimOcgFieldTypes::double()->getTxKey()
    ) {

      if (FiString::isEmpty($fcLnLength)) {
        $fcLnLength = 50;
      }

      if (FiString::isEmpty($fcLnScale)) {
        $fcLnScale = 2;
      }

      $sbTypeDef->append(" decimal($fcLnLength,$fcLnScale)");
    }

    if ($fcTxFieldType == FimOcgFieldTypes::int()->getTxKey()) {
      $sbTypeDef->append(" int");
    }

    if ($fcTxFieldType == FimOcgFieldTypes::tinyint()->getTxKey()) {
      $sbTypeDef->append(" tinyint");
    }

    if ($fcTxFieldType == FimOcgFieldTypes::bit()->getTxKey()) {
      $sbTypeDef->append(" bit");
    }

    // URREV
    if ($fcTxFieldType == FimOcgFieldTypes::date()->getTxKey()) {
      $sbTypeDef->append(" datetime");
    }

    if ($fcTxFieldType == FimOcgFieldTypes::datetimeoffset()->getTxKey()) {
      $sbTypeDef->append(" datetimeoffset(0)");
    }

    if ($fcTxIdType == 'identity' || $fcTxIdType == 'auto') {
      $sbTypeDef->append(" IDENTITY(1,1) NOT NULL PRIMARY KEY");
    }

    if ($fcTxIdType == 'user') {
      $sbTypeDef->append(" NOT NULL PRIMARY KEY");
      //$sbTypeDef->append(" UNIQUEIDENTIFIER NOT NULL PRIMARY KEY DEFAULT NEWID()");
    }


    return $sbTypeDef->toString();
  }
}
