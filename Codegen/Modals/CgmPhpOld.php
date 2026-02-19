<?php

namespace Codegen\Modals;

use Codegen\FiCols\FicFiMeta;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCores\FiBool;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiTemplate;
use Engtuncay\Phputils8\FiDtos\FiCol;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;

/**
 * Code Generator Modal for Php
 */
class CgmPhpOld
{
  public static function actGenFiColClassByFkbList(FkbList $fkbListExcel): string
  {

    $fiCols = CgmUtils::getFiColListFromFkbList($fkbListExcel);

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

      //FiKeybean
      $fkbFiColMethodBody = new FiKeybean();

      //String
      $fieldName = $fiCol->fcTxFieldName;
      //fkbFiColMethodBody.add("fieldMethodName", FiString.capitalizeFirstLetter(fieldName));
      $fkbFiColMethodBody->add("fieldMethodName", $fieldName);
      $fkbFiColMethodBody->add("fieldName", $fieldName);
      $fkbFiColMethodBody->add("fieldHeader", $fiCol->fcTxHeader);
      $fkbFiColMethodBody->add("fiColMethodBody", $sbFiColMethodBody->toString());

      /**
       * @var string $txFiColMethod
       */
      $txFiColMethod = FiTemplate::replaceParams($templateFiColMethod, $fkbFiColMethodBody);

      $sbFiColMethodsBody->append($txFiColMethod)->append("\n\n");

      //
      if (!FiBool::isTrue($fiCol->fcBoTransient)) {
        $sbFclListBody->append("  \$fclList->add(self::$fieldName());\n");
        //sbFclListBody.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      } else {
        $sbFclListBodyTrans->append("  \$fclList->add(self::$fieldName());\n");
        //sbFclListBodyTrans.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      }

      $index++;
    }

    // String
    $tempGenFiCols = <<<EOD
public static function GenTableCols() : FclList {
  \$fclList = new FclList();
{{fclListBody}}
  return \$fclList;
}
EOD;

    // String
    $txResGenTableColsMethod = FiTemplate::replaceParams($tempGenFiCols, FiKeybean::bui()->buiPut("fclListBody", $sbFclListBody->toString()));

    $sbClassBody->append("\n")->append($txResGenTableColsMethod)->append("\n");

    // String
    $tempGenFiColsTrans = <<<EOD
public static function GenTableColsTrans() : FclList  {
  \$fclList = new FclList();
{{fclListBodyTrans}}
  return \$fclList;
}
EOD;

    //    String
    $txResGenTableColsMethodTrans = FiTemplate::replaceParams($tempGenFiColsTrans, FiKeybean::bui()->buiPut("fclListBodyTrans", $sbFclListBodyTrans->toString()));
    $sbClassBody->append("\n")->append($txResGenTableColsMethodTrans)->append("\n");

    $sbClassBody->append("\n");
    $sbClassBody->append($sbFiColMethodsBody->toString());

    //
    $classPref = "Fic";
    // URFIX entity name çekilecek
    // String
    $txEntityName = $fiCols->get(0)?->getFcTxEntityNameNtn();
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.fcTxEntityName());
    //
    $fkbParamsMain = new FiKeybean();
    $fkbParamsMain->add("classPref", $classPref);
    $fkbParamsMain->add("entityName", $txEntityName);
    $fkbParamsMain->add("classBody", $sbClassBody->toString());

    // String
    $templateMain = self::getTemplateFiColClassWithInterface();
    $txResult = FiTemplate::replaceParams($templateMain, $fkbParamsMain);

    return $txResult;
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

  private static function genFiColMethodBodyDetail(FiCol $fiCol): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    //String
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::fcTxFieldType()));

    if ($fiCol->fcTxFieldType != null)
      $sbFiColMethodBody->append(sprintf(" \$fiCol->fcTxFieldType = '%s';\n", $fiCol->fcTxFieldType));


    //$fcTxIdType = $fiCol->fcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->fcTxIdType);

//        if (!FiString.isEmpty(ofiTxIdType)) {
//          sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
//          sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
//        }

    if (FiBool::isTrue($fiCol->fcBoTransient)) {
      $sbFiColMethodBody->append(" \$fiCol->fcBoTransient = true;\n");
    }

    if ($fiCol->fcLnLength != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.fcLnLength = %s;\n", $fiCol->fcLnLength));
    }

    if (FiBool::isTrue($fiCol->fcBoNullable)) {
      $sbFiColMethodBody->append("  fiCol->fcBoNullable = true;\n");
    }

//        if (fiCol.getFcLnPrecision() != null) {
//          sbFiColMethodBody.append(String.format("\tfiCol.fcLnPrecision = %s;\n", fiCol.getFcLnPrecision().toString()));
//        }
//
//        if (fiCol.getFcLnScale() != null) {
//          sbFiColMethodBody.append(String.format("\tfiCol.fcLnScale = %s;\n", fiCol.getFcLnScale().toString()));
//        }
//
//        if (FiBool.isTrue(fiCol.getFcBoUnique())) {
//          sbFiColMethodBody.append("\tfiCol.fcBoUnique = true;\n");
//        }
//
//        if (FiBool.isTrue(fiCol.getFcBoUniqGro1())) {
//          sbFiColMethodBody.append("\tfiCol.fcBoUniqGro1 = true;\n");
//        }
//
//        if (FiBool.isTrue(fiCol.getFcBoUtfSupport())) {
//          sbFiColMethodBody.append("\tfiCol.fcBoUtfSupport = true;\n");
//        }
//
//        if (!FiString.isEmpty(fiCol.getFcTxDefValue())) {
//          sbFiColMethodBody.append(String.format("\tfiCol.fcTxDefValue = \"%s\";\n", fiCol.getFcTxDefValue()));
//        }
//
//        if (FiBool.isTrue(fiCol.getBoFilterLike())) {
//          sbFiColMethodBody.append("\tfiCol.fcBoFilterLike = true;\n");
//        }
//
//        // fcTxCollation	fcTxTypeName

    return $sbFiColMethodBody;
  }

  public static function getTemplateFiColClassWithInterface(): string
  {
    //String
    $templateMain = <<<EOD
      
use Engtuncay\Phputils8\FiCols\IFiTableMeta;
use Engtuncay\Phputils8\FiDtos\FiCol;
use Engtuncay\Phputils8\FiDtos\FclList;

class {{entityName}} implements IFiTableMeta {

public function getITxTableName() : string {
  return self::GetTxTableName();
}

public static function  GetTxTableName() : string{
  return "{{entityName}}";
}

{{classBody}}

public function genITableCols() : FclList {
  return self::GenTableCols();
}

public function genITableColsTrans():FclList {
  return self::GenTableColsTrans();
}

}
EOD;

    return $templateMain;
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

      //FiKeybean
      $fkbFiMetaMethodBody = new FiKeybean();

      //String
      $fieldName = $fkb->getValueByFiCol(FicFiMeta::ftTxKey());

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
    $classPref = "Fim";
    // URFIX entity name çekilecek
    // String
    $txEntityName = $fkbListExcel->get(0)?->getValueByFiCol(FicFiCol::fcTxEntityName());
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.fcTxEntityName());
    //
    $fkbParamsClass = new FiKeybean();
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

    $txKey = $fkb->getValueByFiCol(FicFiMeta::ftTxKey());
    if ($txKey != null) {
      $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
    }

    $txValue = $fkb->getValueByFiCol(FicFiMeta::ftTxValue());
    if ($txValue != null) {
      $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txValue = '%s';\n", $txValue));
    }

    return $sbFmtMethodBodyFieldDefs;
  }

  public static function getTemplateFiMetaClass(): string
  {
    //String
    $templateMain = <<<EOD
      
use Engtuncay\Phputils8\FiDtos\FiMeta;
use Engtuncay\Phputils8\FiDtos\FmtList;

class {{entityName}} {

{{classBody}}

}
EOD;

    return $templateMain;
  }

}
