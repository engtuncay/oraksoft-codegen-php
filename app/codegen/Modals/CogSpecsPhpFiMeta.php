<?php

namespace Codegen\Modals;

use Codegen\FiCols\FicFiMeta;
use Engtuncay\Phputils8\Core\FiBool;
use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\Core\FiString;
use Engtuncay\Phputils8\FiCol\FicFiCol;
use Engtuncay\Phputils8\FiCol\FicValue;
use Engtuncay\Phputils8\FiDto\FiKeybean;

class CogSpecsPhpFiMeta implements ICogSpecsFiMeta
{
  //
  public function genFiMetaMethodBodyByFiColTemp(FiKeybean $fkb): FiStrbui
  {
    return new FiStrbui();
  }

  public function getTemplateFiMetaClass(): string
  {
    //String
    $templateMain = <<<EOD
      
use Engtuncay\Phputils8\FiDto\FiMeta;
use Engtuncay\Phputils8\FiDto\FmtList;

class {{classPref}}{{entityName}} {

{{classBody}}

}
EOD;

    return $templateMain;
  }



  public function getTemplateFkbColMethod(): string
  {
    return <<<EOD
public static function {{fieldMethodName}}() : FiKeybean
{ 
  \$fkbCol = new FiKeybean();
{{fiColMethodBody}}
  return \$fkbCol;
}
EOD;
  }

  public function getTemplateFiMetaMethod(): string
  {
    return <<<EOD
public static function {{fieldMethodName}}() : FiMeta
{ 
  \$fiMeta = new FiMeta("{{fieldName}}");
{{fiMethodBody}}
  return \$fiMeta;
}
EOD;
  }

  public function genFiMetaMethodBody(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    //String
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::ofcTxFieldType()));
    //$txKey = $fkb->getValueByFiCol(FicFiMeta::txKey());
    //if ($txKey != null) {
    //  $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
    //}

    // $ofcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader());
    // if ($ofcTxHeader != null)
    //   $sbFiColMethodBody->append(sprintf(" \$fiCol->ofcTxHeader = '%s';\n", $ofcTxHeader));

    // $ofcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldType());
    // if ($ofcTxFieldType != null)
    //   $sbFiColMethodBody->append(sprintf("  \$fiCol->ofcTxFieldType = '%s';\n", $ofcTxFieldType));

    // $ofcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDbField());
    // if ($ofcTxDbField != null)
    //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcTxDbField = \"%s\";\n", $ofcTxDbField));

    // {
    //   $ofcTxRefField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxRefField());
    //   if ($ofcTxRefField != null)
    //     $sbFiColMethodBody->append(sprintf("  fiCol.ofcTxRefField = \"%s\";\n", $ofcTxRefField));
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
}
