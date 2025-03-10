<?php

namespace codegen\modals;

use codegen\ficols\FicFiCol;
use codegen\ficols\FicFiMeta;
use Engtuncay\Phputils8\Core\FiBool;
use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\Core\FiString;
use Engtuncay\Phputils8\Core\FiTemplate;
use Engtuncay\Phputils8\FiCol\FicValue;
use Engtuncay\Phputils8\Meta\FiCol;
use Engtuncay\Phputils8\Meta\FiKeybean;
use Engtuncay\Phputils8\Meta\FkbList;

/**
 * Code Generator Modal for Php
 */
class CgmCsharp
{
  public static function actGenFiColClassByFkbList(FkbList $fkbListExcel): string
  {

    $fiCols = CgmFiCol::getFiColListFromFkbList($fkbListExcel);

    //if (FiCollection.isEmpty(fiCols)) return;

    //fiCol.buiColType(OzColType.{{fieldType}});
    $sbClassBody = new FiStrbui(); //new StringBuilder();
    $sbFiColMethodsBody = new FiStrbui(); //new StringBuilder();

    //int
    $index = 0;

    $sbFclListBody = new FiStrbui();
    $sbFclListBodyTrans = new FiStrbui();

    $templateFiColMethod = self::getTemplateFiColMethod();

    /**
     * @var FiCol $fiCol
     */
    foreach ($fiCols as $fiCol) {

      /**
       * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
       */
      $sbFiColMethodBody = self::genFiColMethodBodyDetail($fiCol); //StringBuilder

      //FiKeyBean
      $fkbFiColMethodBody = new FiKeybean();

      //String
      $fieldName = $fiCol->ofcTxFieldName;
      //fkbFiColMethodBody.add("fieldMethodName", FiString.capitalizeFirstLetter(fieldName));
      $fkbFiColMethodBody->add("fieldMethodName", $fieldName);
      $fkbFiColMethodBody->add("fieldName", $fieldName);
      $fkbFiColMethodBody->add("fieldHeader", $fiCol->ofcTxHeader);
      $fkbFiColMethodBody->add("fiColMethodBody", $sbFiColMethodBody->toString());

      /**
       * @var string $txFiColMethod
       */
      $txFiColMethod = FiTemplate::replaceParams($templateFiColMethod, $fkbFiColMethodBody);

      $sbFiColMethodsBody->append($txFiColMethod)->append("\n\n");

      //
      if (!FiBool::isTrue($fiCol->ofcBoTransient)) {
        $sbFclListBody->append("ficList.Add($fieldName());\n");
        //sbFclListBody.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      } else {
        $sbFclListBodyTrans->append("ficList.Add($fieldName());\n");
        //sbFclListBodyTrans.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      }

      $index++;
    }

    // String
    $tempGenFiCols = <<<EOD
public static FiColList GenTableCols() {
  FiColList ficList = new FiColList();

  {{ficListBody}}

  return ficList;
}
EOD;

    // String
    $txResGenTableColsMethod = FiTemplate::replaceParams($tempGenFiCols, FiKeybean::bui()->buiPut("ficListBody", $sbFclListBody->toString()));

    $sbClassBody->append("\n")->append($txResGenTableColsMethod)->append("\n");

    // String
    $tempGenFiColsTrans = <<<EOD
public static FiColList GenTableColsTrans() {
  FiColList ficList = new FiColList();
  
  {{ficListBodyTrans}}
  
  return ficList;
}
EOD;

    //    String
    $txResGenTableColsMethodTrans = FiTemplate::replaceParams($tempGenFiColsTrans, FiKeyBean::bui()->buiPut("ficListBodyTrans", $sbFclListBodyTrans->toString()));
    $sbClassBody->append("\n")->append($txResGenTableColsMethodTrans)->append("\n");

    $sbClassBody->append("\n");
    $sbClassBody->append($sbFiColMethodsBody->toString());

    //
    $classPref = "Fic";
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
    $templateMain = self::getTemplateFicClass();
    $txResult = FiTemplate::replaceParams($templateMain, $fkbParamsMain);

    return $txResult;
  }

  private static function getTemplateFiColMethod(): string
  {
    return <<<EOD
public static FiCol {{fieldMethodName}}()
{
  FiCol fiCol = new FiCol("{{fieldName}}", "{{fieldHeader}}");
{{fiColMethodBody}}
  return fiCol;
}
EOD;

  }

  private static function genFiColMethodBodyDetail(FiCol $fiCol): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    //String
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::ofcTxFieldType()));

    if ($fiCol->ofcTxFieldType != null)
      $sbFiColMethodBody->append(sprintf("fiCol.ofcTxFieldType = '%s';\n", $fiCol->ofcTxFieldType));


    //$ofcTxIdType = $fiCol->ofcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->ofcTxIdType);

//        if (!FiString.isEmpty(ofiTxIdType)) {
//          sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
//          sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
//        }

    if (FiBool::isTrue($fiCol->ofcBoTransient)) {
      $sbFiColMethodBody->append("fiCol.ofcBoTransient = true;\n");
    }

    if ($fiCol->ofcLnLength != null) {
      $sbFiColMethodBody->append(sprintf("fiCol.ofcLnLength = %s;\n", $fiCol->ofcLnLength));
    }

    if (FiBool::isTrue($fiCol->ofcBoNullable)) {
      $sbFiColMethodBody->append("fiCol.ofcBoNullable = true;\n");
    }

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

    return $sbFiColMethodBody;
  }

  public static function getTemplateFicClass(): string
  {
    //String
    $templateMain = <<<EOD
      
public class {{classPref}}{{entityName}} : IFiTableMeta
{

  public static string GetTxTableName()
  {
    return "{{entityName}}";
  }
  
  public string GetITxTableName()
  {
    return GetTxTableName();
  }

  public FiColList GenITableCols()
  {
    return GenTableCols();
  }
  
  public FiColList GenITableColsTrans()
  {
    return GenTableColsTrans();
  }

{{classBody}}

}
EOD;

    return $templateMain;
  }

  public static function actGenFiColClassByFkbList2(FkbList $fkbListExcel): string
  {

    //$fiCols = CgmFiCol::getFiColListFromFkbList($fkbListExcel);

    //if (FiCollection.isEmpty(fiCols)) return;

    //fkbItem.buiColType(OzColType.{{fieldType}});
    $sbClassBody = new FiStrbui(); //new StringBuilder();
    $sbFiColMethodsBody = new FiStrbui(); //new StringBuilder();

    //int
//    $index = 0;

    $sbFclListBody = new FiStrbui();
    $sbFclListBodyTrans = new FiStrbui();

    $templateFiColMethod = self::getTemplateFiColMethod();

    /**
     * @var FiKeybean $fkbItem
     */
    foreach ($fkbListExcel as $fkbItem) {

      /**
       * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
       */
      $sbFiColMethodBody = self::genFiColMethodBodyDetail2($fkbItem); //StringBuilder

      //FiKeyBean
      $fkbFiColMethodBody = new FiKeybean();

      //String
      $fieldName = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldName());
      //fkbFiColMethodBody.add("fieldMethodName", FiString.capitalizeFirstLetter(fieldName));
      $fkbFiColMethodBody->add("fieldMethodName", ucfirst($fieldName));
      $fkbFiColMethodBody->add("fieldName", $fieldName);
      $fkbFiColMethodBody->add("fieldHeader", $fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader()));
      $fkbFiColMethodBody->add("fiColMethodBody", $sbFiColMethodBody->toString());

      /**
       * @var string $txFiColMethod
       */
      $txFiColMethod = FiTemplate::replaceParams($templateFiColMethod, $fkbFiColMethodBody);

      $sbFiColMethodsBody->append($txFiColMethod)->append("\n\n");

      //
      $ofcBoTransient = FicValue::toBool($fkbItem->getValueByFiCol(FicFiCol::ofcBoTransient()));
      $methodName = ucfirst($fieldName);
      if (!$ofcBoTransient === true) {
        $sbFclListBody->append("ficList.Add($methodName());\n");
        //sbFclListBody.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      } else {
        $sbFclListBodyTrans->append("ficList.Add($methodName());\n");
        //sbFclListBodyTrans.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      }

      //$index++;
    }

    // String
    $tempGenFiCols = <<<EOD
public static FiColList GenTableCols() {
  FiColList ficList = new FiColList();

  {{ficListBody}}

  return ficList;
}
EOD;

    // String
    $txResGenTableColsMethod = FiTemplate::replaceParams($tempGenFiCols, FiKeybean::bui()->buiPut("ficListBody", $sbFclListBody->toString()));

    $sbClassBody->append("\n")->append($txResGenTableColsMethod)->append("\n");

    // String
    $tempGenFiColsTrans = <<<EOD
public static FiColList GenTableColsTrans() {
  FiColList ficList = new FiColList();
  
  {{ficListBodyTrans}}
  
  return ficList;
}
EOD;

    //    String
    $txResGenTableColsMethodTrans = FiTemplate::replaceParams($tempGenFiColsTrans, FiKeyBean::bui()->buiPut("ficListBodyTrans", $sbFclListBodyTrans->toString()));
    $sbClassBody->append("\n")->append($txResGenTableColsMethodTrans)->append("\n");

    $sbClassBody->append("\n");
    $sbClassBody->append($sbFiColMethodsBody->toString());

    //
    $classPref = "Fic";
    // URFIX entity name çekilecek
    // String
    $txEntityName = $fkbListExcel->get(0)?->getValueByFiCol(FicFiCol::ofcTxEntityName());
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.ofcTxEntityName());
    //
    $fkbParamsMain = new FiKeyBean();
    $fkbParamsMain->add("classPref", $classPref);
    $fkbParamsMain->add("entityName", $txEntityName);
    $fkbParamsMain->add("classBody", $sbClassBody->toString());

    // String
    $templateMain = self::getTemplateFicClass();
    $txResult = FiTemplate::replaceParams($templateMain, $fkbParamsMain);

    return $txResult;
  }

  private static function genFiColMethodBodyDetail2(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    //String
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::ofcTxFieldType()));

    $ofcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldType());
    if ($ofcTxFieldType != null)
      $sbFiColMethodBody->append(sprintf("fiCol.ofcTxFieldType = '%s';\n", $ofcTxFieldType));

    //$ofcTxIdType = $fiCol->ofcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->ofcTxIdType);

//        if (!FiString.isEmpty(ofiTxIdType)) {
//          sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
//          sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
//        }


    $ofcBoTransient = FicValue::toBool($fkbItem->getValueByFiCol(FicFiCol::ofcBoTransient()));
    if ($ofcBoTransient === true) {
      $sbFiColMethodBody->append("fiCol.ofcBoTransient = true;\n");
    }

//    if ($fiCol->ofcLnLength != null) {
//      $sbFiColMethodBody->append(sprintf("fiCol.ofcLnLength = %s;\n", $fiCol->ofcLnLength));
//    }
//
//    if (FiBool::isTrue($fiCol->ofcBoNullable)) {
//      $sbFiColMethodBody->append("fiCol.ofcBoNullable = true;\n");
//    }

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

    return $sbFiColMethodBody;
  }

  public static function actGenFiMetaClass(FkbList $fkbListExcel): string
  {
    //if (FiCollection.isEmpty(fiCols)) return;

    $sbClassBody = new FiStrbui(); //new StringBuilder();
    $sbFiMetaAllMethods = new FiStrbui(); //new StringBuilder();

    $sbFmtListBody = new FiStrbui();

    $templateFiMetaMethod = <<<EOD
public static function {{fieldMethodName}}() : FiMeta {
  \$fiMeta = new FiMeta();
{{fiColMethodBody}}
  return \$fiMeta;
}

EOD;

    /**
     * @var FiKeybean $fkb
     */
    foreach ($fkbListExcel as $fkb) {

      /**
       * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
       */
      $sbFmtMethodBodyFieldDefs = self::genFiMetaMethodBodyFieldDefs($fkb); //StringBuilder

      //FiKeyBean
      $fkbFiMetaMethodBody = new FiKeybean();

      //String
      $fieldName = $fkb->getValueByFiCol(FicFiMeta::txKey());

      $fkbFiMetaMethodBody->add("fieldMethodName", $fieldName);
      //$fkbFiMetaMethodBody->add("fieldName", $fieldName);
      //$fkbFiMetaMethodBody->add("fieldHeader", $fkb->getValueByFiCol(FicFiMeta::txValue()));
      $fkbFiMetaMethodBody->add("fiColMethodBody", $sbFmtMethodBodyFieldDefs->toString());

      $txResFiMetaMethod = FiTemplate::replaceParams($templateFiMetaMethod, $fkbFiMetaMethodBody);

      $sbFiMetaAllMethods->append($txResFiMetaMethod)->append("\n\n");

      //
      $sbFmtListBody->append("  \$fmtList->add(self::$fieldName());\n");

    }

    // String
    $tempGenFiCols = <<<EOD
public static function GenFmtList() : FmtList {
  \$fmtList = new FmtList();
{{fmtListBody}}
  return \$fmtList;
}
EOD;

    // String
    $txResGenFmtListMethod = FiTemplate::replaceParams($tempGenFiCols, FiKeybean::bui()->buiPut("fmtListBody", $sbFmtListBody->toString()));

    $sbClassBody->append("\n")->append($txResGenFmtListMethod)->append("\n");

    $sbClassBody->append("\n");
    $sbClassBody->append($sbFiMetaAllMethods->toString());

    //
    $classPref = "Fic";
    // URFIX entity name çekilecek
    // String
    $txEntityName = $fkbListExcel->get(0)?->getValueByFiCol(FicFiCol::ofcTxEntityName());
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.ofcTxEntityName());
    //
    $fkbParamsClass = new FiKeyBean();
    $fkbParamsClass->add("classPref", $classPref);
    $fkbParamsClass->add("entityName", $txEntityName);
    $fkbParamsClass->add("classBody", $sbClassBody->toString());

    // String
    $tplFiMetaClass = self::getTemplateFiMetaClass();
    $txResult = FiTemplate::replaceParams($tplFiMetaClass, $fkbParamsClass);

    return $txResult;
  }

  private static function genFiMetaMethodBodyFieldDefs(FiKeybean $fkb): FiStrbui
  {
    //StringBuilder
    $sbFmtMethodBodyFieldDefs = new FiStrbui();

    $txKey = $fkb->getValueByFiCol(FicFiMeta::txKey());
    if ($txKey != null) {
      $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
    }

    $txValue = $fkb->getValueByFiCol(FicFiMeta::txValue());
    if ($txValue != null) {
      $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txValue = '%s';\n", $txValue));
    }

    return $sbFmtMethodBodyFieldDefs;
  }

  public static function getTemplateFiMetaClass(): string
  {
    //String
    $templateMain = <<<EOD
      
use Engtuncay\Phputils8\Meta\FiMeta;
use Engtuncay\Phputils8\Meta\FmtList;

class {{entityName}} {

{{classBody}}

}
EOD;

    return $templateMain;
  }

}
