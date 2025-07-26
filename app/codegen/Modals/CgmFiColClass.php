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
 * Code Generator Modal (Cgm) for FiColClass ve FiMetaClass (For All Languages)
 */
class CgmFiColClass
{

  public static function actGenFiColClassByFkb(FkbList $fkbList, ICogFicSpecs $iCogSpecs = null): string
  {

    if($iCogSpecs==null) return "";

    //if (FiCollection.isEmpty(fiCols)) return;
    $sbClassBody = new FiStrbui(); //new StringBuilder();
    $sbFiColMethodsBody = new FiStrbui(); //new StringBuilder();

    //int
    //$index = 0;

    $sbFclListBody = new FiStrbui();
    //$sbFclListBodyExtra = new FiStrbui();
    $sbFclListBodyTrans = new FiStrbui();
    $sbFiColAddDescDetail = new FiStrbui();

    $templateFiColMethod = $iCogSpecs->getTemplateFiColMethod();
    //$templateFiColMethodExtra = $iFiColClass->getTemplateFiColMethodExtra();

    /**
     * @var FiKeybean $fkbItem
     */
    foreach ($fkbList as $fkbItem) {

      /**
       * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
       */
      $sbFiColMethodBody = $iCogSpecs->genFiColMethodBodyDetail($fkbItem); //StringBuilder

      $sbFiColAddDescDetail->append($iCogSpecs->genFiColAddDescDetail($fkbItem)->toString());

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
    $tempGenFiCols = $iCogSpecs->getTempGenFiColsMethod();

    // String
    $txResGenTableColsMethod = FiTemplate::replaceParams($tempGenFiCols, FiKeybean::bui()->buiPut("ficListBody", $sbFclListBody->toString()));

    $sbClassBody->append("\n")->append($txResGenTableColsMethod)->append("\n");

    // String
    $tempGenFiColsTrans = $iCogSpecs->getTempGenGiColsTransList();

    //    String
    $txResGenTableColsMethodTrans = FiTemplate::replaceParams($tempGenFiColsTrans, FiKeybean::bui()->buiPut("ficListBodyTrans", $sbFclListBodyTrans->toString()));
    $sbClassBody->append("\n")->append($txResGenTableColsMethodTrans)->append("\n");

    $tempGenFiColsExt = $iCogSpecs->getTempGenFiColsExtraList();

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
    $templateMain = $iCogSpecs->getTemplateFicClass();
    $txResult = FiTemplate::replaceParams($templateMain, $fkbParamsMain);

    return $txResult;
  }

  // UBOM FiMeta Class Oluşturma
  public static function actGenFiMetaClassByFkb(FkbList $fkbList, ICogFicSpecs $iCogSpecs = null): string
  {

    if($iCogSpecs==null) return "";

    //if (FiCollection.isEmpty(fiCols)) return;
    $sbClassBody = new FiStrbui(); 
    $sbFiColMethodsBody = new FiStrbui();

    //int
    //$index = 0;

    $sbFclListBody = new FiStrbui();
    //$sbFclListBodyExtra = new FiStrbui();
    $sbFclListBodyTrans = new FiStrbui();
    $sbFiColAddDescDetail = new FiStrbui();

    $templateFiColMethod = $iCogSpecs->getTemplateFiColMethod();
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
    $tempGenFiCols = $iCogSpecs->getTempGenFiColsMethod();

    // String
    $txResGenTableColsMethod = FiTemplate::replaceParams($tempGenFiCols, FiKeybean::bui()->buiPut("ficListBody", $sbFclListBody->toString()));

    $sbClassBody->append("\n")->append($txResGenTableColsMethod)->append("\n");

    // String
    $tempGenFiColsTrans = $iCogSpecs->getTempGenGiColsTransList();

    // String
    $txResGenTableColsMethodTrans = FiTemplate::replaceParams($tempGenFiColsTrans, FiKeybean::bui()->buiPut("ficListBodyTrans", $sbFclListBodyTrans->toString()));
    $sbClassBody->append("\n")->append($txResGenTableColsMethodTrans)->append("\n");

    $tempGenFiColsExt = $iCogSpecs->getTempGenFiColsExtraList();

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
    $templateMain = $iCogSpecs->getTemplateFicClass();
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

//     // String
//     $tempGenFiCols = <<<EOD
// public static function GenFmtList() : FmtList {
//   \$fmtList = new FmtList();
// {{fmtListBody}}
//   return \$fmtList;
// }
// EOD;

//     // String
//     $txResGenFmtListMethod = FiTemplate::replaceParams($tempGenFiCols, FiKeybean::bui()->buiPut("fmtListBody", $sbFmtListBody->toString()));

//     $sbClassBody->append("\n")->append($txResGenFmtListMethod)->append("\n");

//     $sbClassBody->append("\n");
//     $sbClassBody->append($sbFiMetaAllMethods->toString());

//     //
//     $classPref = "Fic";
//     // URFIX entity name çekilecek
//     // String
//     $txEntityName = $fkbListExcel->get(0)?->getValueByFiCol(FicFiCol::ofcTxEntityName());
//     //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.ofcTxEntityName());
//     //
//     $fkbParamsClass = new FiKeybean();
//     $fkbParamsClass->add("classPref", $classPref);
//     $fkbParamsClass->add("entityName", self::checkClassNameStd($txEntityName));
//     $fkbParamsClass->add("classBody", $sbClassBody->toString());

//     // String
//     $tplFiMetaClass = self::getTemplateFiMetaClass();
//     $txResult = FiTemplate::replaceParams($tplFiMetaClass, $fkbParamsClass);

//     return $txResult;
//   }



//  /**
//   * @param mixed $txEntityName
//   * @return mixed
//   */
//  public static function checkClassNameStd(mixed $txEntityName): mixed
//  {
//    if (!FiString::hasLowercaseLetter($txEntityName)) {
//      $txEntityName = strtolower($txEntityName);
//    }
//
//    return ucfirst($txEntityName);
//  }

}
