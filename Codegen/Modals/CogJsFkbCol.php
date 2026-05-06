<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiBool;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCols\FicValue;
use Engtuncay\Phputils8\FiCores\FiTemplate;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCodeTemp;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

class CogJsFkbCol implements ICogGenClassCode
{
  public function genClassCode(FkbList $fkbList): string
  {

    $iCogSpecs = new CogSpecsJs();

    $sbClassBlock = new FiStrbui(); //new StringBuilder();
    $sbFiColMethodsBody = new FiStrbui(); //new StringBuilder();

    //int
    //$index = 0;

    $sbFclListBody = new FiStrbui();
    //$sbFclListBodyExtra = new FiStrbui();
    $sbFclListBodyTrans = new FiStrbui();
    //$sbFiColAddDescDetail = new FiStrbui();
    $sbPrepFkbFields = new FiStrbui();

    $templateFiColMethod = $this->getTemplateColMethod();
    //$templateFiColMethodExtra = $iFiColClass->getTemplateFiColMethodExtra();

    /**
     * @var FiKeybean $fkbItem
     */
    foreach ($fkbList as $fkbItem) {
      self::processFkbItem($this, $fkbItem, $iCogSpecs, $templateFiColMethod, $sbFiColMethodsBody, $sbFclListBody, $sbFclListBodyTrans, $sbPrepFkbFields);
      //$index++;
    }

    // String
    $txGenTableColsMethod = FiTemplate::replaceParams($this->getTemplateColListMethod(), FiKeybean::bui()->buiPut("fkbListBody", $sbFclListBody->toString()));

    $sbClassBlock->append("\n")->append($txGenTableColsMethod)->append("\n");

    // String
    $txGenTableColsMethodTrans = FiTemplate::replaceParams($this->getTemplateColListTransMethod(), FiKeybean::bui()->buiPut("fkbListBodyTrans", $sbFclListBodyTrans->toString()));

    $sbClassBlock->append("\n")->append($txGenTableColsMethodTrans)->append("\n");

    $txGenFkbFields = FiTemplate::replaceParams($this->getTemplateGenFkbFields(), FiKeybean::bui()->buiPut("genFkbFieldsBlock", $sbPrepFkbFields->toString()));

    $sbClassBlock->append("\n")->append($txGenFkbFields)->append("\n");

    //$tempGenFiColsExt = $iCogSpecs->getTempGenFiColsExtraList();

    //$txResGenTableColsMethodExtra = FiTemplate::replaceParams($tempGenFiColsExt, FiKeybean::bui()->buiPut("fkbListBodyExtra", $sbFclListBodyExtra->toString()));
    //$sbClassBlock->append("\n")->append($txResGenTableColsMethodExtra)->append("\n");

    $sbClassBlock->append("\n");
    $sbClassBlock->append($sbFiColMethodsBody->toString());

    // Fkc: FiKeybean Col
    $classPref = "Fkc";

    // String
    $txEntityName = $fkbList->get(0)?->getFimValue(FimFiCol::fcTxEntityName());

    $txTablePrefix = $fkbList->get(0)?->getFimValue(FimFiCol::fcTxPrefix());
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.fcTxEntityName());
    //
    $fkbParamsMain = new FiKeybean();
    $fkbParamsMain->addFim(FimFiCodeTemp::classPref(), $classPref);
    $fkbParamsMain->addFim(FimFiCodeTemp::entityName(), $iCogSpecs->checkClassNameStd($txEntityName));
    $fkbParamsMain->addFim(FimFiCodeTemp::tableName(), $txEntityName);
    $fkbParamsMain->addFim(FimFiCodeTemp::tablePrefix(), $txTablePrefix);
    $fkbParamsMain->addFim(FimFiCodeTemp::classBody(), $sbClassBlock->toString());
    //$sbFiColAddDescDetail->toString()
    $fkbParamsMain->add("addFieldDescDetail", "");
    $sbClassBlockExtra = $this->genClassBlockExtra($iCogSpecs, $fkbList);

    $sbClassBlockExtra->prepend("// Extra \n\n");

    $fkbParamsMain->addFim(FimFiCodeTemp::classBlockExtra(), $sbClassBlockExtra->toString());

    $sbClassBlockExtra = $this->genClassBlockExtra($iCogSpecs, $fkbList);


    // String
    $templateMain = $this->getTemplateColClass();
    $txResult = FiTemplate::replaceParams($templateMain, $fkbParamsMain);

    return $txResult;
  }

  public function getTemplateColClass(): string
  {
    //String
    $templateMain = <<<EOD
import { FiKeybean, FkbList, FimFiCol } from '../orak_modules/orak-util-js/orak-util-js.js';

export class {{classPref}}{{entityName}} {

  // return string
  static getTxTableName() {
    return "{{tableName}}";
  }
  
  // return string
  getITxTableName() {
    return {{classPref}}{{entityName}}.getTxTableName();
  }

  // return FkbList
  genITableCols() {
    return {{classPref}}{{entityName}}.genTableCols();
  }

  // return FkbList
  genITableColsTrans() {
    return {{classPref}}{{entityName}}.genTableColsTrans();
  }

  // return string
  static getTxPrefix() {
    return "{{tablePrefix}}";
  }

  getITxPrefix() {
    return {{classPref}}{{entityName}}.getTxPrefix();
  }

  // param FkbList
  static addFieldDesc(fkbList) {

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
// return FiKeybean
static {{fieldMethodName}}() {
  let fkbCol = new FiKeybean();
{{fkbColMethodBody}}
  return fkbCol;
}
EOD;
  }

  public function getTemplateColMethodExtra(): string
  {
    return <<<EOD
// return FiKeybean
public static {{fieldMethodName}}Ext()
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
  public function getTemplateColsExtraList(): string
  {
    return <<<EOD
// return FkbList
static genTableColsExtra()  {
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
    // return FkbList
static genTableColsTrans() { 
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
// return FkbList
static genTableCols() {
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
   * @param FiStrbui $sbContent
   * @param string $methodName
   * @return void
   */
  public function doTransientFieldOps(FiStrbui $sbContent, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $className = $iCogSpecs->checkClassNameStd($fkbItem->getValueByFiMeta(FimFiCol::fcTxEntityName()));
    // URFIX Fkc dinamik olarak alınmalı
    $sbContent->append("fkbList.add(Fkc$className.$methodName());\n");
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

  public function getTemplateGenFkbFields(): string
  {
    return "";
  }

  public function prepBodyGenFkbFields(FiStrbui $sbContent, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    // will be implemented
  }

  public function genClassBlockExtra(ICogSpecs $iCogSpecs, FkbList $fkbList): FiStrbui
  {
    return new FiStrbui();
  }

    public function processFkbItem($iSpecsFkbCol, $fkbItem, $iCogSpecs, $templateFiColMethod, $sbFiColMethodsBody, $sbFclListBody, $sbFclListBodyTrans, $sbPrepFkbFields)
  {
    /**
     * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
     */
    $sbFiColMethodBody = $iSpecsFkbCol->genColMethodBody($fkbItem); //StringBuilder

    //$sbFiColAddDescDetail->append($iCogSpecs->genFiColAddDescDetail($fkbItem)->toString());

    //FiKeybean
    $fkbFiColMethodBody = new FiKeybean();

    //String
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $fcTxHeader = FiString::orEmpty($fkbItem->getValueByFiCol(FicFiCol::fcTxHeader()));

    //fkbFiColMethodBody.add("fieldMethodName", FiString.capitalizeFirstLetter(fieldName));
    $fkbFiColMethodBody->add("fieldMethodName", $iCogSpecs->checkMethodNameStd($fieldName));
    $fkbFiColMethodBody->add("fieldName", $fieldName);
    $fkbFiColMethodBody->add("fieldHeader", $fcTxHeader);
    $fkbFiColMethodBody->add("fkbColMethodBody", $sbFiColMethodBody->toString());

    /**
     * @var string $txFiColMethod
     */
    $txFiColMethod = FiTemplate::replaceParams($templateFiColMethod, $fkbFiColMethodBody);

    $sbFiColMethodsBody->append($txFiColMethod)->append("\n\n");

    //$sbFiColMethodBodyExtra = $iFiColClass->genFiColMethodBodyDetailExtra($fkbItem);
    //      $fkbFiColMethodBodyExtra = new FiKeybean();
    //      $fkbFiColMethodBodyExtra->add("fieldMethodName", $iFiColClass->checkMethodNameStd($fieldName));
    //      $fkbFiColMethodBodyExtra->add("fieldName", $fieldName);
    //      $fkbFiColMethodBodyExtra->add("fieldHeader", $fcTxHeader);
    //      $fkbFiColMethodBodyExtra->add("fiColMethodBody", $sbFiColMethodBodyExtra->toString());
    //      $txFiColMethodExtra = FiTemplate::replaceParams($templateFiColMethodExtra, $fkbFiColMethodBodyExtra);

    //      $sbFiColMethodsBody->append($txFiColMethodExtra)->append("\n\n");

    //
    $fcBoTransient = FicValue::toBool($fkbItem->getValueByFiCol(FicFiCol::fcBoTransient()));
    //$methodName = $iCogSpecs->checkMethodNameStd($fieldName);

    if (!$fcBoTransient === true) {
      $iSpecsFkbCol->doNonTransientFieldOps($sbFclListBody,  $fkbItem, $iCogSpecs);
      //sbFclListBody.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
    } else {
      $iSpecsFkbCol->doTransientFieldOps($sbFclListBodyTrans, $fkbItem, $iCogSpecs);
      //sbFclListBodyTrans.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
    }

    $iSpecsFkbCol->prepBodyGenFkbFields($sbPrepFkbFields, $fkbItem, $iCogSpecs);
  }
}
