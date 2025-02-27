<?php

namespace codegen\modals;

use Engtuncay\Phputils8\Core\FiArray;
use Engtuncay\Phputils8\Core\FiBool;
use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\Core\FiString;
use Engtuncay\Phputils8\Core\FiTemplate;
use Engtuncay\Phputils8\Meta\FiCol;
use Engtuncay\Phputils8\Meta\FiColList;
use Engtuncay\Phputils8\Meta\FiKeybean;
use Engtuncay\Phputils8\Meta\FkbList;
use codegen\ficols\CgmFiCol;

/**
 * Code Generator Modal for Php
 */
class CgmPhp
{
  public static function actGenFiColClassByFkbList(FkbList $fkbListExcel): string
  {

    $fiCols = CgmFiCol::getFiColListFromFkbList($fkbListExcel);

    //if (FiCollection.isEmpty(fiCols)) return;


//        $buffer[] = "Merhaba";
//        $buffer[] = " Dünya!";
    // Tüm parçaları tek seferde birleştir
    //$result = implode('', $buffer);

    //fiCol.buiColType(OzColType.{{fieldType}});
    $sbClassBody = new FiStrbui(); //new StringBuilder();
    $sbFiColMethodsBody = new FiStrbui(); //new StringBuilder();

    //int
    $index = 0;

    $sbFieldColsAddition = new FiStrbui();
    $sbFieldColsAdditionTrans = new FiStrbui();

    $templateFiColMethod = self::getTemplateFiColMethod();

    /**
     * @var FiCol $fiCol
     */
    foreach ($fiCols as $fiCol) {

      //StringBuilder
      $sbFiColMethodBody = self::genFiColMethodBodyDetail($fiCol);

      //FiKeyBean
      $fkbParamsFiColMethod = new FiKeybean();

      //String
      $fieldName = $fiCol->ofcTxFieldName;
      //fkbParamsFiColMethod.add("fieldMethodName", FiString.capitalizeFirstLetter(fieldName));
      $fkbParamsFiColMethod->add("fieldMethodName", $fieldName);
      $fkbParamsFiColMethod->add("fieldName", $fieldName);
      $fkbParamsFiColMethod->add("fieldHeader", $fiCol->ofcTxHeader);
      $fkbParamsFiColMethod->add("fiColMethodBody", $sbFiColMethodBody->toString());

      /**
       * @var string $txFiColMethod
       */
      $txFiColMethod = FiTemplate::replaceParams($templateFiColMethod, $fkbParamsFiColMethod);

      $sbFiColMethodsBody->append($txFiColMethod)->append("\n\n");

      //
      if (!FiBool::isTrue($fiCol->ofcBoTransient)) {
        $sbFieldColsAddition->append("\tfiColList.Add(")->append($fieldName)->append("());\n");
        //sbFieldColsAddition.append("\tfiColList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      } else {
        $sbFieldColsAdditionTrans->append("\tfiColList.Add(")->append($fieldName)->append("());\n");
        //sbFieldColsAdditionTrans.append("\tfiColList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      }

      $index++;
    }
//}
    // String
    $tempGenTableCols = "public static function genTableCols() : FiColList {\n\n" .
      "  \$fiColList = new FiColList();\n\n" .
      "{{fiColsAddition}}\n" .
      "  return \$fiColList;\n" .
      "}";

//        String
    $txGenTableColsMethod = FiTemplate:: replaceParams($tempGenTableCols, FiKeybean::bui()->buiPut("fiColsAddition", $sbFieldColsAddition->toString()));

    $sbClassBody->append("\n")->append($txGenTableColsMethod)->append("\n");

    // String
    $tempGenTableColsTrans = "public static function genTableColsTrans() : FiColList  {\n\n" .
      "\t\$fiColList = new FiColList();\n\n" .
      "{{fiColsAddition}}\n" .
      "\treturn \$fiColList;\n" .
      "}";

    //    String
    $txGenTableColsMethodTrans = FiTemplate::replaceParams($tempGenTableColsTrans, FiKeyBean::bui()->buiPut("fiColsAddition", $sbFieldColsAdditionTrans->toString()));
    $sbClassBody->append("\n")->append($txGenTableColsMethodTrans)->append("\n");

    $sbClassBody->append("\n");
    $sbClassBody->append($sbFiColMethodsBody->toString());
    $sbClassBody->append("\n");

    //
    $classPref = "FiCols";
    // URFIX entity name çekilecek
    // String
    $txEntityName = $fiCols->get(0)?->getOfcTxEntityNameNtn();
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.ofcTxEntityName());
    //
    $fkbParamsMain = new FiKeyBean();
    $fkbParamsMain->add("classPref", $classPref);
    $fkbParamsMain->add("entityName", $txEntityName);
    $fkbParamsMain->add("classBody", $sbClassBody->toString());

    // String
    $templateMain = self::getTemplateFiColClassWithInterface();
    $txResult = FiTemplate::replaceParams($templateMain, $fkbParamsMain);

    return $txResult;
  }

  private static function genFiColMethodBodyDetail(FiCol $fiCol): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    //String
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::ofcTxFieldType()));

    if ($fiCol->colType != null)
      $sbFiColMethodBody->append(sprintf(" \$fiCol.fiColType = FiColType.%s;\n", $fiCol->colType));

//        String ofiTxIdType = fiCol.getOfiTxIdType();
//        //FiCodeGen.convertExcelIdentityTypeToFiColAttribute(fiCol.getTosOrEmpty(FiColsMetaTable.ofiTxIdType()));
//
//        if (!FiString.isEmpty(ofiTxIdType)) {
//          sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
//          sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
//        }

    if (FiBool::isTrue($fiCol->ofcBoTransient)) {
      $sbFiColMethodBody->append(" \$fiCol->ofcBoTransient = true;\n");
    }
//
//        if (fiCol.getOfcLnLength() != null) {
//          sbFiColMethodBody.append(String.format("\tfiCol.ofcLnLength = %s;\n", fiCol.getOfcLnLength().toString()));
//        }
//
//        if (FiBool.isTrue(fiCol.getBoNullable())) {
//          sbFiColMethodBody.append("\tfiCol.ofcBoNullable = true;\n");
//        }
//
//        if (fiCol.getOfcLnPrecision() != null) {
//          sbFiColMethodBody.append(String.format("\tfiCol.ofcLnPrecision = %s;\n", fiCol.getOfcLnPrecision().toString()));
//        }
//
//        if (fiCol.getOfcLnScale() != null) {
//          sbFiColMethodBody.append(String.format("\tfiCol.ofcLnScale = %s;\n", fiCol.getOfcLnScale().toString()));
//        }
//
//        if (FiBool.isTrue(fiCol.getOfcBoUnique())) {
//          sbFiColMethodBody.append("\tfiCol.ofcBoUnique = true;\n");
//        }
//
//        if (FiBool.isTrue(fiCol.getOfcBoUniqGro1())) {
//          sbFiColMethodBody.append("\tfiCol.ofcBoUniqGro1 = true;\n");
//        }
//
//        if (FiBool.isTrue(fiCol.getOfcBoUtfSupport())) {
//          sbFiColMethodBody.append("\tfiCol.ofcBoUtfSupport = true;\n");
//        }
//
//        if (!FiString.isEmpty(fiCol.getOfcTxDefValue())) {
//          sbFiColMethodBody.append(String.format("\tfiCol.ofcTxDefValue = \"%s\";\n", fiCol.getOfcTxDefValue()));
//        }
//
//        if (FiBool.isTrue(fiCol.getBoFilterLike())) {
//          sbFiColMethodBody.append("\tfiCol.ofcBoFilterLike = true;\n");
//        }
//
//        // ofcTxCollation	ofcTxTypeName
//
    return $sbFiColMethodBody;
  }

  private static function getTemplateFiColMethod(): string
  {
    return "public static function {{fieldMethodName}} () : FiCol {\n" .
      "  \$fiCol = new FiCol(\"{{fieldName}}\", \"{{fieldHeader}}\");\n" .
      "{{fiColMethodBody}}\n" .
      "  return \$fiCol;\n" .
      "}";
  }


  public static function getTemplateFiColClassWithInterface(): string
  {
    //String
    $templateMain = "\n\n" .
      "class {{classPref}}{{entityName}} : IFiTableMeta {\n" .
      "\n" .
      "public string GetITxTableName() {\n" .
      "  return GetTxTableName();\n" .
      "}\n\n" .
      "public static string GetTxTableName() {\n" .
      "  return \"{{entityName}}\";\n" .
      "}\n\n" .
      "public function GenITableCols() : FiColList {\n" .
      "  return GenTableCols();\n" .
      "}\n\n" .
      "function genITableColsTrans():FiColList {\n" .
      "  return self::genTableColsTrans();\n" .
      "}\n" .
      "\n" .
      "{{classBody}}\n" .
      "}";

    return $templateMain;
  }


}
