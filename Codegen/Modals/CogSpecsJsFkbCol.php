<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiBool;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCols\FicValue;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

class CogSpecsJsFkbCol implements ICogSpecsGenCol
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
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::fcTxFieldType()));
    $fcTxFieldName = $fkbItem->getValueByFiMeta(FimFiCol::fcTxFieldName());
    if ($fcTxFieldName != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFieldByFiMeta(FimFiCol.fcTxFieldName(), \"%s\");\n", $fcTxFieldName));
    }


    $fcTxHeader = $fkbItem->getValueByFiMeta(FimFiCol::fcTxHeader());
    if ($fcTxHeader != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFieldByFiMeta(FimFiCol.fcTxHeader(), \"%s\");\n", $fcTxHeader));
    }

    $fcTxFieldType = $fkbItem->getValueByFiMeta(FimFiCol::fcTxFieldType());
    if ($fcTxFieldType != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFieldByFiMeta(FimFiCol.fcTxFieldType(), \"%s\");\n", $fcTxFieldType));
    }

    $fcTxDbField = $fkbItem->getValueByFiMeta(FimFiCol::fcTxDbField());
    if ($fcTxDbField != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFieldByFiMeta(FimFiCol.fcTxDbField(), \"%s\");\n", $fcTxDbField));
    }

    $fcTxRefField = $fkbItem->getValueByFiMeta(FimFiCol::fcTxRefField());
    if ($fcTxRefField != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFieldByFiMeta(FimFiCol.fcTxRefField(), \"%s\");\n", $fcTxRefField));
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
      $sbFkbColMethodBody->append("  fkbCol.addFieldByFiMeta(FimFiCol.fcBoTransient(), true );\n");
    }

    $fcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnLength()));
    if ($fcLnLength != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFieldByFiMeta(FimFiCol.fcLnLength(), %s);\n", $fcLnLength));
      // $sbFkbColMethodBody->append(sprintf("  fkbCol.fcLnLength = %s;\n", $fcLnLength));
    }

    $fcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnPrecision()));
    if ($fcLnPrecision != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFieldByFiMeta(FimFiCol.fcLnPrecision(), %s);\n", $fcLnPrecision));
    }

    $fcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnScale()));
    if ($fcLnScale != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFieldByFiMeta(FimFiCol.fcLnScale(), %s);\n", $fcLnScale));
    }

    if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoNullable()))) {
      $sbFkbColMethodBody->append("  fkbCol.addFieldByFiMeta(FimFiCol.fcBoNullable(), false);\n");
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
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $className = $iCogSpecs->checkClassNameStd($fkbItem->getValueByFiMeta(FimFiCol::fcTxEntityName()));
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
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $className = $iCogSpecs->checkClassNameStd($fkbItem->getValueByFiMeta(FimFiCol::fcTxEntityName()));
    // URFIX Fkc dinamik olarak alınmalı
    $sbFclListBodyTrans->append("fkbList.add(Fkc$className.$methodName());\n");
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
