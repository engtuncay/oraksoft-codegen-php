<?php

namespace Codegen\Modals;

use Codegen\FiCols\FicFiMeta;
use Engtuncay\Phputils8\FiCores\FiBool;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCols\FicValue;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

class CogSpecsJavaFkbCol implements ICogSpecsGenCol
{

  public function getTemplateColMethod(): string
  {
    return <<<EOD
public static FiKeybean {{fieldMethodName}}()
{
  FiKeybean fkbCol = new FiKeybean();
{{fkbColMethodBody}}
  return fkbCol;
}
EOD;
  }



  public function getTemplateColMethodExtra(): string
  {
    return <<<EOD
public static FiKeybean {{fieldMethodName}}Ext()
{
  FiKeybean fkbCol = {{fieldMethodName}}();
{{fkbColMethodExtraBody}}
  return fkbCol;
}
EOD;
  }

  /**
   * FkcEntity Class -> FkbCols
   * 
   * @return string
   */
  public function getTemplateColClass(): string
  {
    //FicFiCol::ofcTxHeader();

    //String
    $templateMain = <<<EOD

import ozpasyazilim.utils.datatypes.FiKeybean;
import ozpasyazilim.utils.fidborm.IFiTableMetaFkc;
import ozpasyazilim.utils.fidbanno.FiIdGenerationType;
import ozpasyazilim.utils.datatypes.FkbList;
import ozpasyazilim.utils.ficols.FimFiCol;

public class {{classPref}}{{entityName}} implements IFiTableMetaFkc
{

  public static String getTxTableName()
  {
    return "{{tableName}}";
  }
  
  public String getITxTableName()
  {
    return getTxTableName();
  }

  public FkbList genITableCols()
  {
    return genTableCols();
  }
  
  public FkbList genITableColsTrans()
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


  public function genColMethodBody(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    //String
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::ofcTxFieldType()));

    $ofcTxFieldName = $fkbItem->getValueByFiMeta(FimFiCol::fcTxFieldName());
    if ($ofcTxFieldName != null) {
      $sbFiColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.ofcTxFieldName(), \"%s\");\n", $ofcTxFieldName));
    }

    $ofcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader());
    if ($ofcTxHeader != null) {
      $sbFiColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.ofcTxHeader(), \"%s\");\n", $ofcTxHeader));
    }


    $ofcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldType());
    if ($ofcTxFieldType != null) {
      $sbFiColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.ofcTxFieldType(), \"%s\");\n", $ofcTxFieldType));
    }

    $ofcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDbField());
    if ($ofcTxDbField != null) {
      $sbFiColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.ofcTxDbField(), \"%s\");\n", $ofcTxDbField));
    }

    //$ofcTxIdType = $fiCol->ofcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->ofcTxIdType);

    // if (!FiString.isEmpty(ofiTxIdType)) {
    // sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
    // sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
    // }

    $ofcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoTransient());
    if ($ofcBoTransient) {
      $sbFiColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.ofcBoTransient(), true);\n"));
    }

    $ofcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnLength()));
    if ($ofcLnLength != null) {
      $sbFiColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.ofcLnLength(), %s);\n", $ofcLnLength));
    }

    $ofcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnPrecision()));
    if ($ofcLnPrecision != null) {
      $sbFiColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.ofcLnPrecision(), %s);\n", $ofcLnPrecision));
    }

    $ofcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnScale()));
    if ($ofcLnScale != null) {
      $sbFiColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.ofcLnScale(), %s);\n", $ofcLnScale));
    }

    if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoNullable()))) {
      $sbFiColMethodBody->append("  fkbCol.AddFieldBy(FimFiCol.ofcBoNullable(), false);\n");
    }

    //        if (FiBool.isTrue(fiCol.getOfcBoUnique())) {
    //          sbFiColMethodBody.append("\tfiCol.ofcBoUnique = true;\n");
    //        }
    //
    //        if (FiBool.isTrue(fiCol.getOfcBoUniqGro1())) {
    //          sbFiColMethodBody.append("\tfkbCol.ofcBoUniqGro1 = true;\n");
    //        }
    //
    //        if (FiBool.isTrue(fiCol.getOfcBoUtfSupport())) {
    //          sbFiColMethodBody.append("\tfkbCol.ofcBoUtfSupport = true;\n");
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

  public function genGenMethodBodyDetailExtra(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    $ofcTxFielDesc = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDesc());
    //if ($ofcTxFielDesc != null)
    $sbFiColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.ofcTxFieldDesc(), \"%s\");\n", $ofcTxFielDesc));

    return $sbFiColMethodBody;
  }

  /**
   * @return string
   */
  public function getTempGenTableColsExtraMethod(): string
  {
    return <<<EOD
public static FiColList genTableColsExtra() {
  FkbList fkbList = new FkbList();

  {{fkbListBodyExtra}}

  return fkbList;
}
EOD;
  }

  /**
   * @return string
   */
  public function getTemplateColListTransMethod(): string
  {
    return <<<EOD
public static FkbList genTableColsTrans() {
  FkbList fkbList = new FkbList();
  
  {{fkbListBodyTrans}}
  
  return fkbList;
}
EOD;
  }

  /**
   * 
   * genTableCols Method
   * 
   * @return string
   */
  public function getTemplateColListMethod(): string
  {
    return <<<EOD
public static FkbList genTableCols() {
  FkbList fkbList = new FkbList();

  {{fkbListBody}}

  return fkbList;
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
    $sbFclListBody->append("fkbList.add($methodName());\n");
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
    $sbFclListBodyTrans->append("fkbList.add($methodName());\n");
  }

  public function genFiColAddDescDetail(FiKeybean $fkbItem, ICogSpecs $cogFicSpecs): FiStrbui
  {
    // TODO: Implement genFiColAddDescBody() method.
    $sbFiColAddDescBody = new FiStrbui();
    return $sbFiColAddDescBody;
  }
}
