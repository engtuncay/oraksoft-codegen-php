<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiBool;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCols\FicValue;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

class CogSpecsTsFkbCol implements ICogSpecsGenCol
{

  public function getTemplateColClass(): string
  {
    //String
    $templateMain = <<<EOD
import { FiKeybean, FkbList, FimFiCol } from 'orak-util-ts';

export class {{classPref}}{{entityName}} {

  public static getTxTableName(): string {
    return "{{tableName}}";
  }
  
  public getITxTableName(): string {
    return {{classPref}}{{entityName}}.getTxTableName();
  }

  public genITableCols(): FkbList {
    return {{classPref}}{{entityName}}.genTableCols();
  }

  public genITableColsTrans(): FkbList {
    return {{classPref}}{{entityName}}.genTableColsTrans();
  }

  public static getTxPrefix(): string {
    return "{{tablePrefix}}";
  }

  public getITxPrefix(): string {
    return {{classPref}}{{entityName}}.getTxPrefix();
  }

  public static addFieldDesc(fkbList: FkbList) {

    for (const fkb of fkbList.getArray()) {
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
public static {{fieldMethodName}}(): FiKeybean {
  let fkbCol = new FiKeybean();
{{fkbColMethodBody}}
  return fkbCol;
}
EOD;
  }

  public function getTemplateColMethodExtra(): string
  {
    return <<<EOD
public static {{fieldMethodName}}Ext(): FiKeybean
{
  let fkbCol = {{fieldMethodName}}();
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
    $ofcTxFieldName = $fkbItem->getValueByFiMeta(FimFiCol::ofcTxFieldName());
    if ($ofcTxFieldName != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFieldByFiMeta(FimFiCol.ofcTxFieldName(), \"%s\");\n", $ofcTxFieldName));
    }


    $ofcTxHeader = $fkbItem->getValueByFiMeta(FimFiCol::ofcTxHeader());
    if ($ofcTxHeader != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFieldByFiMeta(FimFiCol.ofcTxHeader(), \"%s\");\n", $ofcTxHeader));
    }

    $ofcTxFieldType = $fkbItem->getValueByFiMeta(FimFiCol::ofcTxFieldType());
    if ($ofcTxFieldType != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFieldByFiMeta(FimFiCol.ofcTxFieldType(), \"%s\");\n", $ofcTxFieldType));
    }

    $ofcTxDbField = $fkbItem->getValueByFiMeta(FimFiCol::ofcTxDbField());
    if ($ofcTxDbField != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFieldByFiMeta(FimFiCol.ofcTxDbField(), \"%s\");\n", $ofcTxDbField));
    }

    $ofcTxRefField = $fkbItem->getValueByFiMeta(FimFiCol::ofcTxRefField());
    if ($ofcTxRefField != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFieldByFiMeta(FimFiCol.ofcTxRefField(), \"%s\");\n", $ofcTxRefField));
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
      $sbFkbColMethodBody->append("  fkbCol.addFieldByFiMeta(FimFiCol.ofcBoTransient(), true );\n");
    }

    $ofcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnLength()));
    if ($ofcLnLength != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFieldByFiMeta(FimFiCol.ofcLnLength(), %s);\n", $ofcLnLength));
      // $sbFkbColMethodBody->append(sprintf("  fkbCol.ofcLnLength = %s;\n", $ofcLnLength));
    }

    $ofcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnPrecision()));
    if ($ofcLnPrecision != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFieldByFiMeta(FimFiCol.ofcLnPrecision(), %s);\n", $ofcLnPrecision));
    }

    $ofcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnScale()));
    if ($ofcLnScale != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFieldByFiMeta(FimFiCol.ofcLnScale(), %s);\n", $ofcLnScale));
    }

    if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoNullable()))) {
      $sbFkbColMethodBody->append("  fkbCol.addFieldByFiMeta(FimFiCol.ofcBoNullable(), false);\n");
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
  public function getTemplateColsExtraList(): string
  {
    return <<<EOD
public static genTableColsExtra(): FkbList {
  let fkbList = new FkbList();

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
public static genTableColsTrans(): FkbList { 
  let fkbList = new FkbList();
  
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
public static genTableCols(): FkbList {
  let fkbList = new FkbList();

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
    $className = $iCogSpecs->checkClassNameStd($fkbItem->getValueByFiMeta(FimFiCol::ofcTxEntityName()));
    // URFIX Fkc dinamik olarak alınmalı
    $sbFclListBody->append("fkbList.add(Fkc$className.$methodName());\n");
    // $sbFclListBodyExtra->append("ficList.Add($methodName" . "Ext());\n");
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
    $className = $iCogSpecs->checkClassNameStd($fkbItem->getValueByFiMeta(FimFiCol::ofcTxEntityName()));
    // URFIX Fkc dinamik olarak alınmalı
    $sbFclListBodyTrans->append("fkbList.add(Fkc$className.$methodName());\n");
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
