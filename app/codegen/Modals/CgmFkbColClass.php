<?php

namespace Codegen\Modals;

use Codegen\ficols\FicFiCol;
use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\Core\FiString;
use Engtuncay\Phputils8\Core\FiTemplate;
use Engtuncay\Phputils8\FiCol\FicValue;
use Engtuncay\Phputils8\FiDto\FiKeybean;
use Engtuncay\Phputils8\FiDto\FkbList;

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
  public static function actGenClassByFkbList(FkbList $fkbList, ICogFicSpecs $iCogSpecs = null): string
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

    $templateFiColMethod = $iCogSpecs->getTemplateFkbColMethod();
    //$templateFiColMethodExtra = $iFiColClass->getTemplateFiColMethodExtra();

    /**
     * @var FiKeybean $fkbItem
     */
    foreach ($fkbList as $fkbItem) {

      /**
       * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
       */
      $sbFiColMethodBody = $iCogSpecs->genFkbColMethodBodyDetail($fkbItem); //StringBuilder

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
      $fkbFiColMethodBody->add("fiColMethodBody", $sbFiColMethodBody->toString());

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
      $methodName = $iCogSpecs->checkMethodNameStd($fieldName);

      if (!$ofcBoTransient === true) {
        $iCogSpecs->doNonTransientFieldOps($sbFclListBody, $methodName);
        //sbFclListBody.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      } else {
        $iCogSpecs->doTransientFieldOps($sbFclListBodyTrans, $methodName);
        //sbFclListBodyTrans.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      }

      //$index++;
    }

    // String
    $tempGenFiCols = $iCogSpecs->getTempGenFkbColsList();

    // String
    $txResGenTableColsMethod = FiTemplate::replaceParams($tempGenFiCols, FiKeybean::bui()->buiPut("ficListBody", $sbFclListBody->toString()));

    $sbClassBody->append("\n")->append($txResGenTableColsMethod)->append("\n");

    // String
    $tempGenFiColsTrans = $iCogSpecs->getTempGenFkbColsTransList();

    // String
    $txResGenTableColsMethodTrans = FiTemplate::replaceParams($tempGenFiColsTrans, FiKeybean::bui()->buiPut("ficListBodyTrans", $sbFclListBodyTrans->toString()));
    $sbClassBody->append("\n")->append($txResGenTableColsMethodTrans)->append("\n");

    //$tempGenFiColsExt = $iCogSpecs->getTempGenFiColsExtraList();

    //$txResGenTableColsMethodExtra = FiTemplate::replaceParams($tempGenFiColsExt, FiKeybean::bui()->buiPut("ficListBodyExtra", $sbFclListBodyExtra->toString()));
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
    //$fkbParamsMain->add("addFieldDescDetail", $sbFiColAddDescDetail->toString());

    // String
    $templateMain = $iCogSpecs->getTemplateFkbColClass();
    $txResult = FiTemplate::replaceParams($templateMain, $fkbParamsMain);

    return $txResult;
  }
}
