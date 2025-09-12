<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\Core\FiBool;
use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\Core\FiString;
use Engtuncay\Phputils8\FiCol\FicFiCol;
use Engtuncay\Phputils8\FiCol\FicValue;
use Engtuncay\Phputils8\FiDto\FiKeybean;

class CogSpecsCSharpFkbCol implements ICogSpecsFkbCol
{

  public function getTemplateFkbColMethod(): string
  {
    return <<<EOD
public static FkbCol {{fieldMethodName}}()
{ 
  FkbCol fkbCol = new FkbCol("{{fieldName}}");
{{fkbColMethodBody}}
  return fkbCol;
}
EOD;
  }

  public function getTemplateFkbColMethodExtra(): string
  {
    return <<<EOD
public static FkbCol {{fieldMethodName}}Ext()
{
  FkbCol fkbCol = {{fieldMethodName}}();
{{fkbColMethodExtraBody}}
  return fkbCol;
}
EOD;
  }

  public function getTemplateFkbColClass(): string
  {
    //String
    $templateMain = <<<EOD
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

    foreach (FkbCol fkbCol in fkbList)
    {
        {{addFieldDescDetail}}
    }
    
  }

{{classBody}}

}
EOD;

    return $templateMain;
  }

  public function genFkbColMethodBody(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFkbColMethodBody = new FiStrbui(); // new StringBuilder();

    //String
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::ofcTxFieldType()));

    $ofcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader());
    if ($ofcTxHeader != null)
      $sbFkbColMethodBody->append(sprintf("  fkbCol.ofcTxHeader = \"%s\";\n", $ofcTxHeader));

    $ofcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldType());
    if ($ofcTxFieldType != null)
      $sbFkbColMethodBody->append(sprintf("  fkbCol.ofcTxFieldType = \"%s\";\n", $ofcTxFieldType));

    $ofcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDbField());
    if ($ofcTxDbField != null)
      $sbFkbColMethodBody->append(sprintf("  fkbCol.ofcTxDbField = \"%s\";\n", $ofcTxDbField)); {
      $ofcTxRefField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxRefField());
      if ($ofcTxRefField != null)
        $sbFkbColMethodBody->append(sprintf("  fkbCol.ofcTxRefField = \"%s\";\n", $ofcTxRefField));
    }


    //$ofcTxIdType = $fiCol->ofcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->ofcTxIdType);

    // if (!FiString.isEmpty(ofiTxIdType)) {
    // sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
    // sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
    // }

    $ofcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoTransient());
    if ($ofcBoTransient) {
      $sbFkbColMethodBody->append("  fkbCol.ofcBoTransient = true;\n");
    }

    $ofcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnLength()));
    if ($ofcLnLength != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.ofcLnLength = %s;\n", $ofcLnLength));
    }

    $ofcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnPrecision()));
    if ($ofcLnPrecision != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.ofcLnPrecision = %s;\n", $ofcLnPrecision));
    }

    $ofcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnScale()));
    if ($ofcLnScale != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.ofcLnScale = %s;\n", $ofcLnScale));
    }

    if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoNullable()))) {
      $sbFkbColMethodBody->append("  fkbCol.ofcBoNullable = false;\n");
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

  public function genFkbColMethodBodyDetailExtra(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    $ofcTxDesc = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDesc());
    //if ($ofcTxDesc != null)
    $sbFiColMethodBody->append(sprintf("  fiCol.ofcTxDesc = \"%s\";\n", $ofcTxDesc));

    return $sbFiColMethodBody;
  }

  // public function genFiMetaMethodBodyFieldDefs(FiKeybean $fkb): FiStrbui
  // {
  //   //StringBuilder
  //   $sbFmtMethodBodyFieldDefs = new FiStrbui();

  //   $txKey = $fkb->getValueByFiCol(FicFiMeta::ofmTxKey());
  //   if ($txKey != null) {
  //     $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
  //   }

  //   $txValue = $fkb->getValueByFiCol(FicFiMeta::ofmTxValue());
  //   if ($txValue != null) {
  //     $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txValue = '%s';\n", $txValue));
  //   }

  //   return $sbFmtMethodBodyFieldDefs;
  // }

  /**
   * @return string
   */
  public function getTemplateFkbColsExtraList(): string
  {
    return <<<EOD
public static FicList GenTableColsExtra() {
  FicList ficList = new FicList();

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
public static FicList GenTableColsTrans() {
  FicList ficList = new FicList();
  
  {{ficListBodyTrans}}
  
  return ficList;
}
EOD;
  }

  /**
   * @return string
   */
  public function getTemplateFkbColsListMethod(): string
  {
    return <<<EOD
public static FicList GenTableCols() {
  FicList ficList = new FicList();

  {{ficListBody}}

  return ficList;
}
EOD;
  }

  /**
   * @param FiStrbui $sbFclListBody
   * @param string $methodName
   * @param FiStrbui $sbFclListBodyExtra
   * @return void
   */
  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, string $methodName): void
  { //, FiStrbui $sbFclListBodyExtra
    $sbFclListBody->append("fkbList.Add($methodName());\n");
    // $sbFclListBodyExtra->append("ficList.Add($methodName" . "Ext());\n");
  }

  /**
   * @param FiStrbui $sbFclListBodyTrans
   * @param string $methodName
   * @return void
   */
  public function doTransientFieldOps(FiStrbui $sbFclListBodyTrans, string $methodName): void
  {
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
