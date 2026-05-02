<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCols\FicValue;
use Engtuncay\Phputils8\FiCores\FiTemplate;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCodeTemp;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

class CogSpecsPhpFkbCol implements ICogSpecsGenCol
{
  public function genClassCode(FkbList $fkbList): string
  {
    $iCogSpecs = new CogSpecsPhp();

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
    $fkbParamsMain->addFim(FimFiCodeTemp::classPref() , $classPref);
    $fkbParamsMain->addFim( FimFiCodeTemp::entityName() , $iCogSpecs->checkClassNameStd($txEntityName));
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

    return "";
  }


  public function getTemplateColMethod(): string
  {
    return <<<EOD
public static function {{fieldMethodName}}() : FiKeybean
{ 
  \$fkbCol = new FiKeybean();
{{fkbColMethodBody}}
  return \$fkbCol;
}
EOD;
  }


  public function getTemplateColClass(): string
  {
    //String
    $templateMain = <<<EOD

use Engtuncay\Phputils8\FiCols\AbsFkbTable;
use Engtuncay\Phputils8\FiCols\IFkbTableMeta;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

class {{classPref}}{{entityName}} extends AbsFkbTable implements IFkbTableMeta {

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

{{classBlockExtra}}


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

    //    $fkbCol->addFiCol(FicFiCol::fcTxFieldName(),'evsTxMustKod'); // "fcTxFieldType", 'string'); //->fcTxFieldType = 'string';
    // $fkbCol->addFiCol(FicFiCol::fcTxFieldType(), 'string'); //->fcTxFieldType = 'string';

    $fcTxFieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    if ($fcTxFieldName != null)
      $sbFiColMethodBody->append(sprintf("  \$fkbCol->addFim(FimFiCol::fcTxFieldName(), '%s');\n", $fcTxFieldName));

    $fcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::fcTxHeader());
    if ($fcTxHeader != null)
      $sbFiColMethodBody->append(sprintf("  \$fkbCol->addFim(FimFiCol::fcTxHeader(), '%s');\n", $fcTxHeader));

    $fcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldType());
    if ($fcTxFieldType != null)
      $sbFiColMethodBody->append(sprintf("  \$fkbCol->addFim(FimFiCol::fcTxFieldType(), '%s');\n", $fcTxFieldType));

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


  /**
   * @return string
   */
  public function getTemplateColListTransMethod(): string
  {
    return <<<EOD
public static function genTableColsTrans() : FkbList {
  \$fkbList = new FkbList();

  {{fkbListBodyTrans}}

  return \$fkbList;
}
EOD;
  }


  /**
   * @return string
   */
  public function getTemplateColListMethod(): string
  {
    return <<<EOD
public static function genTableCols() : FkbList {
  \$fkbList = new FkbList();

  {{fkbListBody}}

  return \$fkbList;
}
EOD;
  }

  /**
   * @param FiStrbui $sbFclListBody
   * @param string $methodName
   * @param FiStrbui $sbFclListBodyExtra
   * @return void
   */
  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void //, string $methodName
  {
    $fieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbFclListBody->append("\$fkbList->add(self::$methodName());\n");
  }


  /**
   * @param FiStrbui $sbContent
   * @param string $methodName
   * @return void
   */
  public function doTransientFieldOps(FiStrbui $sbContent, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    $fieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbContent->append("\$fkbList->add(self::$methodName());\n");
  }

  public function prepBodyGenFkbFields(FiStrbui $sbContent, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    $fieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbContent->append("\$fkb->addFieldFkb(self::$methodName(),self::$methodName());\n");
  }


  public function genFiColAddDescDetail(FiKeybean $fkbItem, ICogSpecs $iCogSpecs): FiStrbui
  {
    //StringBuilder
    $sbText = new FiStrbui(); // new StringBuilder();

    $fcTxFielDesc = $fkbItem->getFimValue(FimFiCol::fcTxDesc());

    if (!FiString::isEmpty($fcTxFielDesc)) {
      $methodNameStd = $iCogSpecs->checkMethodNameStd($fkbItem->getFimValue(FimFiCol::fcTxFieldName()));

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

  public function getTemplateGenFkbFields(): string
  {
    return <<<EOD
public static function genFkbFields() : FiKeybean {
  \$fkb = new FiKeybean();

  {{genFkbFieldsBlock}}
  return \$fkb;
}
EOD;
  }

  public function genClassBlockExtra(ICogSpecs $iCogSpecs, FkbList $fkbList): FiStrbui
  {
    $sbClassBlockExtra = new FiStrbui();

    // $templateMethodFkbTable = $this->getTemplateMethodFkbTable();

    // $sbClassBlockExtra->append($templateMethodFkbTable);

    return $sbClassBlockExtra;
  }

  public function getTemplateMethodFkbFields()
  {

    $txMethodName = CgmUtilsName::getMethodNameGetFkbFieldsAll();

    return <<<EOD
public static function {$txMethodName}() : FiKeybean {

    \$fkb = new FiKeybean();

    {{genFkbItems}}

     return \$fkb;

  } 
EOD;
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
