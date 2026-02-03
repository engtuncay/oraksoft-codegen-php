<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCores\FiTemplate;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

/**
 * Code Generator Modal (Cgm) for FiMetaClass (For All Languages)
 * 
 * FiCol excelini kullanarak FiMeta class oluşturur. ofcTxFieldName key, ofcTxHeader da value gösterir. 
 * 
 */
class CgmFiMetaClassByDmlTemplate
{


  /**
   * Generate FiMeta Class from FkbList
   *
   * @param FkbList $fkbList
   * @param CogSpecsJava| null $iCogSpecs sadece className ve methodName standartları için kullanılır
   * @param ICogSpecsGenCol|null $iSpecsFiMeta
   * @return string
   */
  public static function actGenFiMetaClassByFkbList(FkbList $fkbList, ICogSpecs $iCogSpecs = null, ICogSpecsGenCol $iSpecsFiMeta = null): string
  {

    if ($iCogSpecs == null) return "";

    //if (FiCollection.isEmpty(fiCols)) return;
    $sbClassBody = new FiStrbui();
    $sbFiMetaMethods = new FiStrbui();

    //int
    //$index = 0;

    //$sbFclListBody = new FiStrbui();
    //$sbFclListBodyExtra = new FiStrbui();
    //$sbFclListBodyTrans = new FiStrbui();
    //$sbFiColAddDescDetail = new FiStrbui();

    //$templateFiColMethodExtra = $iFiColClass->getTemplateFiColMethodExtra();

    /**
     * @var FiKeybean $fkbItem
     */
    foreach ($fkbList as $fkbItem) {

      /**
       * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
       */
      $sbFiColMethodBody = $iSpecsFiMeta->genColMethodBody($fkbItem); 

      //$sbFiColAddDescDetail->append($iCogSpecs->genFiColAddDescDetail($fkbItem)->toString());

      //FiKeybean
      $fkbFiMetaMethod = new FiKeybean();

      //String
      $fieldName = $fkbItem->getValueByFiMeta(FimFiCol::ofcTxFieldName());

      if (FiString::isEmpty($fieldName)) continue;
      //$ofcTxHeader = FiString::orEmpty($fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader()));

      $fkbFiMetaMethod->add("fieldMethodName", $iCogSpecs->checkMethodNameStd($fieldName));
      $fkbFiMetaMethod->add("fieldName", $fieldName);
      $fkbFiMetaMethod->add("fiMethodBody", $sbFiColMethodBody->toString());
      //$fkbFiColMethodBody->add("fieldHeader", $ofcTxHeader);

      $txMethodCode = FiTemplate::replaceParams($iSpecsFiMeta->getTemplateColMethod(), $fkbFiMetaMethod);

      $sbFiMetaMethods->append($txMethodCode)->append("\n\n");


      // $ofcBoTransient = FicValue::toBool($fkbItem->getValueByFiCol(FicFiCol::ofcBoTransient()));
      // $methodName = $iCogSpecs->checkMethodNameStd($fieldName);

      // if (!$ofcBoTransient === true) {
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
    $txEntityName = $fkbList->get(0)?->getValueByFiCol(FicFiCol::ofcTxEntityName());

    $txTablePrefix = $fkbList->get(0)?->getValueByFiCol(FicFiCol::ofcTxPrefix());
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.ofcTxEntityName());


    $fkbClassParams = new FiKeybean();
    $fkbClassParams->add("classPref", $classPref);
    $fkbClassParams->add("entityName", $iCogSpecs->checkClassNameStd($txEntityName));
    $fkbClassParams->add("tableName", $txEntityName);
    $fkbClassParams->add("tablePrefix", $txTablePrefix);
    $fkbClassParams->add("classBody", $sbClassBody->toString());
    //$fkbParamsMain->add("addFieldDescDetail", $sbFiColAddDescDetail->toString());

    // String
    $templateFiMetaClass = $iSpecsFiMeta->getTemplateColClass();
    $txResult = FiTemplate::replaceParams($templateFiMetaClass, $fkbClassParams);

    return $txResult;
  }
}
