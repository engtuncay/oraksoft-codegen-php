<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiBool;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCols\FicValue;
use Engtuncay\Phputils8\FiCores\FiTemplate;
use Engtuncay\Phputils8\FiDtos\Fkb;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCodeTemp;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

class CogTsFkbCol implements ICogGenClassCode
{
  public function genClassCode(FkbList $fkbList): string
  {
    $iCogSpecs = new CogSpecsTs();

    $sbClassBlock = new FiStrbui();
    $sbFiColMethodsBody = new FiStrbui();

    //int
    //$index = 0;

    $sbFclListBody = new FiStrbui();
    $sbGetTableColsTransContent = new FiStrbui();
    $sbFkbFields = new FiStrbui();

    $tempFiColMethod = $this->getTemplateColMethod();

    /**
     * @var Fkb $fkbItem
     */
    foreach ($fkbList as $fkbItem) {
      //self::processFkbItemMain($this, $fkbItem, $iCogSpecs, $tempFiColMethod, $sbFiColMethodsBody, $sbFclListBody, $sbGetTableColsTransContent, $sbFkbFields);

      /**
       * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
       */
      $sbFkbColMethodContent = $this->processFkbColMetContent($fkbItem);

      //Fkb
      $fkbParamsFkbColMet = new Fkb();

      //String
      $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
      $fcTxHeader = FiString::orEmpty($fkbItem->getValueByFiCol(FicFiCol::fcTxHeader()));

      //fkbFiColMethodBody.add("fieldMethodName", FiString.capitalizeFirstLetter(fieldName));
      $fkbParamsFkbColMet->add("fieldMethodName", $iCogSpecs->checkMethodNameStd($fieldName));
      $fkbParamsFkbColMet->add("fieldName", $fieldName);
      $fkbParamsFkbColMet->add("fieldHeader", $fcTxHeader);
      $fkbParamsFkbColMet->add("fkbColMethodBody", $sbFkbColMethodContent->toString());

      /**
       * @var string $txFiColMethod
       */
      $txFiColMethod = FiTemplate::replaceParams($tempFiColMethod, $fkbParamsFkbColMet);

      $sbFiColMethodsBody->append($txFiColMethod)->append("\n\n");

      //
      $fcBoTransient = FicValue::toBool($fkbItem->getValueByFiCol(FicFiCol::fcBoTransient()));
      //$methodName = $iCogSpecs->checkMethodNameStd($fieldName);

      if (!$fcBoTransient === true) {
        $this->doNonTransientFieldOps($sbFclListBody,  $fkbItem, $iCogSpecs);
        //sbFclListBody.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      } else {
        $this->doTransientFieldOps($sbGetTableColsTransContent, $fkbItem, $iCogSpecs);
        //sbFclListBodyTrans.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      }

      $this->prepBodyGenFkbFields($sbFkbFields, $fkbItem, $iCogSpecs);


      //$index++;
    }

    // String
    $txGenTableColsMethod = FiTemplate::replaceParams($this->getTempGetTableColsMet(), Fkb::bui()->buiPut("fkbListBody", $sbFclListBody->toString()));

    $sbClassBlock->append("\n")->append($txGenTableColsMethod)->append("\n");

    // String
    $txGenTableColsMethodTrans = FiTemplate::replaceParams($this->getTemplateColListTransMethod(), Fkb::bui()->buiPut("fkbListBodyTrans", $sbGetTableColsTransContent->toString()));

    $sbClassBlock->append("\n")->append($txGenTableColsMethodTrans)->append("\n");

    $txGenFkbFields = FiTemplate::replaceParams($this->getTemplateGenFkbFields(), Fkb::bui()->buiPut("genFkbFieldsBlock", $sbFkbFields->toString()));

    $sbClassBlock->append("\n")->append($txGenFkbFields)->append("\n");

    //$tempGenFiColsExt = $iCogSpecs->getTempGenFiColsExtraList();

    //$txResGenTableColsMethodExtra = FiTemplate::replaceParams($tempGenFiColsExt, Fkb::bui()->buiPut("fkbListBodyExtra", $sbFclListBodyExtra->toString()));
    //$sbClassBlock->append("\n")->append($txResGenTableColsMethodExtra)->append("\n");

    $sbClassBlock->append("\n");
    $sbClassBlock->append($sbFiColMethodsBody->toString());

    // Fkc: Fkb Col
    $classPref = "Fkc";

    // String
    $txEntityName = $fkbList->get(0)?->getFimValue(FimFiCol::fcTxEntityName());

    $txTablePrefix = $fkbList->get(0)?->getFimValue(FimFiCol::fcTxPrefix());
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.fcTxEntityName());
    //
    $fkbParamsClass = new Fkb();
    $fkbParamsClass->addFim(FimFiCodeTemp::classPref(), $classPref);
    $fkbParamsClass->addFim(FimFiCodeTemp::entityName(), $iCogSpecs->checkClassNameStd($txEntityName));
    $fkbParamsClass->addFim(FimFiCodeTemp::tableName(), $txEntityName);
    $fkbParamsClass->addFim(FimFiCodeTemp::tablePrefix(), $txTablePrefix);
    $fkbParamsClass->addFim(FimFiCodeTemp::classBody(), $sbClassBlock->toString());
    //$sbFiColAddDescDetail->toString()
    $fkbParamsClass->add("addFieldDescDetail", "");

    // String
    $templateMain = $this->getTemplateColClass();
    $txResult = FiTemplate::replaceParams($templateMain, $fkbParamsClass);

    return $txResult;
  }

  public function getTemplateColClass(): string
  {
    //String
    $templateMain = <<<EOD
import { Fkb, FkbList, FimFiCol } from 'orak-util-ts';

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
public static {{fieldMethodName}}(): Fkb {
  let fkbCol = new Fkb();
{{fkbColMethodBody}}
  return fkbCol;
}
EOD;
  }

  public function getTemplateColMethodExtra(): string
  {
    return <<<EOD
public static {{fieldMethodName}}Ext(): Fkb
{
  let fkbCol = {{fieldMethodName}}();
{{fkbColMethodExtraBody}}
  return fkbCol;
}
EOD;
  }


  public function processFkbColMetContent(Fkb $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFkbColMethodBody = new FiStrbui(); // new StringBuilder();

    //String
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::fcTxFieldType()));
    $fcTxFieldName = $fkbItem->getValueByFiMeta(FimFiCol::fcTxFieldName());
    if ($fcTxFieldName != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFim(FimFiCol.fcTxFieldName(), \"%s\");\n", $fcTxFieldName));
    }


    $fcTxHeader = $fkbItem->getValueByFiMeta(FimFiCol::fcTxHeader());
    if ($fcTxHeader != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFim(FimFiCol.fcTxHeader(), \"%s\");\n", $fcTxHeader));
    }

    $fcTxFieldType = $fkbItem->getValueByFiMeta(FimFiCol::fcTxFieldType());
    if ($fcTxFieldType != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFim(FimFiCol.fcTxFieldType(), \"%s\");\n", $fcTxFieldType));
    }

    $fcTxDbField = $fkbItem->getValueByFiMeta(FimFiCol::fcTxDbField());
    if ($fcTxDbField != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFim(FimFiCol.fcTxDbField(), \"%s\");\n", $fcTxDbField));
    }

    $fcTxRefField = $fkbItem->getValueByFiMeta(FimFiCol::fcTxRefField());
    if ($fcTxRefField != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFim(FimFiCol.fcTxRefField(), \"%s\");\n", $fcTxRefField));
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
      $sbFkbColMethodBody->append("  fkbCol.addFim(FimFiCol.fcBoTransient(), true );\n");
    }

    $fcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnLength()));
    if ($fcLnLength != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFim(FimFiCol.fcLnLength(), %s);\n", $fcLnLength));
      // $sbFkbColMethodBody->append(sprintf("  fkbCol.fcLnLength = %s;\n", $fcLnLength));
    }

    $fcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnPrecision()));
    if ($fcLnPrecision != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFim(FimFiCol.fcLnPrecision(), %s);\n", $fcLnPrecision));
    }

    $fcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnScale()));
    if ($fcLnScale != null) {
      $sbFkbColMethodBody->append(sprintf("  fkbCol.addFim(FimFiCol.fcLnScale(), %s);\n", $fcLnScale));
    }

    if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoNullable()))) {
      $sbFkbColMethodBody->append("  fkbCol.addFim(FimFiCol.fcBoNullable(), false);\n");
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

  public function genColMethodBodyDetailExtra(Fkb $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    $fcTxDesc = $fkbItem->getValueByFiCol(FicFiCol::fcTxDesc());
    //if ($fcTxDesc != null)
    $sbFiColMethodBody->append(sprintf("  fiCol.fcTxDesc = \"%s\";\n", $fcTxDesc));

    return $sbFiColMethodBody;
  }

  // public function genFiMetaMethodBodyFieldDefs(Fkb $fkb): FiStrbui
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
  public function getTempGetTableColsMet(): string
  {
    return <<<EOD
public static getTableCols(): FkbList {
  let fkbList = new FkbList();

  {{fkbListBody}}

  return fkbList;
}
EOD;
  }

  /**
   * @param FiStrbui $sbFclListBody
   * @param Fkb $fkbItem
   * @param ICogSpecs $iCogSpecs
   * @return void
   */
  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, Fkb $fkbItem, ICogSpecs $iCogSpecs): void
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
  public function doTransientFieldOps(FiStrbui $sbContent, Fkb $fkbItem, ICogSpecs $iCogSpecs): void
  {
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $className = $iCogSpecs->checkClassNameStd($fkbItem->getValueByFiMeta(FimFiCol::fcTxEntityName()));
    // URFIX Fkc dinamik olarak alınmalı
    $sbContent->append("fkbList.add(Fkc$className.$methodName());\n");
  }

  public function genFiColAddDescDetail(Fkb $fkbItem, ICogSpecs $iCogSpecs): FiStrbui
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

  public function prepBodyGenFkbFields(FiStrbui $sbContent, Fkb $fkbItem, ICogSpecs $iCogSpecs): void
  {
    // will be implemented
  }

}
