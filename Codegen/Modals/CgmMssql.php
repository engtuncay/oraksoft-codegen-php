<?php

namespace Codegen\Modals;

use Codegen\FiMetas\App\FimOcgFieldTypes;
use Codegen\FiMetas\App\FimQcColClassTempAreas;
use Codegen\OcgConfigs\OcgLogger;
use Engtuncay\Phputils8\FiCols\FicValue;
use Engtuncay\Phputils8\FiCores\FiBool;
use Engtuncay\Phputils8\FiCores\FiCollection;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCores\FiTemplate;
use Engtuncay\Phputils8\FiDbs\FiDbTypes;
use Engtuncay\Phputils8\FiDtos\Fdr;
use Engtuncay\Phputils8\FiDtos\Fkb;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCol;
use Engtuncay\Phputils8\FiMetas\FimOcgSql;
use Engtuncay\Phputils8\FiMetas\FimQcFieldType;
use Engtuncay\Phputils8\FiMetas\FimQcSpecFields;
use Engtuncay\Phputils8\FiMetas\FimQcSql;

/**
 * SQL Server Code Generation Model
 */
class CgmMssql
{

  public static function actGenCreateTableByEntity(FkbList $fklEntity): Fdr
  {
    $fdrMain = new Fdr();

    $sbTxCodeGen1 = new FiStrbui();
    $txVer = CgmApiUtil::getTxVer();
    $sbTxCodeGen1->append("-- Sql Create Table Code Gen v$txVer\n");
    $sbTxCodeGen1->append(CgmMssql::actGenSqlCreate($fklEntity));
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

    /** @var Fkb $fkbItem */
    foreach ($fkbList as $fkbItem) {

      $sfTxFieldDef = self::formSqlColTypeDef($fkbItem);
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
    $sbUniqDefs = new FiStrbui();

    // Template Placeholders (getTxKeyAsTemp is for Template Placeholders)
    $phsfTableName = FimOcgSql::sfTableName()->getTxKeyAsPlaceHolder();
    $phsfTableFields = FimOcgSql::sfTableFields()->getTxKeyAsPlaceHolder();
    $phsfIdentifName = FimQcSql::sfIdentifName()->getTxKeyAsPlaceHolder();
    $phsfTxFields = FimQcSql::sfTxFields()->getTxKeyAsPlaceHolder();

    // $fkbList map çevir
    $fkbFieldsAll = FiCollection::toFkb($fkbList, function (Fkb $item) {
      return $item->getFimValue(FimFiCol::fcTxFieldName());
    });

    $fkbFieldsByTxId = FiCollection::toFkb($fkbList, function (Fkb $item) {
      return $item->getFimValue(FimFiCol::fcTxId());
    });

    //OcgLogger::debug("Fields:" . json_encode($fkbFieldsAll));

    /** @var Fkb $fkbTableName */
    $fkbTableName = $fkbFieldsAll->getFimValue(FimQcSpecFields::qcfTxSqTableName());

    /** @var FkbList $fkbList 
     *  @var Fkb $fkbItem
     */
    foreach ($fkbList as $fkbItem) {

      $fcTxFieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());
      $fcBoTransient = $fkbItem->getFimAsBool(FimFiCol::fcBoTransient());
      $fcTxIdType = $fkbItem->getFimValue(FimFiCol::fcTxIdType());
      $fcTxFieldType = $fkbItem->getFimValue(FimFiCol::fcTxFieldType());
      $fcTxHeader = $fkbItem->getFimValue(FimFiCol::fcTxHeader());

      //OcgLogger::debug("CgmMssql::actGenSqlCreate - Field: $fcTxFieldName, TypeDef: $fcTxFieldType");

      // Unique Alanlar için
      if ($fcTxFieldType == FimQcFieldType::sq_unique()->getTxValue()) {
        self::prepUniqueFieldsDef($sbUniqDefs, $fkbTableName, $fkbItem, $fkbFieldsByTxId);
      }

      // Transient Alanlar SQL Create Table'da yer almaz
      if (FiBool::isTrue($fcBoTransient)) {
        continue;
      }

      // URFIX burada özel fielNameleri atlaması yapılabilir

      $sqlTypeDef = self::formSqlColTypeDef($fkbItem);

      $sbColDef = new FiStrbui();
      $sbColDef->append("$fcTxFieldName $sqlTypeDef,\n");
      $sbColDefs->append($sbColDef->toString());
    }

    $fkbSqlCreateParam = new Fkb();
    $fkbSqlCreateParam->addFieldMeta(FimOcgSql::sfTableName(), $fkbFirstItem->getFimValue(FimFiCol::fcTxEntityName()));
    // rtrim ile en sondaki , ve \n karakterleri silinir (!)
    $fkbSqlCreateParam->addFieldMeta(FimOcgSql::sfTableFields(), rtrim($sbColDefs->toString(), ",\n"));


    $sqlTemplate = "
CREATE TABLE $phsfTableName (
  $phsfTableFields
)
";

    $txResult = FiTemplate::replaceParams($sqlTemplate, $fkbSqlCreateParam);

    $sbResult = new FiStrbui();
    $sbResult->append($txResult)->append("\n");

    if (!FiString::isEmpty($sbUniqDefs->toString())) {
      $sbResult->append("\n-- Unique Constraints\n")->append($sbUniqDefs->toString());
    }

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

    return $sbResult->toString();
  }

  private static function prepUniqueFieldsDef(FiStrbui $sbUniqDefs, Fkb $fkbTableName, Fkb $fkbItem, Fkb $fkbFieldsByTxId)
  {
    $phsfTableName = FimOcgSql::sfTableName()->getTxKeyAsPlaceHolder();
    //$phsfTableFields = FimOcgSql::sfTableFields()->getTxKeyAsPlaceHolder();
    $phsfIdentifName = FimQcSql::sfIdentifName()->getTxKeyAsPlaceHolder();
    $phsfTxFields = FimQcSql::sfTxFields()->getTxKeyAsPlaceHolder();

    $fcTxFieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());

    OcgLogger::debug("CgmMssql::actGenSqlCreate - Unique Field Detected: $fcTxFieldName");
    
    $template = "ALTER TABLE {$phsfTableName} 
ADD CONSTRAINT {$phsfIdentifName} 
UNIQUE ({$phsfTxFields});";
    
    $fkbUniqueCons = new Fkb();
    $fkbUniqueCons->addFim(FimQcSql::sfTableName(), $fkbTableName->getFcTxHd());
    $fkbUniqueCons->addFim(FimQcSql::sfIdentifName(), $fkbItem->getFcFn());

    $txUniuqFields = $fkbItem->getFimValue(FimFiCol::fcTxHeader());

    $arrUniqFields = FiString::split($txUniuqFields, ",", true);

    $sbFieldList = new FiStrbui();
    
    foreach ($arrUniqFields as $txField) {
      
    if (FiString::isEmpty($txField)) continue;
      // Unique alanların field tanımlarının da olması gerekir, yoksa hata verir
      /** @var Fkb $fkbFieldUni */
      $fkbFieldUni = $fkbFieldsByTxId->get($txField); 
      //OcgLogger::debug(json_encode($fkbFieldUni));
      $sbFieldList->append($fkbFieldUni->getFcFn())->append(FiString::textComma());
    }
    // Remove trailing comma and space
    $txFieldListStr = rtrim($sbFieldList->toString(), FiString::textComma());
    $fkbUniqueCons->addFim(FimQcSql::sfTxFields(), $txFieldListStr);

    $txUniqueCode = FiTemplate::replaceParams($template, $fkbUniqueCons);
    $sbUniqDefs->append($txUniqueCode)->append("\n");
  }

  /**
   * sqldeki sütunun field type ve diger özellikleri tanımlanır
   *
   * @param Fkb $fkbItem
   * @return string
   */
  public static function formSqlColTypeDef(Fkb $fkbItem): string
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

    if (FiString::any(
      $fcTxFieldType,
      FimOcgFieldTypes::decimal()->getTxKey(),
      FimOcgFieldTypes::double()->getTxKey()
    )) {

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

    if (FiString::any(
      $fcTxFieldType,
      FimOcgFieldTypes::bit()->getTxKey(),
      FimOcgFieldTypes::bool()->getTxKey()
    )) {
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

    $fcBoUnique = FicValue::toBool($fkbItem->getFimValue(FimFiCol::fcBoUnique()), false);

    if ($fcBoUnique) {
      $sbTypeDef->append(" UNIQUE");
    }

    return $sbTypeDef->toString();
  }
}
