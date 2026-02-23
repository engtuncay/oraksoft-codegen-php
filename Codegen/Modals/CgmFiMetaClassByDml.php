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
 * Cgm for Fmt (FiMeta)Class 
 * 
 * FiCol excelini kullanarak FiMeta class oluşturur. fcTxFieldName key, fcTxHeader da value gösterir. 
 * 
 */
class CgmFiMetaClassByDml
{


  /**
   * Generate FiMeta Class from FkbList
   *
   * @param FkbList $fkbList
   * @param CogSpecsJava| null $iCogSpecs sadece className ve methodName standartları için kullanılır
   * @param ICogSpecsGenCol|null $iSpecsFiMeta
   * @return string
   */
  public static function actGenFiMetaClassByFkl(FkbList $fkbList, ICogSpecs $iCogSpecs = null, ICogSpecsGenCol $iSpecsFiMeta = null): string
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
      $sbColMethodBody = $iSpecsFiMeta->genColMethodBody($fkbItem);

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

      $txMethodCode = FiTemplate::replaceParams($iSpecsFiMeta->getTemplateColMethod(), $fkbFiMetaMethod);

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
