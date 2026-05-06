<?php

namespace Codegen\Modals;

use Codegen\FiCols\FicFiMeta;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCores\FiTemplate;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCodeTemp;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

class CogPhpFiMeta implements ICogGenClassCode
{
  public function genClassCode(FkbList $fkbList): string
  {
    $iCogSpecs = new CogSpecsPhp();

    $sbClassBody = new FiStrbui();
    $sbFiMetaMethods = new FiStrbui();

    //int
    //$index = 0;

    //$sbFiColAddDescDetail = new FiStrbui();
    //$templateFiColMethodExtra = $iFiColClass->getTemplateFiColMethodExtra();

    /**
     * @var FiKeybean $fkbItem
     */
    foreach ($fkbList as $fkbItem) {

      /**
       * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
       */
      $sbColMethodBody = $this->genColMethodBody($fkbItem);

      //$sbFiColAddDescDetail->append($iCogSpecs->genFiColAddDescDetail($fkbItem)->toString());

      //FiKeybean
      $fkbFiMetaMethod = new FiKeybean();

      //String
      $fieldName = $fkbItem->getValueByFiMeta(FimFiCol::fcTxFieldName());

      if (FiString::isEmpty($fieldName)) continue;
      //$fcTxHeader = FiString::orEmpty($fkbItem->getValueByFiCol(FicFiCol::fcTxHeader()));

      $fkbFiMetaMethod->add("fieldMethodName", $iCogSpecs->checkMethodNameStd($fieldName));
      $fkbFiMetaMethod->add("fieldName", $fieldName);
      $fkbFiMetaMethod->add("fiMethodBody", $sbColMethodBody->toString());
      //$fkbFiColMethodBody->add("fieldHeader", $fcTxHeader);

      $txMethodCode = FiTemplate::replaceParams($this->getTemplateColMethod(), $fkbFiMetaMethod);

      $sbFiMetaMethods->append($txMethodCode)->append("\n\n");

      // $fcBoTransient = FicValue::toBool($fkbItem->getValueByFiCol(FicFiCol::fcBoTransient()));
      // $methodName = $iCogSpecs->checkMethodNameStd($fieldName);

      // if (!$fcBoTransient === true) {
      //   $iCogSpecs->doNonTransientFieldOps($sbFclListBody, $methodName);
      //   //sbFclListBody.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      // } else {
      //   $iCogSpecs->doTransientFieldOps($sbFclListBodyTrans, $methodName);
      //   //sbFclListBodyTrans.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      // }

      //$index++;
    }

    // String
    //$templateFiMetaMethod = $iSpecsFiMeta->getTemplateFiMetaMethod();

    // String
    //$txGenAllFiMetasMethod = FiTemplate::replaceParams($templateFiMetaMethod, FiKeybean::bui()->buiPut("fmtListBody", $sbFclListBody->toString()));

    //$sbClassBody->append("\n")->append($txGenAllFiMetasMethod)->append("\n");

    // String
    //$tempGenFiColsTrans = $iCogSpecs->getTempGenFmtColsTransList();

    // String
    //$txResGenTableColsMethodTrans = FiTemplate::replaceParams($tempGenFiColsTrans, FiKeybean::bui()->buiPut("fmtListBodyTrans", $sbFclListBodyTrans->toString()));
    //$sbClassBody->append("\n")->append($txResGenTableColsMethodTrans)->append("\n");

    //$tempGenFiColsExt = $iCogSpecs->getTempGenFiColsExtraList();

    //$txResGenTableColsMethodExtra = FiTemplate::replaceParams($tempGenFiColsExt, FiKeybean::bui()->buiPut("ficListBodyExtra", $sbFclListBodyExtra->toString()));
    //$sbClassBody->append("\n")->append($txResGenTableColsMethodExtra)->append("\n");

    $sbClassBody->append("\n");
    $sbClassBody->append($sbFiMetaMethods->toString());

    //
    $classPref = "Fim";

    // String
    $txEntityName = $fkbList->get(0)?->getValueByFiCol(FicFiCol::fcTxEntityName());

    $txTablePrefix = $fkbList->get(0)?->getValueByFiCol(FicFiCol::fcTxPrefix());
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.fcTxEntityName());

    $sbClassBodyExtra = new FiStrbui();
    $sbClassBodyExtra->append("// Extras");

    $fkbClassParams = new FiKeybean();
    $fkbClassParams->add("classPref", $classPref);
    $fkbClassParams->add("entityName", $iCogSpecs->checkClassNameStd($txEntityName));
    $fkbClassParams->add("tableName", $txEntityName);
    $fkbClassParams->add("tablePrefix", $txTablePrefix);
    $fkbClassParams->add("classBody", $sbClassBody->toString());
    $fkbClassParams->addFim( FimFiCodeTemp::classBlockExtra() , $sbClassBodyExtra->toString() );
    //$fkbParamsMain->add("addFieldDescDetail", $sbFiColAddDescDetail->toString());

    // String
    $templateFiMetaClass = $this->getTemplateColClass();
    $txResult = FiTemplate::replaceParams($templateFiMetaClass, $fkbClassParams);

    return $txResult;

  }

  //
  public function genFiMetaMethodBodyByFiColTemp(FiKeybean $fkb): FiStrbui
  {
    return new FiStrbui();
  }

  public function getTemplateColClass(): string
  {
    //String
    $templateMain = <<<EOD
      
use Engtuncay\Phputils8\FiDtos\FiMeta;

class {{classPref}}{{entityName}} {

{{classBody}}

}
EOD;

    return $templateMain;
  }

  public function getTemplateColMethod(): string
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

  public function genColMethodBody(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbColMethodBody = new FiStrbui(); // new StringBuilder();

    //String
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::fcTxFieldType()));
    //$txKey = $fkb->getValueByFiCol(FicFiMeta::txKey());
    //if ($txKey != null) {
    //  $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
    //}

    // $fcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::fcTxHeader());
    // if ($fcTxHeader != null)
    //   $sbFiColMethodBody->append(sprintf(" \$fiCol->fcTxHeader = '%s';\n", $fcTxHeader));

    // $fcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldType());
    // if ($fcTxFieldType != null)
    //   $sbFiColMethodBody->append(sprintf("  \$fiCol->fcTxFieldType = '%s';\n", $fcTxFieldType));

    // $fcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::fcTxDbField());
    // if ($fcTxDbField != null)
    //   $sbFiColMethodBody->append(sprintf("  fiCol.fcTxDbField = \"%s\";\n", $fcTxDbField));

    // {
    //   $fcTxRefField = $fkbItem->getValueByFiCol(FicFiCol::fcTxRefField());
    //   if ($fcTxRefField != null)
    //     $sbFiColMethodBody->append(sprintf("  fiCol.fcTxRefField = \"%s\";\n", $fcTxRefField));
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

    return $sbColMethodBody;
  }

  public function genFiMetaMethodBodyFieldDefs(FiKeybean $fkb): FiStrbui
  {
    //StringBuilder
    $sbFmtMethodBodyFieldDefs = new FiStrbui();

    $txKey = $fkb->getValueByFiCol(FicFiMeta::ftTxKey());
    if ($txKey != null) {
      $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
    }

    $txValue = $fkb->getValueByFiCol(FicFiMeta::ftTxValue());
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
public static function genTableCols() : FimList {
  \$fimList = new FimList();

  {{fimListBody}}

  return \$fmtList;
}
EOD;
  }

  public function getTemplateColListMethod(): string
  {
    throw new \Exception('Not implemented');
  }

  public function getTemplateColListTransMethod(): string
  {
    throw new \Exception('Not implemented');
  }

  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    throw new \Exception('Not implemented');
  }

  public function doTransientFieldOps(FiStrbui $sbContent, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    throw new \Exception('Not implemented');
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
}
