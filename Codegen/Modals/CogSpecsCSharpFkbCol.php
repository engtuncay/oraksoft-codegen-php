<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiBool;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCols\FicValue;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

class CogSpecsCSharpFkbCol implements ICogSpecsGenCol
{

  public function getTemplateColClass(): string
  {
    //String
    $templateMain = <<<EOD
using OrakYazilimLib.FiMetas.FimStore;
using OrakYazilimLib.Util.Collection;
using OrakYazilimLib.Util.core;

public class {{classPref}}{{entityName}}
{

  public static string GetTxTableName()
  {
    return "{{tableName}}";
  }
  
  public string GetITxTableName()
  {
    return GetTxTableName();
  }

  public FkbList GenITableCols()
  {
    return GenTableCols();
  }

  public FkbList GenITableColsTrans()
  {
    return GenTableColsTrans();
  }
  
  public static string GetTxPrefix()
  {
    return "{{tablePrefix}}";
  }

  public string GetITxPrefix()
  {
    return GetTxPrefix();
  }

  public static void AddFieldDesc(FkbList fkbList) {

    foreach (FiKeybean fkb in fkbList)
    {
        {{addFieldDescDetail}}
    }
    
  }

{{classBody}}
}
EOD;

    return $templateMain;
  }

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


  public function genColMethodBody(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFkbColMethodBody = new FiStrbui(); // new StringBuilder();

    //String
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::ofcTxFieldType()));
    $ofcTxFieldName = $fkbItem->getValueByFiMeta(FimFiCol::fcTxFieldName());
    if ($ofcTxFieldName != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.OfcTxFieldName(), \"%s\");\n", $ofcTxFieldName));
    }


    $ofcTxHeader = $fkbItem->getValueByFiMeta(FimFiCol::ofcTxHeader());
    if ($ofcTxHeader != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.OfcTxHeader(), \"%s\");\n", $ofcTxHeader));
    }

    $ofcTxFieldType = $fkbItem->getValueByFiMeta(FimFiCol::fcTxFieldType());
    if ($ofcTxFieldType != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.OfcTxFieldType(), \"%s\");\n", $ofcTxFieldType));
    }

    $ofcTxDbField = $fkbItem->getValueByFiMeta(FimFiCol::ofcTxDbField());
    if ($ofcTxDbField != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.OfcTxDbField(), \"%s\");\n", $ofcTxDbField));
    }

    $ofcTxRefField = $fkbItem->getValueByFiMeta(FimFiCol::ofcTxRefField());
    if ($ofcTxRefField != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.OfcTxRefField(), \"%s\");\n", $ofcTxRefField));
    }




    //$ofcTxIdType = $fiCol->ofcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->ofcTxIdType);

    // if (!FiString.isEmpty(ofiTxIdType)) {
    // sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
    // sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
    // }

    $ofcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoTransient());
    if ($ofcBoTransient) {
      //$sbFkbColMethodBody->append("  fkbCol.ofcBoTransient = true;\n");
      $sbFkbColMethodBody->append("  fkbCol.AddFieldBy(FimFiCol.OfcBoTransient(), true );\n");
    }

    $ofcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnLength()));
    if ($ofcLnLength != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.OfcLnLength(), %s);\n", $ofcLnLength));
      // $sbFkbColMethodBody->append(sprintf("  fkbCol.ofcLnLength = %s;\n", $ofcLnLength));
    }

    $ofcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnPrecision()));
    if ($ofcLnPrecision != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.OfcLnPrecision(), %s);\n", $ofcLnPrecision));
    }

    $ofcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnScale()));
    if ($ofcLnScale != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.OfcLnScale(), %s);\n", $ofcLnScale));
    }

    if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoNullable()))) {
      $sbFkbColMethodBody->append("  fkbCol.AddFieldBy(FimFiCol.OfcBoNullable(), false);\n");
    }

    $ofcLnId = FicValue::toInt($fkbItem->getValueByFiMeta(FimFiCol::ofcLnId()));
    if ($ofcLnId != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.OfcLnId(), %s);\n", $ofcLnId));
    }

    //
    //    if (FiBool::isTrue($fiCol->ofcBoNullable)) {
    //      $sbFiColMethodBody->append("fiCol.ofcBoNullable = true;\n");
    //    }

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

    return $sbFkbColMethodBody;
  }

  public function genColMethodBodyDetailExtra(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    $ofcTxDesc = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDesc());
    //if ($ofcTxDesc != null)
    $sbFiColMethodBody->append(sprintf("  fiCol.ofcTxDesc = \"%s\";\n", $ofcTxDesc));

    return $sbFiColMethodBody;
  }

  /**
   * @return string
   */
  public function getTemplateColListExtra(): string
  {
    return <<<EOD
public static FkbList GenTableColsExtra() {
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
public static FkbList GenTableColsTrans() {
  FkbList fkbList = new FkbList();
  
  {{fkbListBodyTrans}}
  
  return fkbList;
}
EOD;
  }

  /**
   * @return string
   */
  public function getTemplateColListMethod(): string
  {
    return <<<EOD
public static FkbList GenTableCols() {
  FkbList fkbList = new FkbList();

  {{fkbListBody}}

  return fkbList;
}
EOD;
  }

  /**
   * @param FiStrbui $sbFclListBody
   * @param string $methodName
   * @param FiStrbui $sbFclListBodyExtra
   * @return void
   */
  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  { //, FiStrbui $sbFclListBodyExtra
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbFclListBody->append("fkbList.Add($methodName());\n");
    // $sbFclListBodyExtra->append("ficList.Add($methodName" . "Ext());\n");
  }

  /**
   * @param FiStrbui $sbFclListBodyTrans
   * @param FiKeybean $fkbItem
   * @param ICogSpecs $iCogSpecs
   * @return void
   */
  public function doTransientFieldOps(FiStrbui $sbFclListBodyTrans, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbFclListBodyTrans->append("fkbList.Add($methodName());\n");
  }

  public function genFiColAddDescDetail(FiKeybean $fkbItem, ICogSpecs $iCogSpecs): FiStrbui
  {
    //StringBuilder
    $sbText = new FiStrbui(); // new StringBuilder();

    $ofcTxFielDesc = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDesc());

    if (!FiString::isEmpty($ofcTxFielDesc)) {
      $methodNameStd = $iCogSpecs->checkMethodNameStd($fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldName()));

      $sbText->append(
        <<<EOD

    if(FiString.Equals(fkbCol.ofcTxFieldName,$methodNameStd().ofcTxFieldName)){
      fkbCol.ofcTxFieldDesc = "$ofcTxFielDesc";
    }
      
EOD
      );
    }

    return $sbText;
  }
}
