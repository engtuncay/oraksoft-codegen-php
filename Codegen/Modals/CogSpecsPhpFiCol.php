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
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::fcTxFieldType()));
    //$txKey = $fkb->getValueByFiCol(FicFiMeta::txKey());
    //if ($txKey != null) {
    //  $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
    //}

    $fcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::fcTxHeader());
    if ($fcTxHeader != null)
      $sbFiColMethodBody->append(sprintf("  \$fiCol->fcTxHeader = '%s';\n", $fcTxHeader));

    $fcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldType());
    if ($fcTxFieldType != null)
      $sbFiColMethodBody->append(sprintf("  \$fiCol->fcTxFieldType = '%s';\n", $fcTxFieldType));

    // $fcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::fcTxDbField());
    // if ($fcTxDbField != null)
    //   $sbFiColMethodBody->append(sprintf("  fiCol.fcTxDbField = \"%s\";\n", $fcTxDbField));

    // {
    //   $fcTxRefField = $fkbItem->getValueByFiCol(FicFiCol::fcTxRefField());
    //   if ($fcTxRefField != null)
    //     $sbFiColMethodBody->append(sprintf("  \$fiCol->fcTxRefField = \"%s\";\n", $fcTxRefField));
    // }


    //$fcTxIdType = $fiCol->fcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->fcTxIdType);

    // if (!FiString.isEmpty(ofiTxIdType)) {
    // sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
    // sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
    // }

    // $fcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoTransient());
    // if ($fcBoTransient) {
    //   $sbFiColMethodBody->append("  fiCol.fcBoTransient = true;\n");
    // }

    // $fcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnLength()));
    // if ($fcLnLength != null) {
    //   $sbFiColMethodBody->append(sprintf("  fiCol.fcLnLength = %s;\n", $fcLnLength));
    // }

    // $fcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnPrecision()));
    // if ($fcLnPrecision != null) {
    //   $sbFiColMethodBody->append(sprintf("  fiCol.fcLnPrecision = %s;\n", $fcLnPrecision));
    // }

    // $fcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnScale()));
    // if ($fcLnScale != null) {
    //   $sbFiColMethodBody->append(sprintf("  fiCol.fcLnScale = %s;\n", $fcLnScale));
    // }

    // if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoNullable()))) {
    //   $sbFiColMethodBody->append("  fiCol.fcBoNullable = false;\n");
    // }


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

    return $sbFiColMethodBody;
  }

  // public function genFkbColMethodBodyDetail(FiKeybean $fkbItem): FiStrbui
  // {
  //   //StringBuilder
  //   $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

  //   //String
  //   //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::fcTxFieldType()));
  //   //$txKey = $fkb->getValueByFiCol(FicFiMeta::txKey());
  //   //if ($txKey != null) {
  //   //  $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
  //   //}

  //   //    $fkbCol->addFiCol(FicFiCol::fcTxFieldName(),'evsTxMustKod'); // "fcTxFieldType", 'string'); //->fcTxFieldType = 'string';
  //   // $fkbCol->addFiCol(FicFiCol::fcTxFieldType(), 'string'); //->fcTxFieldType = 'string';

  //   $fcTxFieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
  //   if ($fcTxFieldName != null)
  //     $sbFiColMethodBody->append(sprintf("  \$fkbCol->addFm(FimFiCol::fcTxFieldName(), '%s');\n", $fcTxFieldName));

  //   $fcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::fcTxHeader());
  //   if ($fcTxHeader != null)
  //     $sbFiColMethodBody->append(sprintf("  \$fkbCol->addFm(FimFiCol::fcTxHeader(), '%s');\n", $fcTxHeader));

  //   $fcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldType());
  //   if ($fcTxFieldType != null)
  //     $sbFiColMethodBody->append(sprintf("  \$fkbCol->addFm(FimFiCol::fcTxFieldType(), '%s');\n", $fcTxFieldType));

  //   // $fcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::fcTxDbField());
  //   // if ($fcTxDbField != null)
  //   //   $sbFiColMethodBody->append(sprintf("  fiCol.fcTxDbField = \"%s\";\n", $fcTxDbField));

  //   // {
  //   //   $fcTxRefField = $fkbItem->getValueByFiCol(FicFiCol::fcTxRefField());
  //   //   if ($fcTxRefField != null)
  //   //     $sbFiColMethodBody->append(sprintf("  \$fiCol->fcTxRefField = \"%s\";\n", $fcTxRefField));
  //   // }


  //   //$fcTxIdType = $fiCol->fcTxIdType;
  //   //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->fcTxIdType);

  //   // if (!FiString.isEmpty(ofiTxIdType)) {
  //   // sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
  //   // sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
  //   // }

  //   // $fcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoTransient());
  //   // if ($fcBoTransient) {
  //   //   $sbFiColMethodBody->append("  fiCol.fcBoTransient = true;\n");
  //   // }

  //   // $fcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnLength()));
  //   // if ($fcLnLength != null) {
  //   //   $sbFiColMethodBody->append(sprintf("  fiCol.fcLnLength = %s;\n", $fcLnLength));
  //   // }

  //   // $fcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnPrecision()));
  //   // if ($fcLnPrecision != null) {
  //   //   $sbFiColMethodBody->append(sprintf("  fiCol.fcLnPrecision = %s;\n", $fcLnPrecision));
  //   // }

  //   // $fcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnScale()));
  //   // if ($fcLnScale != null) {
  //   //   $sbFiColMethodBody->append(sprintf("  fiCol.fcLnScale = %s;\n", $fcLnScale));
  //   // }

  //   // if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoNullable()))) {
  //   //   $sbFiColMethodBody->append("  fiCol.fcBoNullable = false;\n");
  //   // }


  //   //    if (FiBool::isTrue($fiCol->fcBoNullable)) {
  //   //      $sbFiColMethodBody->append("fiCol.fcBoNullable = true;\n");
  //   //    }

  //   //        if (FiBool.isTrue(fiCol.getFcBoUnique())) {
  //   //          sbFiColMethodBody.append("\tfiCol.fcBoUnique = true;\n");
  //   //        }
  //   //
  //   //        if (FiBool.isTrue(fiCol.getFcBoUniqGro1())) {
  //   //          sbFiColMethodBody.append("\tfiCol.fcBoUniqGro1 = true;\n");
  //   //        }
  //   //
  //   //        if (FiBool.isTrue(fiCol.getFcBoUtfSupport())) {
  //   //          sbFiColMethodBody.append("\tfiCol.fcBoUtfSupport = true;\n");
  //   //        }
  //   //
  //   //        if (!FiString.isEmpty(fiCol.getFcTxDefValue())) {
  //   //          sbFiColMethodBody.append(String.format("\tfiCol.fcTxDefValue = \"%s\";\n", fiCol.getFcTxDefValue()));
  //   //        }
  //   //
  //   //        if (FiBool.isTrue(fiCol.getBoFilterLike())) {
  //   //          sbFiColMethodBody.append("\tfiCol.fcBoFilterLike = true;\n");
  //   //        }
  //   //
  //   //        // fcTxCollation	fcTxTypeName

  //   return $sbFiColMethodBody;
  // }

  // public function genFiMetaMethodBody(FiKeybean $fkbItem): FiStrbui
  // {
  //   //StringBuilder
  //   $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

  //   //String
  //   //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::fcTxFieldType()));
  //   //$txKey = $fkb->getValueByFiCol(FicFiMeta::txKey());
  //   //if ($txKey != null) {
  //   //  $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
  //   //}

  //   // $fcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::fcTxHeader());
  //   // if ($fcTxHeader != null)
  //   //   $sbFiColMethodBody->append(sprintf(" \$fiCol->fcTxHeader = '%s';\n", $fcTxHeader));

  //   // $fcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldType());
  //   // if ($fcTxFieldType != null)
  //   //   $sbFiColMethodBody->append(sprintf("  \$fiCol->fcTxFieldType = '%s';\n", $fcTxFieldType));

  //   // $fcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::fcTxDbField());
  //   // if ($fcTxDbField != null)
  //   //   $sbFiColMethodBody->append(sprintf("  fiCol.fcTxDbField = \"%s\";\n", $fcTxDbField));

  //   // {
  //   //   $fcTxRefField = $fkbItem->getValueByFiCol(FicFiCol::fcTxRefField());
  //   //   if ($fcTxRefField != null)
  //   //     $sbFiColMethodBody->append(sprintf("  fiCol.fcTxRefField = \"%s\";\n", $fcTxRefField));
  //   // }


  //   //$fcTxIdType = $fiCol->fcTxIdType;
  //   //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->fcTxIdType);

  //   // if (!FiString.isEmpty(ofiTxIdType)) {
  //   // sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
  //   // sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
  //   // }

  //   // $fcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoTransient());
  //   // if ($fcBoTransient) {
  //   //   $sbFiColMethodBody->append("  fiCol.fcBoTransient = true;\n");
  //   // }

  //   // $fcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnLength()));
  //   // if ($fcLnLength != null) {
  //   //   $sbFiColMethodBody->append(sprintf("  fiCol.fcLnLength = %s;\n", $fcLnLength));
  //   // }

  //   // $fcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnPrecision()));
  //   // if ($fcLnPrecision != null) {
  //   //   $sbFiColMethodBody->append(sprintf("  fiCol.fcLnPrecision = %s;\n", $fcLnPrecision));
  //   // }

  //   // $fcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnScale()));
  //   // if ($fcLnScale != null) {
  //   //   $sbFiColMethodBody->append(sprintf("  fiCol.fcLnScale = %s;\n", $fcLnScale));
  //   // }

  //   // if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoNullable()))) {
  //   //   $sbFiColMethodBody->append("  fiCol.fcBoNullable = false;\n");
  //   // }


  //   //    if (FiBool::isTrue($fiCol->fcBoNullable)) {
  //   //      $sbFiColMethodBody->append("fiCol.fcBoNullable = true;\n");
  //   //    }

  //   //        if (FiBool.isTrue(fiCol.getFcBoUnique())) {
  //   //          sbFiColMethodBody.append("\tfiCol.fcBoUnique = true;\n");
  //   //        }
  //   //
  //   //        if (FiBool.isTrue(fiCol.getFcBoUniqGro1())) {
  //   //          sbFiColMethodBody.append("\tfiCol.fcBoUniqGro1 = true;\n");
  //   //        }
  //   //
  //   //        if (FiBool.isTrue(fiCol.getFcBoUtfSupport())) {
  //   //          sbFiColMethodBody.append("\tfiCol.fcBoUtfSupport = true;\n");
  //   //        }
  //   //
  //   //        if (!FiString.isEmpty(fiCol.getFcTxDefValue())) {
  //   //          sbFiColMethodBody.append(String.format("\tfiCol.fcTxDefValue = \"%s\";\n", fiCol.getFcTxDefValue()));
  //   //        }
  //   //
  //   //        if (FiBool.isTrue(fiCol.getBoFilterLike())) {
  //   //          sbFiColMethodBody.append("\tfiCol.fcBoFilterLike = true;\n");
  //   //        }
  //   //
  //   //        // fcTxCollation	fcTxTypeName

  //   return $sbFiColMethodBody;
  // }

  public function genColMethodBodyDetailExtra(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    $fcTxDesc = $fkbItem->getValueByFiCol(FicFiCol::fcTxDesc());
    //if ($fcTxDesc != null)
    $sbFiColMethodBody->append(sprintf("  \$fiCol->fcTxDesc = \"%s\";\n", $fcTxDesc));

    return $sbFiColMethodBody;
  }

  // public function genFiMetaMethodBodyFieldDefs(FiKeybean $fkb): FiStrbui
  // {
  //   //StringBuilder
  //   $sbFmtMethodBodyFieldDefs = new FiStrbui();

  //   $txKey = $fkb->getValueByFiCol(FicFiMeta::ftTxKey());
  //   if ($txKey != null) {
  //     $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
  //   }

  //   $txValue = $fkb->getValueByFiCol(FicFiMeta::ftTxValue());
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
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
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
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbFclListBodyTrans->append("\$ficList->add(self::$methodName());\n");
  }

  public function genFiColAddDescMethodBody(FiKeybean $fkbItem, ICogSpecs $iCogSpecs): FiStrbui
  {
    //StringBuilder
    $sbText = new FiStrbui(); // new StringBuilder();

    $fcTxFielDesc = $fkbItem->getValueByFiCol(FicFiCol::fcTxDesc());

    if (!FiString::isEmpty($fcTxFielDesc)) {
      $methodNameStd = $iCogSpecs->checkMethodNameStd($fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName()));

      $sbText->append(
        <<<EOD

    if(FiString.Equals(fiCol.fcTxFieldName,$methodNameStd().fcTxFieldName)){
      fiCol.fcTxFieldDesc = "$fcTxFielDesc";
    }
      
EOD
      );
    }

    return $sbText;
  }
}
