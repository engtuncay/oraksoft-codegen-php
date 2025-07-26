<?php

namespace Codegen\Modals;

use Codegen\ficols\FicFiCol;
use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\Core\FiString;
use Engtuncay\Phputils8\Core\FiTemplate;
use Engtuncay\Phputils8\FiDto\FiKeybean;
use Engtuncay\Phputils8\FiDto\FkbList;

/**
 * Code Generator Modal (Cgm) for FiMetaClass (For All Languages)
 */
class CgmFiMetaClass
{

  
  /**
   * Generate FiMeta Class from FkbList
   *
   * @param FkbList $fkbList
   * @param CogPhpSpecs| null $iCogSpecs
   * @return string
   */
  public static function actGenFiMetaClassByFkb(FkbList $fkbList, CogPhpSpecs $iCogSpecs = null): string
  {

    if ($iCogSpecs == null) return "";

    //if (FiCollection.isEmpty(fiCols)) return;
    $sbClassBody = new FiStrbui();
    $sbFiColMethodsBody = new FiStrbui();

    //int
    //$index = 0;

    $sbFclListBody = new FiStrbui();
    //$sbFclListBodyExtra = new FiStrbui();
    $sbFclListBodyTrans = new FiStrbui();
    $sbFiColAddDescDetail = new FiStrbui();

    $templateMethod = $iCogSpecs->getTemplateFiMetaMethod();
    //$templateFiColMethodExtra = $iFiColClass->getTemplateFiColMethodExtra();

    /**
     * @var FiKeybean $fkbItem
     */
    foreach ($fkbList as $fkbItem) {

      /**
       * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
       */
      $sbFiColMethodBody = $iCogSpecs->genFiMetaMethodBody($fkbItem); //StringBuilder

      //$sbFiColAddDescDetail->append($iCogSpecs->genFiColAddDescDetail($fkbItem)->toString());

      //FiKeybean
      $fkbFiColMethodBody = new FiKeybean();

      //String
      $fieldName = $fkbItem->getValue('ofmTxKey');
      //$ofcTxHeader = FiString::orEmpty($fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader()));
      
      $fkbFiColMethodBody->add("fieldMethodName", $iCogSpecs->checkMethodNameStd($fieldName));
      $fkbFiColMethodBody->add("fieldName", $fieldName);
      $fkbFiColMethodBody->add("fiMethodBody", $sbFiColMethodBody->toString());
      //$fkbFiColMethodBody->add("fieldHeader", $ofcTxHeader);
      

      /**
       * @var string $txFiColMethod
       */
      $txFiColMethod = FiTemplate::replaceParams($templateMethod, $fkbFiColMethodBody);

      $sbFiColMethodsBody->append($txFiColMethod)->append("\n\n");


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
    $tempGenFiCols = $iCogSpecs->getTempGenFiColsList();

    // String
    $txResGenTableColsMethod = FiTemplate::replaceParams($tempGenFiCols, FiKeybean::bui()->buiPut("ficListBody", $sbFclListBody->toString()));

    $sbClassBody->append("\n")->append($txResGenTableColsMethod)->append("\n");

    // String
    $tempGenFiColsTrans = $iCogSpecs->getTempGenGiColsTransList();

    // String
    $txResGenTableColsMethodTrans = FiTemplate::replaceParams($tempGenFiColsTrans, FiKeybean::bui()->buiPut("ficListBodyTrans", $sbFclListBodyTrans->toString()));
    $sbClassBody->append("\n")->append($txResGenTableColsMethodTrans)->append("\n");

    //$tempGenFiColsExt = $iCogSpecs->getTempGenFiColsExtraList();

    //$txResGenTableColsMethodExtra = FiTemplate::replaceParams($tempGenFiColsExt, FiKeybean::bui()->buiPut("ficListBodyExtra", $sbFclListBodyExtra->toString()));
    //$sbClassBody->append("\n")->append($txResGenTableColsMethodExtra)->append("\n");

    $sbClassBody->append("\n");
    $sbClassBody->append($sbFiColMethodsBody->toString());

    //
    $classPref = "Fic";
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
    $fkbParamsMain->add("addFieldDescDetail", $sbFiColAddDescDetail->toString());

    // String
    $templateMain = $iCogSpecs->getTemplateFiMetaClass();
    $txResult = FiTemplate::replaceParams($templateMain, $fkbParamsMain);

    return $txResult;
  }

  //   public static function actGenFiMetaClass(FkbList $fkbListExcel, ICogFicSpecs $iCogFicSpecs = null): string
  //   {
  //     //if (FiCollection.isEmpty(fiCols)) return;

  //     $sbClassBody = new FiStrbui(); //new StringBuilder();
  //     $sbFiMetaAllMethods = new FiStrbui(); //new StringBuilder();

  //     $sbFmtListBody = new FiStrbui();

  //     $templateFiMetaMethod = <<<EOD
  // public static function {{fieldMethodName}}() : FiMeta {
  //   \$fiMeta = new FiMeta();
  // {{fiColMethodBody}}
  //   return \$fiMeta;
  // }

  // EOD;

  //     /**
  //      * @var FiKeybean $fkb
  //      */
  //     foreach ($fkbListExcel as $fkb) {

  //       /**
  //        * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
  //        */
  //       $sbFmtMethodBodyFieldDefs = self::genFiMetaMethodBodyFieldDefs($fkb); //StringBuilder

  //       //FiKeybean
  //       $fkbFiMetaMethodBody = new FiKeybean();

  //       //String
  //       $fieldName = $fkb->getValueByFiCol(FicFiMeta::txKey());

  //       $fkbFiMetaMethodBody->add("fieldMethodName", $fieldName);
  //       //$fkbFiMetaMethodBody->add("fieldName", $fieldName);
  //       //$fkbFiMetaMethodBody->add("fieldHeader", $fkb->getValueByFiCol(FicFiMeta::txValue()));
  //       $fkbFiMetaMethodBody->add("fiColMethodBody", $sbFmtMethodBodyFieldDefs->toString());

  //       $txResFiMetaMethod = FiTemplate::replaceParams($templateFiMetaMethod, $fkbFiMetaMethodBody);

  //       $sbFiMetaAllMethods->append($txResFiMetaMethod)->append("\n\n");

  //       //
  //       $sbFmtListBody->append("  \$fmtList->add(self::$fieldName());\n");

  //     }


}
