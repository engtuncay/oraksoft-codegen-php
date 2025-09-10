<?php

namespace Codegen\Modals;

use Codegen\ficols\FicFiCol;
use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\Core\FiString;
use Engtuncay\Phputils8\Core\FiTemplate;
use Engtuncay\Phputils8\FiDto\FiKeybean;
use Engtuncay\Phputils8\FiDto\FkbList;
use Engtuncay\Phputils8\FiMeta\FimFiCol;

/**
 * Code Generator Modal (Cgm) for FiMetaClass (For All Languages)
 * 
 * FiCol excelini kullanarak FiMeta class oluşturur. ofcTxFieldName key, ofcTxHeader da value gösterir. 
 * 
 */
class CgmFiMetaClassByFiColTemp
{


  /**
   * Generate FiMeta Class from FkbList
   *
   * @param FkbList $fkbList
   * @param CogSpecsCsharp| null $iCogSpecs
   * @param SpecsCsharpFiMeta| null $iSpecsFiMeta
   * @return string
   */
  public static function actGenFiMetaClassByFkbList(FkbList $fkbList, ICogFicSpecs $iCogSpecs = null, ISpecsFiMeta $iSpecsFiMeta = null): string
  {

    if ($iCogSpecs == null) return "";

    //if (FiCollection.isEmpty(fiCols)) return;
    $sbClassBody = new FiStrbui();
    $sbFiMetaMethods = new FiStrbui();

    //int
    //$index = 0;

    $sbFclListBody = new FiStrbui();
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
      $sbFiColMethodBody = $iCogSpecs->genFiMetaMethodBodyByFiColTemp($fkbItem); //StringBuilder

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

      $txMethodCode = FiTemplate::replaceParams($iSpecsFiMeta->getTemplateFiMetaMethod(), $fkbFiMetaMethod);

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
    $templateFiMetaClass = $iSpecsFiMeta->getTemplateFiMetaClass();
    $txResult = FiTemplate::replaceParams($templateFiMetaClass, $fkbClassParams);

    return $txResult;
  }
}
