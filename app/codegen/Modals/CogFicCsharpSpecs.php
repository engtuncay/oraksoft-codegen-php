<?php

namespace codegen\modals;

use codegen\ficols\FicFiCol;
use codegen\ficols\FicFiMeta;
use Engtuncay\Phputils8\Core\FiBool;
use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\Core\FiString;
use Engtuncay\Phputils8\Core\FiTemplate;
use Engtuncay\Phputils8\FiCol\FicValue;
use Engtuncay\Phputils8\Meta\FiKeybean;
use Engtuncay\Phputils8\Meta\FkbList;

/**
 * Java Templates For Code Generator
 */
class CogFicCsharpSpecs implements ICogFicSpecs
{

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

  public function getTemplateFiColMethod(): string
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

  public function getTemplateFiColMethodExtra(): string
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

  public function getTemplateFicClass(): string
  {
    //String
    $templateMain = <<<EOD
using OrakYazilimLib.DbGeneric;
using OrakYazilimLib.Util;
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
  
  public static void AddFieldDesc(FiColList ficolList) {

    foreach (FiCol fiCol in ficolList)
    {
        {{addFieldDescDetail}}
    }
    
  }

{{classBody}}

}
EOD;

    return $templateMain;
  }

  public function genFiColMethodBodyDetail(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    //String
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::ofcTxFieldType()));

    $ofcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader());
    if ($ofcTxHeader != null)
      $sbFiColMethodBody->append(sprintf("  fiCol.ofcTxHeader = \"%s\";\n", $ofcTxHeader));

    $ofcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldType());
    if ($ofcTxFieldType != null)
      $sbFiColMethodBody->append(sprintf("  fiCol.ofcTxFieldType = \"%s\";\n", $ofcTxFieldType));

    $ofcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDbField());
    if ($ofcTxDbField != null)
      $sbFiColMethodBody->append(sprintf("  fiCol.ofcTxDbField = \"%s\";\n", $ofcTxDbField));

    {
      $ofcTxRefField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxRefField());
      if ($ofcTxRefField != null)
        $sbFiColMethodBody->append(sprintf("  fiCol.ofcTxRefField = \"%s\";\n", $ofcTxRefField));
    }


    //$ofcTxIdType = $fiCol->ofcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->ofcTxIdType);

// if (!FiString.isEmpty(ofiTxIdType)) {
// sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
// sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
// }

    $ofcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoTransient());
    if ($ofcBoTransient) {
      $sbFiColMethodBody->append("  fiCol.ofcBoTransient = true;\n");
    }

    $ofcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnLength()));
    if ($ofcLnLength != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnLength = %s;\n", $ofcLnLength));
    }

    $ofcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnPrecision()));
    if ($ofcLnPrecision != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnPrecision = %s;\n", $ofcLnPrecision));
    }

    $ofcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnScale()));
    if ($ofcLnScale != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnScale = %s;\n", $ofcLnScale));
    }

    if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoNullable()))) {
      $sbFiColMethodBody->append("  fiCol.ofcBoNullable = false;\n");
    }

    //
//    if (FiBool::isTrue($fiCol->ofcBoNullable)) {
//      $sbFiColMethodBody->append("fiCol.ofcBoNullable = true;\n");
//    }

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

  public function genFiColMethodBodyDetailExtra(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    $ofcTxFielDesc = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldDesc());
    //if ($ofcTxFielDesc != null)
    $sbFiColMethodBody->append(sprintf("  fiCol.ofcTxFieldDesc = \"%s\";\n", $ofcTxFielDesc));

    return $sbFiColMethodBody;
  }

  public function genFiMetaMethodBodyFieldDefs(FiKeybean $fkb): FiStrbui
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

  /**
   * @return string
   */
  public function getTempGenFiColsExtraList(): string
  {
    return <<<EOD
public static FiColList GenTableColsExtra() {
  FiColList ficList = new FiColList();

  {{ficListBodyExtra}}

  return ficList;
}
EOD;
  }

  /**
   * @return string
   */
  public function getTempGenGiColsTransList(): string
  {
    return <<<EOD
public static FiColList GenTableColsTrans() {
  FiColList ficList = new FiColList();
  
  {{ficListBodyTrans}}
  
  return ficList;
}
EOD;
  }

  /**
   * @return string
   */
  public function getTempGenFiColsList(): string
  {
    return <<<EOD
public static FiColList GenTableCols() {
  FiColList ficList = new FiColList();

  {{ficListBody}}

  return ficList;
}
EOD;
  }

  /**
   * @param FiStrbui $sbFclListBody
   * @param string $methodName
   * @param FiStrbui $sbFclListBodyExtra
   * @return void
   */
  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, string $methodName): void
  { //, FiStrbui $sbFclListBodyExtra
    $sbFclListBody->append("ficList.Add($methodName());\n");
    // $sbFclListBodyExtra->append("ficList.Add($methodName" . "Ext());\n");
  }

  /**
   * @param FiStrbui $sbFclListBodyTrans
   * @param string $methodName
   * @return void
   */
  public function doTransientFieldOps(FiStrbui $sbFclListBodyTrans, string $methodName): void
  {
    $sbFclListBodyTrans->append("ficList.Add($methodName());\n");
  }

  public function genFiColAddDescDetail(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbText = new FiStrbui(); // new StringBuilder();

    $ofcTxFielDesc = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldDesc());

    if (!FiString::isEmpty($ofcTxFielDesc)) {
      $methodNameStd = $this->checkMethodNameStd($fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldName()));

      $sbText->append(
        <<<EOD

    if(FiString.Equals(fiCol.ofcTxFieldName,$methodNameStd().ofcTxFieldName)){
      fiCol.ofcTxFieldDesc = "$ofcTxFielDesc";
    }
      
EOD
      );
    }

    return $sbText;
  }

  /**
   * @param mixed $fieldName
   * @return string
   */
  public function checkMethodNameStd(mixed $fieldName): string
  {
    // Başlangıçta eğer fieldName boşsa direkt döndür
    if (FiString::isEmpty($fieldName)) return "";

    if (!FiString::hasLowercaseLetter($fieldName)) {
      $fieldName = strtolower($fieldName);
      return ucfirst($fieldName);
    } else {

      $characters = str_split($fieldName); // Dizeyi karakterlere böl
      $result = ''; // Sonuç dizesi oluştur
      $length = count($characters);

      for ($i = 0; $i < $length; $i++) {
        // İlk harf her zaman büyük kalacak
        if ($i === 0) {
          $result .= strtoupper($characters[$i]);
          $characters[$i] = strtoupper($characters[$i]);
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


}
