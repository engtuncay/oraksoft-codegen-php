<?php

namespace Codegen\Modals;

use Codegen\FiCols\FicFiMeta;
use Engtuncay\Phputils8\FiCores\FiBool;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCols\FicValue;
use Engtuncay\Phputils8\FiDtos\FiKeybean;

class CogSpecsPhpFiCol implements ICogSpecsGenCol
{
  //
  public function genColMethodBodyByFiColTemp(FiKeybean $fkb): FiStrbui
  {
    return new FiStrbui();
  }

  public function getTemplateColClass(): string
  {
    //String
    $templateMain = <<<EOD
      
use Engtuncay\Phputils8\FiDtos\FiMeta;
use Engtuncay\Phputils8\FiDtos\FmtList;

class {{classPref}}{{entityName}} {

{{classBody}}

}
EOD;

    return $templateMain;
  }



  public function getTemplateColMethod(): string
  {
    return <<<EOD
public static function {{fieldMethodName}}() : FiCol
{ 
  \$fiCol = new FiCol("{{fieldName}}");
{{fiColMethodBody}}
  return \$fiCol;
}
EOD;
  }

  //   public function getTemplateFkbColMethod(): string
  //   {
  //     return <<<EOD
  // public static function {{fieldMethodName}}() : FiKeybean
  // { 
  //   \$fkbCol = new FiKeybean();
  // {{fiColMethodBody}}
  //   return \$fkbCol;
  // }
  // EOD;
  //   }

  //   public function getTemplateColMethod(): string
  //   {
  //     return <<<EOD
  // public static function {{fieldMethodName}}() : FiMeta
  // { 
  //   \$fiMeta = new FiMeta("{{fieldName}}");
  // {{fiMethodBody}}
  //   return \$fiMeta;
  // }
  // EOD;
  //   }

  public function getTemplateColMethodExtra(): string
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

  public function getTemplateFicClass(): string
  {
    //String
    $templateMain = <<<EOD
use Engtuncay\Phputils8\FiCols\IFiTableMeta;
use Engtuncay\Phputils8\FiDtos\FiCol;
use Engtuncay\Phputils8\FiDtos\FicList;

class {{classPref}}{{entityName}} implements IFiTableMeta {

public function getITxTableName() : string {
  return self::GetTxTableName();
}

public static function  getTxTableName() : string{
  return "{{entityName}}";
}

{{classBody}}

public function genITableCols() : FicList {
  return self::genTableCols();
}

public function genITableColsTrans():FicList {
  return self::genTableColsTrans();
}

}
EOD;

    return $templateMain;
  }

  public function getTemplateFkbColClass(): string
  {
    //String
    $templateMain = <<<EOD

use Engtuncay\Phputils8\FiCols\IFkbTableMeta;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

class {{classPref}}{{entityName}} implements IFkbTableMeta {

public function getITxTableName() : string {
  return self::GetTxTableName();
}

public static function  getTxTableName() : string{
  return "{{entityName}}";
}

{{classBody}}

public function genITableCols() : FkbList {
  return self::genTableCols();
}

public function genITableColsTrans():FkbList {
  return self::genTableColsTrans();
}

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
    //$txKey = $fkb->getValueByFiCol(FicFiMeta::txKey());
    //if ($txKey != null) {
    //  $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
    //}

    $ofcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader());
    if ($ofcTxHeader != null)
      $sbFiColMethodBody->append(sprintf("  \$fiCol->ofcTxHeader = '%s';\n", $ofcTxHeader));

    $ofcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldType());
    if ($ofcTxFieldType != null)
      $sbFiColMethodBody->append(sprintf("  \$fiCol->ofcTxFieldType = '%s';\n", $ofcTxFieldType));

    // $ofcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDbField());
    // if ($ofcTxDbField != null)
    //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcTxDbField = \"%s\";\n", $ofcTxDbField));

    // {
    //   $ofcTxRefField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxRefField());
    //   if ($ofcTxRefField != null)
    //     $sbFiColMethodBody->append(sprintf("  \$fiCol->ofcTxRefField = \"%s\";\n", $ofcTxRefField));
    // }


    //$ofcTxIdType = $fiCol->ofcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->ofcTxIdType);

    // if (!FiString.isEmpty(ofiTxIdType)) {
    // sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
    // sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
    // }

    // $ofcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoTransient());
    // if ($ofcBoTransient) {
    //   $sbFiColMethodBody->append("  fiCol.ofcBoTransient = true;\n");
    // }

    // $ofcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnLength()));
    // if ($ofcLnLength != null) {
    //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnLength = %s;\n", $ofcLnLength));
    // }

    // $ofcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnPrecision()));
    // if ($ofcLnPrecision != null) {
    //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnPrecision = %s;\n", $ofcLnPrecision));
    // }

    // $ofcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnScale()));
    // if ($ofcLnScale != null) {
    //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnScale = %s;\n", $ofcLnScale));
    // }

    // if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoNullable()))) {
    //   $sbFiColMethodBody->append("  fiCol.ofcBoNullable = false;\n");
    // }


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

    return $sbFiColMethodBody;
  }

  // public function genFkbColMethodBodyDetail(FiKeybean $fkbItem): FiStrbui
  // {
  //   //StringBuilder
  //   $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

  //   //String
  //   //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::ofcTxFieldType()));
  //   //$txKey = $fkb->getValueByFiCol(FicFiMeta::txKey());
  //   //if ($txKey != null) {
  //   //  $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
  //   //}

  //   //    $fkbCol->addFiCol(FicFiCol::ofcTxFieldName(),'evsTxMustKod'); // "ofcTxFieldType", 'string'); //->ofcTxFieldType = 'string';
  //   // $fkbCol->addFiCol(FicFiCol::ofcTxFieldType(), 'string'); //->ofcTxFieldType = 'string';

  //   $ofcTxFieldName = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldName());
  //   if ($ofcTxFieldName != null)
  //     $sbFiColMethodBody->append(sprintf("  \$fkbCol->addFm(FimFiCol::fcTxFieldName(), '%s');\n", $ofcTxFieldName));

  //   $ofcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader());
  //   if ($ofcTxHeader != null)
  //     $sbFiColMethodBody->append(sprintf("  \$fkbCol->addFm(FimFiCol::ofcTxHeader(), '%s');\n", $ofcTxHeader));

  //   $ofcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldType());
  //   if ($ofcTxFieldType != null)
  //     $sbFiColMethodBody->append(sprintf("  \$fkbCol->addFm(FimFiCol::fcTxFieldType(), '%s');\n", $ofcTxFieldType));

  //   // $ofcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDbField());
  //   // if ($ofcTxDbField != null)
  //   //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcTxDbField = \"%s\";\n", $ofcTxDbField));

  //   // {
  //   //   $ofcTxRefField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxRefField());
  //   //   if ($ofcTxRefField != null)
  //   //     $sbFiColMethodBody->append(sprintf("  \$fiCol->ofcTxRefField = \"%s\";\n", $ofcTxRefField));
  //   // }


  //   //$ofcTxIdType = $fiCol->ofcTxIdType;
  //   //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->ofcTxIdType);

  //   // if (!FiString.isEmpty(ofiTxIdType)) {
  //   // sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
  //   // sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
  //   // }

  //   // $ofcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoTransient());
  //   // if ($ofcBoTransient) {
  //   //   $sbFiColMethodBody->append("  fiCol.ofcBoTransient = true;\n");
  //   // }

  //   // $ofcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnLength()));
  //   // if ($ofcLnLength != null) {
  //   //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnLength = %s;\n", $ofcLnLength));
  //   // }

  //   // $ofcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnPrecision()));
  //   // if ($ofcLnPrecision != null) {
  //   //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnPrecision = %s;\n", $ofcLnPrecision));
  //   // }

  //   // $ofcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnScale()));
  //   // if ($ofcLnScale != null) {
  //   //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnScale = %s;\n", $ofcLnScale));
  //   // }

  //   // if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoNullable()))) {
  //   //   $sbFiColMethodBody->append("  fiCol.ofcBoNullable = false;\n");
  //   // }


  //   //    if (FiBool::isTrue($fiCol->ofcBoNullable)) {
  //   //      $sbFiColMethodBody->append("fiCol.ofcBoNullable = true;\n");
  //   //    }

  //   //        if (FiBool.isTrue(fiCol.getOfcBoUnique())) {
  //   //          sbFiColMethodBody.append("\tfiCol.ofcBoUnique = true;\n");
  //   //        }
  //   //
  //   //        if (FiBool.isTrue(fiCol.getOfcBoUniqGro1())) {
  //   //          sbFiColMethodBody.append("\tfiCol.ofcBoUniqGro1 = true;\n");
  //   //        }
  //   //
  //   //        if (FiBool.isTrue(fiCol.getOfcBoUtfSupport())) {
  //   //          sbFiColMethodBody.append("\tfiCol.ofcBoUtfSupport = true;\n");
  //   //        }
  //   //
  //   //        if (!FiString.isEmpty(fiCol.getOfcTxDefValue())) {
  //   //          sbFiColMethodBody.append(String.format("\tfiCol.ofcTxDefValue = \"%s\";\n", fiCol.getOfcTxDefValue()));
  //   //        }
  //   //
  //   //        if (FiBool.isTrue(fiCol.getBoFilterLike())) {
  //   //          sbFiColMethodBody.append("\tfiCol.ofcBoFilterLike = true;\n");
  //   //        }
  //   //
  //   //        // ofcTxCollation	ofcTxTypeName

  //   return $sbFiColMethodBody;
  // }

  // public function genFiMetaMethodBody(FiKeybean $fkbItem): FiStrbui
  // {
  //   //StringBuilder
  //   $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

  //   //String
  //   //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::ofcTxFieldType()));
  //   //$txKey = $fkb->getValueByFiCol(FicFiMeta::txKey());
  //   //if ($txKey != null) {
  //   //  $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
  //   //}

  //   // $ofcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader());
  //   // if ($ofcTxHeader != null)
  //   //   $sbFiColMethodBody->append(sprintf(" \$fiCol->ofcTxHeader = '%s';\n", $ofcTxHeader));

  //   // $ofcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldType());
  //   // if ($ofcTxFieldType != null)
  //   //   $sbFiColMethodBody->append(sprintf("  \$fiCol->ofcTxFieldType = '%s';\n", $ofcTxFieldType));

  //   // $ofcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDbField());
  //   // if ($ofcTxDbField != null)
  //   //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcTxDbField = \"%s\";\n", $ofcTxDbField));

  //   // {
  //   //   $ofcTxRefField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxRefField());
  //   //   if ($ofcTxRefField != null)
  //   //     $sbFiColMethodBody->append(sprintf("  fiCol.ofcTxRefField = \"%s\";\n", $ofcTxRefField));
  //   // }


  //   //$ofcTxIdType = $fiCol->ofcTxIdType;
  //   //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->ofcTxIdType);

  //   // if (!FiString.isEmpty(ofiTxIdType)) {
  //   // sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
  //   // sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
  //   // }

  //   // $ofcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoTransient());
  //   // if ($ofcBoTransient) {
  //   //   $sbFiColMethodBody->append("  fiCol.ofcBoTransient = true;\n");
  //   // }

  //   // $ofcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnLength()));
  //   // if ($ofcLnLength != null) {
  //   //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnLength = %s;\n", $ofcLnLength));
  //   // }

  //   // $ofcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnPrecision()));
  //   // if ($ofcLnPrecision != null) {
  //   //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnPrecision = %s;\n", $ofcLnPrecision));
  //   // }

  //   // $ofcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnScale()));
  //   // if ($ofcLnScale != null) {
  //   //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnScale = %s;\n", $ofcLnScale));
  //   // }

  //   // if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoNullable()))) {
  //   //   $sbFiColMethodBody->append("  fiCol.ofcBoNullable = false;\n");
  //   // }


  //   //    if (FiBool::isTrue($fiCol->ofcBoNullable)) {
  //   //      $sbFiColMethodBody->append("fiCol.ofcBoNullable = true;\n");
  //   //    }

  //   //        if (FiBool.isTrue(fiCol.getOfcBoUnique())) {
  //   //          sbFiColMethodBody.append("\tfiCol.ofcBoUnique = true;\n");
  //   //        }
  //   //
  //   //        if (FiBool.isTrue(fiCol.getOfcBoUniqGro1())) {
  //   //          sbFiColMethodBody.append("\tfiCol.ofcBoUniqGro1 = true;\n");
  //   //        }
  //   //
  //   //        if (FiBool.isTrue(fiCol.getOfcBoUtfSupport())) {
  //   //          sbFiColMethodBody.append("\tfiCol.ofcBoUtfSupport = true;\n");
  //   //        }
  //   //
  //   //        if (!FiString.isEmpty(fiCol.getOfcTxDefValue())) {
  //   //          sbFiColMethodBody.append(String.format("\tfiCol.ofcTxDefValue = \"%s\";\n", fiCol.getOfcTxDefValue()));
  //   //        }
  //   //
  //   //        if (FiBool.isTrue(fiCol.getBoFilterLike())) {
  //   //          sbFiColMethodBody.append("\tfiCol.ofcBoFilterLike = true;\n");
  //   //        }
  //   //
  //   //        // ofcTxCollation	ofcTxTypeName

  //   return $sbFiColMethodBody;
  // }

  public function genColMethodBodyDetailExtra(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    $ofcTxDesc = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDesc());
    //if ($ofcTxDesc != null)
    $sbFiColMethodBody->append(sprintf("  \$fiCol->ofcTxDesc = \"%s\";\n", $ofcTxDesc));

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
  public function getTemplateColListExtraMethod(): string
  {
    return <<<EOD
public static function genTableColsExtra() : FicList {
    \$ficList = new FicList();

  {{ficListBodyExtra}}

  return \$ficList;
}
EOD;
  }

  /**
   * @return string
   */
  public function getTemplateFiColsTransListMethod(): string
  {
    return <<<EOD
public static function genTableColsTrans() : FicList {
  \$ficList = new FicList();

  {{ficListBodyTrans}}

  return \$ficList;
}
EOD;
  }

  /**
   * @return string
   */
  public function getTempGenFmtColsTransList(): string
  {
    return <<<EOD
public static function genTableColsTrans() : FmtList {
  \$fmtList = new FmtList();

  {{fmtListBodyTrans}}

  return \$fmtList;
}
EOD;
  }

  /**
   * @return string
   */
  public function getTemplateColListTransMethod(): string
  {
    return <<<EOD
public static function genTableColsTrans() : FkbList {
  \$ficList = new FkbList();

  {{ficListBodyTrans}}

  return \$ficList;
}
EOD;
  }

  /**
   * @return string
   */
  public function getTemplateColListMethod(): string
  {
    return <<<EOD
public static function genTableCols() : FicList {
  \$ficList = new FicList();

  {{ficListBody}}

  return \$ficList;
}
EOD;
  }

  /**
   * @return string
   */
  public function getTempGenFmtColsMethod(): string
  {
    return <<<EOD
public static function genTableCols() : FmtList {
  \$fmtList = new FmtList();

  {{fmtListBody}}

  return \$fmtList;
}
EOD;
  }



  /**
   * @param FiStrbui $sbFclListBody
   * @param string $methodName
   * @return void
   */
  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    //$sbFclListBody->append("\$ficList->add(self::$methodName());\n");
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbFclListBody->append("\$ficList->add(self::$methodName());\n");
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
    $sbFclListBodyTrans->append("\$ficList->add(self::$methodName());\n");
  }

  public function genFiColAddDescMethodBody(FiKeybean $fkbItem, ICogSpecs $iCogSpecs): FiStrbui
  {
    //StringBuilder
    $sbText = new FiStrbui(); // new StringBuilder();

    $ofcTxFielDesc = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDesc());

    if (!FiString::isEmpty($ofcTxFielDesc)) {
      $methodNameStd = $iCogSpecs->checkMethodNameStd($fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldName()));

      $sbText->append(
        <<<EOD

    if(FiString.Equals(fiCol.ofcTxFieldName,$methodNameStd().ofcTxFieldName)){
      fiCol.ofcTxFieldDesc = "$ofcTxFielDesc";
    }
      
EOD
      );
    }

    return $sbText;
  }
}
