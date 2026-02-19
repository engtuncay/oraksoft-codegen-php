<?php

namespace Codegen\Modals;

use Codegen\FiCols\FicFiMeta;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCores\FiBool;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCores\FiTemplate;
use Engtuncay\Phputils8\FiCols\FicValue;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;

/**
 * Code Generator Modal for Java
 */
class CgmJavaOld
{
  private static function getTemplateFiColMethod(): string
  {
    return <<<EOD
public static FiCol {{fieldMethodName}}()
{
  FiCol fiCol = new FiCol("{{fieldName}}");
{{fiColMethodBody}}
  return fiCol;
}
EOD;

  }

  private static function getTemplateFiColMethodExtra(): string
  {
    return <<<EOD
public static FiCol {{fieldMethodName}}Ext()
{
  FiCol fiCol = {{fieldMethodName}}();
{{fiColMethodBody}}
  return fiCol;
}
EOD;

  }

  /**
   * @param mixed $fieldName
   * @return string
   */
  public static function checkMethodNameStdJava(mixed $fieldName): string
  {
    // Başlangıçta eğer fieldName boşsa direkt döndür
    if (empty($fieldName)) return "";

    if (!FiString::hasLowercaseLetter($fieldName)) {
      $fieldName = strtolower($fieldName);
      return $fieldName; // ucfirst($fieldName);
    } else {
      $characters = str_split($fieldName); // Dizeyi karakterlere böl
      $result = ''; // Sonuç dizesi oluştur
      $length = count($characters);

      for ($i = 0; $i < $length; $i++) {
        // İlk harf her zaman küçük kalacak
        if ($i === 0) {
          $result .= strtolower($characters[$i]);
          $characters[$i] = strtolower($characters[$i]);
          continue;
        }

        // Kendinden önceki küçükse, aynen ekle
        if (ctype_lower($characters[$i - 1])) { // && ctype_lower($characters[$i])
          $result .= $characters[$i];
        } // Kendinden önceki büyükse küçült
        else if (ctype_upper($characters[$i - 1])) {
          $result .= strtolower($characters[$i]);
        } else { // Kendinden önceki sayı vs ise büyült
          $result .= strtoupper($characters[$i]);
        }

      }

      return $result;
    }


  }


  public static function getTemplateFicClass(): string
  {
    //String
    $templateMain = <<<EOD
using OrakYazilimLib.DbGeneric;
using OrakYazilimLib.Util.Collection;
using OrakYazilimLib.Util.ColStruct;
      
public class {{classPref}}{{entityName}}:IFiTableMeta
{

  public static string GetTxTableName()
  {
    return "{{tableName}}";
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
  
  public static string GetTxPrefix()
  {
    return "{{tablePrefix}}";
  }

  public string GetITxPrefix()
  {
    return GetTxPrefix();
  }

{{classBody}}

}
EOD;

    return $templateMain;
  }

  public static function actGenFiColClassByFkb(FkbList $fkbListExcel): string
  {

    //if (FiCollection.isEmpty(fiCols)) return;

    $sbClassBody = new FiStrbui(); //new StringBuilder();
    $sbFiColMethodsBody = new FiStrbui(); //new StringBuilder();

    //int
    //$index = 0;

    $sbFclListBody = new FiStrbui();
    $sbFclListBodyExtra = new FiStrbui();
    $sbFclListBodyTrans = new FiStrbui();

    $templateFiColMethod = self::getTemplateFiColMethod();
    $templateFiColMethodExtra = self::getTemplateFiColMethodExtra();

    /**
     * @var FiKeybean $fkbItem
     */
    foreach ($fkbListExcel as $fkbItem) {

      /**
       * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
       */
      $sbFiColMethodBody = self::genFiColMethodBodyDetail($fkbItem); //StringBuilder

      //FiKeybean
      $fkbFiColMethodBody = new FiKeybean();

      //String
      $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
      $fcTxHeader = FiString::orEmpty($fkbItem->getValueByFiCol(FicFiCol::fcTxHeader()));

      //fkbFiColMethodBody.add("fieldMethodName", FiString.capitalizeFirstLetter(fieldName));
      $fkbFiColMethodBody->add("fieldMethodName", self::checkMethodNameStdJava($fieldName));
      $fkbFiColMethodBody->add("fieldName", $fieldName);
      $fkbFiColMethodBody->add("fieldHeader", $fcTxHeader);
      $fkbFiColMethodBody->add("fiColMethodBody", $sbFiColMethodBody->toString());

      /**
       * @var string $txFiColMethod
       */
      $txFiColMethod = FiTemplate::replaceParams($templateFiColMethod, $fkbFiColMethodBody);

      $sbFiColMethodsBody->append($txFiColMethod)->append("\n\n");

      $sbFiColMethodBodyExtra = self::genFiColMethodBodyDetailExtra($fkbItem);
      $fkbFiColMethodBodyExtra = new FiKeybean();
      $fkbFiColMethodBodyExtra->add("fieldMethodName", self::checkMethodNameStdJava($fieldName));
      $fkbFiColMethodBodyExtra->add("fieldName", $fieldName);
      $fkbFiColMethodBodyExtra->add("fieldHeader", $fcTxHeader);
      $fkbFiColMethodBodyExtra->add("fiColMethodBody", $sbFiColMethodBodyExtra->toString());
      $txFiColMethodExtra = FiTemplate::replaceParams($templateFiColMethodExtra, $fkbFiColMethodBodyExtra);

      $sbFiColMethodsBody->append($txFiColMethodExtra)->append("\n\n");

      //
      $fcBoTransient = FicValue::toBool($fkbItem->getValueByFiCol(FicFiCol::fcBoTransient()));
      $methodName = self::checkMethodNameStdJava($fieldName);
      if (!$fcBoTransient === true) {
        $sbFclListBody->append("ficList.Add($methodName());\n");
        $sbFclListBodyExtra->append("ficList.Add($methodName" ."Ext());\n");
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
    $txResGenTableColsMethodTrans = FiTemplate::replaceParams($tempGenFiColsTrans, FiKeybean::bui()->buiPut("ficListBodyTrans", $sbFclListBodyTrans->toString()));
    $sbClassBody->append("\n")->append($txResGenTableColsMethodTrans)->append("\n");

    $tempGenFiColsExt = <<<EOD
public static FiColList GenTableColsExtra() {
  FiColList ficList = new FiColList();

  {{ficListBodyExtra}}

  return ficList;
}
EOD;

    $txResGenTableColsMethodExtra = FiTemplate::replaceParams($tempGenFiColsExt, FiKeybean::bui()->buiPut("ficListBodyExtra", $sbFclListBodyExtra->toString()));
    $sbClassBody->append("\n")->append($txResGenTableColsMethodExtra)->append("\n");

    $sbClassBody->append("\n");
    $sbClassBody->append($sbFiColMethodsBody->toString());

    //
    $classPref = "Fic";
    // URFIX entity name çekilecek
    // String
    $txEntityName = $fkbListExcel->get(0)?->getValueByFiCol(FicFiCol::fcTxEntityName());

    $txTablePrefix = $fkbListExcel->get(0)?->getValueByFiCol(FicFiCol::fcTxPrefix());
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.fcTxEntityName());
    //
    $fkbParamsMain = new FiKeybean();
    $fkbParamsMain->add("classPref", $classPref);
    $fkbParamsMain->add("entityName", self::checkClassNameStd($txEntityName));
    $fkbParamsMain->add("tableName", $txEntityName);
    $fkbParamsMain->add("tablePrefix", $txTablePrefix);
    $fkbParamsMain->add("classBody", $sbClassBody->toString());

    // String
    $templateMain = self::getTemplateFicClass();
    $txResult = FiTemplate::replaceParams($templateMain, $fkbParamsMain);

    return $txResult;
  }

  private static function genFiColMethodBodyDetail(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    //String
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::fcTxFieldType()));

    $fcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::fcTxHeader());
    if ($fcTxHeader != null)
      $sbFiColMethodBody->append(sprintf("  fiCol.fcTxHeader = \"%s\";\n", $fcTxHeader));

    $fcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldType());
    if ($fcTxFieldType != null)
      $sbFiColMethodBody->append(sprintf("  fiCol.fcTxFieldType = \"%s\";\n", $fcTxFieldType));

    $fcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::fcTxDbField());
    if ($fcTxDbField != null)
      $sbFiColMethodBody->append(sprintf("  fiCol.fcTxDbField = \"%s\";\n", $fcTxDbField));

    //$fcTxIdType = $fiCol->fcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->fcTxIdType);

// if (!FiString.isEmpty(ofiTxIdType)) {
// sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
// sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
// }

    $fcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoTransient());
    if ($fcBoTransient) {
      $sbFiColMethodBody->append("  fiCol.fcBoTransient = true;\n");
    }

    $fcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnLength()));
    if ($fcLnLength != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.fcLnLength = %s;\n", $fcLnLength));
    }

    $fcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnPrecision()));
    if ($fcLnPrecision != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.fcLnPrecision = %s;\n", $fcLnPrecision));
    }

    $fcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnScale()));
    if ($fcLnScale != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.fcLnScale = %s;\n", $fcLnScale));
    }

    if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoNullable()))) {
      $sbFiColMethodBody->append("  fiCol.fcBoNullable = false;\n");
    }

    //
//    if (FiBool::isTrue($fiCol->fcBoNullable)) {
//      $sbFiColMethodBody->append("fiCol.fcBoNullable = true;\n");
//    }

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
//        if (!FiString.isEmpty(fiCol.getfcTxDefValue())) {
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

  private static function genFiColMethodBodyDetailExtra(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    $fcTxFielDesc = $fkbItem->getValueByFiCol(FicFiCol::fcTxDesc());
    //if ($fcTxFielDesc != null)
      $sbFiColMethodBody->append(sprintf("  fiCol.fcTxFieldDesc = \"%s\";\n", $fcTxFielDesc));

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
    $classPref = "Fic";
    // URFIX entity name çekilecek
    // String
    $txEntityName = $fkbListExcel->get(0)?->getValueByFiCol(FicFiCol::fcTxEntityName());
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.fcTxEntityName());
    //
    $fkbParamsClass = new FiKeybean();
    $fkbParamsClass->add("classPref", $classPref);
    $fkbParamsClass->add("entityName", self::checkClassNameStd($txEntityName));
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

  /**
   * @param mixed $txEntityName
   * @return mixed
   */
  public static function checkClassNameStd(mixed $txEntityName): mixed
  {
    if (!FiString::hasLowercaseLetter($txEntityName)) {
      $txEntityName = strtolower($txEntityName);
    }

    return ucfirst($txEntityName);
  }

}
