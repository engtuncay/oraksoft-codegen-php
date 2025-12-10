<?php

namespace Codegen\Modals;

use Codegen\FiCols\FicFiMeta;
use Engtuncay\Phputils8\Core\FiBool;
use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\Core\FiString;
use Engtuncay\Phputils8\FiCol\FicFiCol;
use Engtuncay\Phputils8\FiCol\FicValue;
use Engtuncay\Phputils8\FiDto\FiKeybean;

class CogSpecsJavaFkbCol implements ICogSpecsFkbCol
{
  
  public function getTemplateFkbColsListMethod(): string
  {
    return "";
  }

  public function genFkbColMethodBody(FiKeybean $fkbItem): FiStrbui
  {
    return new FiStrbui();
  }

  public function getTemplateFkbColMethod(): string
  {
    return <<<EOD
public static FiCol {{fieldMethodName}}()
{
  FiCol fiCol = new FiCol("{{fieldName}}");
{{fiColMethodBody}}
  return fiCol;
}
EOD;
  }


  public function getTemplateFiColMethodExtra(): string
  {
    return <<<EOD
public static FiCol {{fieldMethodName}}Ext()
{
  FiCol fiCol = {{fieldMethodName}}();
{{fiColMethodBody}}
  return fiCol;
}
EOD;
  }

  /**
   * FicEntity Class -> FiCols
   * 
   * FiCol dan FkbCol'a çevrilecek
   *
   * @return string
   */
  public function getTemplateFkbColClass(): string
  {
    //FicFiCol::ofcTxHeader();

    //String
    $templateMain = <<<EOD

import ozpasyazilim.utils.table.FiCol;
import ozpasyazilim.utils.table.OzColType;
import ozpasyazilim.utils.table.FiColList;
import ozpasyazilim.utils.fidbanno.FiIdGenerationType;
import ozpasyazilim.utils.fidborm.IFiTableMeta;
      
public class {{classPref}}{{entityName}} implements IFiTableMeta
{

  public static String getTxTableName()
  {
    return "{{tableName}}";
  }
  
  public String getITxTableName()
  {
    return getTxTableName();
  }

  public FiColList genITableCols()
  {
    return genTableCols();
  }
  
  public FiColList genITableColsTrans()
  {
    return genTableColsTrans();
  }
  
  public static String getTxPrefix()
  {
    return "{{tablePrefix}}";
  }

  public String getITxPrefix()
  {
    return getTxPrefix();
  }

{{classBody}}

}
EOD;

    return $templateMain;
  }


  public function genFiColMethodBodyDetail(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    //String
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::ofcTxFieldType()));

    $ofcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader());
    if ($ofcTxHeader != null)
      $sbFiColMethodBody->append(sprintf("  fiCol.setOfcTxHeader(\"%s\");\n", $ofcTxHeader));

    $ofcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldType());
    if ($ofcTxFieldType != null)
      $sbFiColMethodBody->append(sprintf("  fiCol.setOfcTxFieldType (\"%s\");\n", $ofcTxFieldType));

    $ofcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDbField());
    if ($ofcTxDbField != null)
      $sbFiColMethodBody->append(" fiCol.setOfcTxDbField (\"$ofcTxDbField\");\n");

    //$ofcTxIdType = $fiCol->ofcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->ofcTxIdType);

    // if (!FiString.isEmpty(ofiTxIdType)) {
    // sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
    // sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
    // }

    $ofcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoTransient());
    if ($ofcBoTransient) {
      $sbFiColMethodBody->append("  fiCol.setOfcBoTransient(true);\n");
    }

    $ofcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnLength()));
    if ($ofcLnLength != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.setOfcLnLength(%s);\n", $ofcLnLength));
    }

    $ofcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnPrecision()));
    if ($ofcLnPrecision != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.setOfcLnPrecision(%s);\n", $ofcLnPrecision));
    }

    $ofcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnScale()));
    if ($ofcLnScale != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.setOfcLnScale(%s);\n", $ofcLnScale));
    }

    if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoNullable()))) {
      $sbFiColMethodBody->append("  fiCol.setOfcBoNullable(false);\n");
    }

    //        if (FiBool.isTrue(fiCol.getOfcBoUnique())) {
    //          sbFiColMethodBody.append("\tfiCol.ofcBoUnique = true;\n");
    //        }
    //
    //        if (FiBool.isTrue(fiCol.getOfcBoUniqGro1())) {
    //          sbFiColMethodBody.append("\tfiCol.ofcBoUniqGro1 = true;\n");
    //        }
    //
    //        if (FiBool.isTrue(fiCol.getOfcBoUtfSupport())) {
    //          sbFiColMethodBody.append("\tfiCol.ofcBoUtfSupport = true;\n");
    //        }
    //
    //        if (!FiString.isEmpty(fiCol.getOfcTxDefValue())) {
    //          sbFiColMethodBody.append(String.format("\tfiCol.ofcTxDefValue = \"%s\";\n", fiCol.getOfcTxDefValue()));
    //        }
    //
    //        if (FiBool.isTrue(fiCol.getBoFilterLike())) {
    //          sbFiColMethodBody.append("\tfiCol.ofcBoFilterLike = true;\n");
    //        }
    //
    //        // ofcTxCollation	ofcTxTypeName

    return $sbFiColMethodBody;
  }

  public function genFiColMethodBodyDetailExtra(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    $ofcTxFielDesc = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDesc());
    //if ($ofcTxFielDesc != null)
    $sbFiColMethodBody->append(sprintf("  fiCol.setOfcTxFieldDesc(\"%s\");\n", $ofcTxFielDesc));

    return $sbFiColMethodBody;
  }

  public function genFiMetaMethodBodyFieldDefs(FiKeybean $fkb): FiStrbui
  {
    //StringBuilder
    $sbFmtMethodBodyFieldDefs = new FiStrbui();

    $txKey = $fkb->getValueByFiCol(FicFiMeta::ofmTxKey());
    if ($txKey != null) {
      $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
    }

    $txValue = $fkb->getValueByFiCol(FicFiMeta::ofmTxValue());
    if ($txValue != null) {
      $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txValue = '%s';\n", $txValue));
    }

    return $sbFmtMethodBodyFieldDefs;
  }

  /**
   * @return string
   */
  public function getTempGenFkbColsExtraList(): string
  {
    return <<<EOD
public static FiColList genTableColsExtra() {
  FiColList ficList = new FiColList();

  {{ficListBodyExtra}}

  return ficList;
}
EOD;
  }

  /**
   * @return string
   */
  public function getTemplateFkbColsListTransMethod(): string
  {
    return <<<EOD
public static FiColList genTableColsTrans() {
  FiColList ficList = new FiColList();
  
  {{ficListBodyTrans}}
  
  return ficList;
}
EOD;
  }

  /**
   * @return string
   */
  public function getTempGenFkbColsMethod(): string
  {
    return <<<EOD
public static FiColList genTableCols() {
  FiColList ficList = new FiColList();

  {{ficListBody}}

  return ficList;
}
EOD;
  }

  /**
   * @param FiStrbui $sbFclListBody
   * @param string $methodName
   * @return void
   */
  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  { //, FiStrbui $sbFclListBodyExtra
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbFclListBody->append("ficList.add($methodName());\n");
    //$sbFclListBodyExtra->append("ficList.add($methodName" . "Ext());\n");
  }

  /**
   * @param FiStrbui $sbFclListBodyTrans
   * @param string $methodName
   * @return void
   */
  public function doTransientFieldOps(FiStrbui $sbFclListBodyTrans, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbFclListBodyTrans->append("ficList.add($methodName());\n");
  }

  public static function getTemplateFiMetaClass(): string
  {
    //String
    $templateMain = <<<EOD
use Engtuncay\Phputils8\FiDto\FiMeta;
use Engtuncay\Phputils8\FiDto\FmtList;

class {{entityName}} {

{{classBody}}

}
EOD;

    return $templateMain;
  }

  public function genFiColAddDescDetail(FiKeybean $fkbItem, ICogSpecs $cogFicSpecs): FiStrbui
  {
    // TODO: Implement genFiColAddDescBody() method.
    $sbFiColAddDescBody = new FiStrbui();
    return $sbFiColAddDescBody;
  }
}
