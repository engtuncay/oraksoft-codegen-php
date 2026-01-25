<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCores\FiTemplate;
use Engtuncay\Phputils8\FiCols\FicValue;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;

/**
 * Code Generator Modal (Cgm) for FkbColClass (For All Languages)
 * 
 * CogLangSpecs kullanarak FkbColClass oluşturur. (CogPhpSpecs gibi)
 */
class CgmFkbColClass
{

  /**
   * FkbList'ten FkbColClass oluşturur.
   *
   * @param FkbList $fkbList
   * @param CogSpecsPhp|null $iCogSpecs
   * @return string
   */
  public static function actGenClassByFkbList(FkbList $fkbList, ICogSpecs $iCogSpecs = null, ICogSpecsFkbCol $iSpecsFkbCol = null): string
  {

    if ($iCogSpecs == null) return "";

    $sbClassBody = new FiStrbui(); //new StringBuilder();
    $sbFiColMethodsBody = new FiStrbui(); //new StringBuilder();

    //int
    //$index = 0;

    $sbFclListBody = new FiStrbui();
    //$sbFclListBodyExtra = new FiStrbui();
    $sbFclListBodyTrans = new FiStrbui();
    //$sbFiColAddDescDetail = new FiStrbui();

    $templateFiColMethod = $iSpecsFkbCol->getTemplateFkbColMethod();
    //$templateFiColMethodExtra = $iFiColClass->getTemplateFiColMethodExtra();

    /**
     * @var FiKeybean $fkbItem
     */
    foreach ($fkbList as $fkbItem) {

      /**
       * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
       */
      $sbFiColMethodBody = $iSpecsFkbCol->genFkbColMethodBody($fkbItem); //StringBuilder

      //$sbFiColAddDescDetail->append($iCogSpecs->genFiColAddDescDetail($fkbItem)->toString());

      //FiKeybean
      $fkbFiColMethodBody = new FiKeybean();

      //String
      $fieldName = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldName());
      $ofcTxHeader = FiString::orEmpty($fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader()));

      //fkbFiColMethodBody.add("fieldMethodName", FiString.capitalizeFirstLetter(fieldName));
      $fkbFiColMethodBody->add("fieldMethodName", $iCogSpecs->checkMethodNameStd($fieldName));
      $fkbFiColMethodBody->add("fieldName", $fieldName);
      $fkbFiColMethodBody->add("fieldHeader", $ofcTxHeader);
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
      //      $fkbFiColMethodBodyExtra->add("fieldHeader", $ofcTxHeader);
      //      $fkbFiColMethodBodyExtra->add("fiColMethodBody", $sbFiColMethodBodyExtra->toString());
      //      $txFiColMethodExtra = FiTemplate::replaceParams($templateFiColMethodExtra, $fkbFiColMethodBodyExtra);

      //      $sbFiColMethodsBody->append($txFiColMethodExtra)->append("\n\n");

      //
      $ofcBoTransient = FicValue::toBool($fkbItem->getValueByFiCol(FicFiCol::ofcBoTransient()));
      //$methodName = $iCogSpecs->checkMethodNameStd($fieldName);

      if (!$ofcBoTransient === true) {
        $iSpecsFkbCol->doNonTransientFieldOps($sbFclListBody,  $fkbItem, $iCogSpecs);
        //sbFclListBody.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      } else {
        $iSpecsFkbCol->doTransientFieldOps($sbFclListBodyTrans, $fkbItem, $iCogSpecs);
        //sbFclListBodyTrans.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      }

      //$index++;
    }

    // String
    $tempGenFiCols = $iSpecsFkbCol->getTemplateGenTableColsMethod();

    // String
    $txResGenTableColsMethod = FiTemplate::replaceParams($tempGenFiCols, FiKeybean::bui()->buiPut("fkbListBody", $sbFclListBody->toString()));

    $sbClassBody->append("\n")->append($txResGenTableColsMethod)->append("\n");

    // String
    $tempGenFiColsTrans = $iSpecsFkbCol->getTemplateFkbColsListTransMethod();

    // String
    $txResGenTableColsMethodTrans = FiTemplate::replaceParams($tempGenFiColsTrans, FiKeybean::bui()->buiPut("fkbListBodyTrans", $sbFclListBodyTrans->toString()));
    $sbClassBody->append("\n")->append($txResGenTableColsMethodTrans)->append("\n");

    //$tempGenFiColsExt = $iCogSpecs->getTempGenFiColsExtraList();

    //$txResGenTableColsMethodExtra = FiTemplate::replaceParams($tempGenFiColsExt, FiKeybean::bui()->buiPut("fkbListBodyExtra", $sbFclListBodyExtra->toString()));
    //$sbClassBody->append("\n")->append($txResGenTableColsMethodExtra)->append("\n");

    $sbClassBody->append("\n");
    $sbClassBody->append($sbFiColMethodsBody->toString());

    // Fkc: FiKeybean Col
    $classPref = "Fkc";
    // URFIX entity name çekilecek
    // String
    $txEntityName = $fkbList->get(0)?->getValueByFiCol(FicFiCol::ofcTxEntityName());

    $txTablePrefix = $fkbList->get(0)?->getValueByFiCol(FicFiCol::ofcTxPrefix());
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.ofcTxEntityName());
    //
    $fkbParamsMain = new FiKeybean();
    $fkbParamsMain->add("classPref", $classPref);
    $fkbParamsMain->add("entityName", $iCogSpecs->checkClassNameStd($txEntityName));
    $fkbParamsMain->add("tableName", $txEntityName);
    $fkbParamsMain->add("tablePrefix", $txTablePrefix);
    $fkbParamsMain->add("classBody", $sbClassBody->toString());
    //$sbFiColAddDescDetail->toString()
    $fkbParamsMain->add("addFieldDescDetail", "");

    // String
    $templateMain = $iSpecsFkbCol->getTemplateFkbColClass();
    $txResult = FiTemplate::replaceParams($templateMain, $fkbParamsMain);

    return $txResult;
  }
}
