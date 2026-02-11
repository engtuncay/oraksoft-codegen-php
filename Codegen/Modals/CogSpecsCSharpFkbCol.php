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
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::fcTxFieldType()));
    $fcTxFieldName = $fkbItem->getValueByFiMeta(FimFiCol::fcTxFieldName());
    if ($fcTxFieldName != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.OfcTxFieldName(), \"%s\");\n", $fcTxFieldName));
    }


    $fcTxHeader = $fkbItem->getValueByFiMeta(FimFiCol::fcTxHeader());
    if ($fcTxHeader != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.OfcTxHeader(), \"%s\");\n", $fcTxHeader));
    }

    $fcTxFieldType = $fkbItem->getValueByFiMeta(FimFiCol::fcTxFieldType());
    if ($fcTxFieldType != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.OfcTxFieldType(), \"%s\");\n", $fcTxFieldType));
    }

    $fcTxDbField = $fkbItem->getValueByFiMeta(FimFiCol::fcTxDbField());
    if ($fcTxDbField != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.OfcTxDbField(), \"%s\");\n", $fcTxDbField));
    }

    $fcTxRefField = $fkbItem->getValueByFiMeta(FimFiCol::fcTxRefField());
    if ($fcTxRefField != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.OfcTxRefField(), \"%s\");\n", $fcTxRefField));
    }




    //$fcTxIdType = $fiCol->fcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->fcTxIdType);

    // if (!FiString.isEmpty(ofiTxIdType)) {
    // sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
    // sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
    // }

    $fcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoTransient());
    if ($fcBoTransient) {
      //$sbFkbColMethodBody->append("  fkbCol.fcBoTransient = true;\n");
      $sbFkbColMethodBody->append("  fkbCol.AddFieldBy(FimFiCol.OfcBoTransient(), true );\n");
    }

    $fcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnLength()));
    if ($fcLnLength != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.OfcLnLength(), %s);\n", $fcLnLength));
      // $sbFkbColMethodBody->append(sprintf("  fkbCol.fcLnLength = %s;\n", $fcLnLength));
    }

    $fcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnPrecision()));
    if ($fcLnPrecision != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.OfcLnPrecision(), %s);\n", $fcLnPrecision));
    }

    $fcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnScale()));
    if ($fcLnScale != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.OfcLnScale(), %s);\n", $fcLnScale));
    }

    if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoNullable()))) {
      $sbFkbColMethodBody->append("  fkbCol.AddFieldBy(FimFiCol.OfcBoNullable(), false);\n");
    }

    $fcLnId = FicValue::toInt($fkbItem->getValueByFiMeta(FimFiCol::fcLnId()));
    if ($fcLnId != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.AddFieldBy(FimFiCol.OfcLnId(), %s);\n", $fcLnId));
    }

    //
    //    if (FiBool::isTrue($fiCol->fcBoNullable)) {
    //      $sbFiColMethodBody->append("fiCol.fcBoNullable = true;\n");
    //    }

    //        if (FiBool.isTrue(fiCol.getFcBoUnique())) {
    //          sbFiColMethodBody.append("\tfiCol.fcBoUnique = true;\n");
    //        }
    //
    //        if (FiBool.isTrue(fiCol.getFcBoUniqGro1())) {
    //          sbFiColMethodBody.append("\tfiCol.fcBoUniqGro1 = true;\n");
    //        }
    //
    //        if (FiBool.isTrue(fiCol.getFcBoUtfSupport())) {
    //          sbFiColMethodBody.append("\tfiCol.fcBoUtfSupport = true;\n");
    //        }
    //
    //        if (!FiString.isEmpty(fiCol.getFcTxDefValue())) {
    //          sbFiColMethodBody.append(String.format("\tfiCol.fcTxDefValue = \"%s\";\n", fiCol.getFcTxDefValue()));
    //        }
    //
    //        if (FiBool.isTrue(fiCol.getBoFilterLike())) {
    //          sbFiColMethodBody.append("\tfiCol.fcBoFilterLike = true;\n");
    //        }
    //
    //        // fcTxCollation	fcTxTypeName

    return $sbFkbColMethodBody;
  }

  public function genColMethodBodyDetailExtra(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    $fcTxDesc = $fkbItem->getValueByFiCol(FicFiCol::fcTxDesc());
    //if ($fcTxDesc != null)
    $sbFiColMethodBody->append(sprintf("  fiCol.fcTxDesc = \"%s\";\n", $fcTxDesc));

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
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
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
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbFclListBodyTrans->append("fkbList.Add($methodName());\n");
  }

  public function genFiColAddDescDetail(FiKeybean $fkbItem, ICogSpecs $iCogSpecs): FiStrbui
  {
    //StringBuilder
    $sbText = new FiStrbui(); // new StringBuilder();

    $fcTxFielDesc = $fkbItem->getValueByFiCol(FicFiCol::fcTxDesc());

    if (!FiString::isEmpty($fcTxFielDesc)) {
      $methodNameStd = $iCogSpecs->checkMethodNameStd($fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName()));

      $sbText->append(
        <<<EOD

    if(FiString.Equals(fkbCol.fcTxFieldName,$methodNameStd().fcTxFieldName)){
      fkbCol.fcTxFieldDesc = "$fcTxFielDesc";
    }
      
EOD
      );
    }

    return $sbText;
  }
}
