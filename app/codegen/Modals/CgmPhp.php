<?php

namespace codegen\modals;

use Engtuncay\Phputils8\Core\FiBool;
use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\Core\FiTemplate;
use Engtuncay\Phputils8\Meta\FiCol;
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

    //fiCol.buiColType(OzColType.{{fieldType}});
    $sbClassBody = new FiStrbui(); //new StringBuilder();
    $sbFiColMethodsBody = new FiStrbui(); //new StringBuilder();

    //int
    $index = 0;

    $sbFiColListBody = new FiStrbui();
    $sbFiColListBodyTrans = new FiStrbui();

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
        $sbFiColListBody->append("  \$fiColList->add(self::$fieldName());\n");
        //sbFiColListBody.append("\tfiColList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      } else {
        $sbFiColListBodyTrans->append("  \$fiColList->add(self::$fieldName());\n");
        //sbFiColListBodyTrans.append("\tfiColList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      }

      $index++;
    }

    // String
    $tempGenFiCols = <<<EOD
public static function GenTableCols() : FiColList {
  \$fiColList = new FiColList();
{{fiColListBody}}
  return \$fiColList;
}
EOD;

    // String
    $txResGenTableColsMethod = FiTemplate::replaceParams($tempGenFiCols, FiKeybean::bui()->buiPut("fiColListBody", $sbFiColListBody->toString()));

    $sbClassBody->append("\n")->append($txResGenTableColsMethod)->append("\n");

    // String
    $tempGenFiColsTrans = <<<EOD
public static function GenTableColsTrans() : FiColList  {
  \$fiColList = new FiColList();
{{fiColListBodyTrans}}
  return \$fiColList;
}
EOD;

    //    String
    $txResGenTableColsMethodTrans = FiTemplate::replaceParams($tempGenFiColsTrans, FiKeyBean::bui()->buiPut("fiColListBodyTrans", $sbFiColListBodyTrans->toString()));
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
      $sbFiColMethodBody->append(sprintf(" \$fiCol->fiColType = FiColType.%s;\n", $fiCol->colType));

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

    if ($fiCol->ofcLnLength != null) {
      $sbFiColMethodBody->append(sprintf("\tfiCol.ofcLnLength = %s;\n", $fiCol->ofcLnLength));
    }

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
    return <<<EOD
public static function {{fieldMethodName}} () : FiCol {
  \$fiCol = new FiCol("{{fieldName}}", "{{fieldHeader}}");
{{fiColMethodBody}}
  return \$fiCol;
}
EOD;
  }


  public static function getTemplateFiColClassWithInterface(): string
  {
    //String
    $templateMain = <<<EOD
      
use Engtuncay\Phputils8\FiCol\IFiTableMeta;
use Engtuncay\Phputils8\Meta\FiCol;
use Engtuncay\Phputils8\Meta\FiColList;

class {{entityName}} implements IFiTableMeta {

public function getITxTableName() : string {
  return self::GetTxTableName();
}

public static function  GetTxTableName() : string{
  return "{{entityName}}";
}

{{classBody}}

public function genITableCols() : FiColList {
  return self::GenTableCols();
}

public function genITableColsTrans():FiColList {
  return self::GenTableColsTrans();
}

}
EOD;

    return $templateMain;
  }


}
