<?php

namespace Codegen\Modals;

use Codegen\ficols\FicFiCol;
use Codegen\ficols\FicFiMeta;
use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\Core\FiString;
use Engtuncay\Phputils8\FiDto\FiKeybean;

/**
 * Csharp Templates For Code Generator
 */
class CogPhpSpecs implements ICogFicSpecs
{

  public static function getTemplateFiMetaClass(): string
  {
    //String
    $templateMain = <<<EOD
      
use Engtuncay\Phputils8\FiDto\FiMeta;
use Engtuncay\Phputils8\FiDto\FmtList;

class {{entityName}} {

{{classBody}}

}
EOD;

    return $templateMain;
  }

  /**
   * 
   * Hepsi büyük harf ise, hepsini küçültür
   * 
   * İlk harf büyük olarak döner.
   * 
   * @param mixed $txEntityName
   * @return mixed
   */
  public function checkClassNameStd(mixed $txEntityName): string
  {
    // hiç küçük harf yoksa (hepsi büyükse), tüm harfleri küçük yap
    if (!FiString::hasLowercaseLetter($txEntityName)) {
      $txEntityName = strtolower($txEntityName);
    }

    // Her zaman ilk harfi büyük yap
    return ucfirst($txEntityName);
  }

  public function getTemplateFiColMethod(): string
  {
    return <<<EOD
public static function {{fieldMethodName}}() : FiCol
{ 
  \$fiCol = new FiCol("{{fieldName}}");
{{fiColMethodBody}}
  return \$fiCol;
}
EOD;

  }

  public function getTemplateFkbColMethod(): string
  {
    return <<<EOD
public static function {{fieldMethodName}}() : FiKeybean
{ 
  \$fkbCol = new FiKeybean();
{{fiColMethodBody}}
  return \$fkbCol;
}
EOD;

  }

  public function getTemplateFiMetaMethod(): string
  {
    return <<<EOD
public static function {{fieldMethodName}}() : FiMeta
{ 
  \$fiMeta = new FiMeta("{{fieldName}}");
{{fiMethodBody}}
  return \$fiMeta;
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
use Engtuncay\Phputils8\FiCol\IFiTableMeta;
use Engtuncay\Phputils8\FiDto\FiCol;
use Engtuncay\Phputils8\FiDto\FicList;

class {{classPref}}{{entityName}} implements IFiTableMeta {

public function getITxTableName() : string {
  return self::GetTxTableName();
}

public static function  getTxTableName() : string{
  return "{{entityName}}";
}

{{classBody}}

public function genITableCols() : FicList {
  return self::genTableCols();
}

public function genITableColsTrans():FicList {
  return self::genTableColsTrans();
}

}
EOD;

    return $templateMain;
  }

public function getTemplateFkbColClass(): string
  {
    //String
    $templateMain = <<<EOD

use Engtuncay\Phputils8\FiCol\IFkbTableMeta;
use Engtuncay\Phputils8\FiDto\FiKeybean;
use Engtuncay\Phputils8\FiDto\FkbList;
use Engtuncay\Phputils8\FiMeta\FimFiCol;

class {{classPref}}{{entityName}} implements IFkbTableMeta {

public function getITxTableName() : string {
  return self::GetTxTableName();
}

public static function  getTxTableName() : string{
  return "{{entityName}}";
}

{{classBody}}

public function genITableCols() : FkbList {
  return self::genTableCols();
}

public function genITableColsTrans():FkbList {
  return self::genTableColsTrans();
}

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
    //$txKey = $fkb->getValueByFiCol(FicFiMeta::txKey());
    //if ($txKey != null) {
    //  $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
    //}

    $ofcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader());
    if ($ofcTxHeader != null)
      $sbFiColMethodBody->append(sprintf("  \$fiCol->ofcTxHeader = '%s';\n", $ofcTxHeader));

    $ofcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldType());
    if ($ofcTxFieldType != null)
      $sbFiColMethodBody->append(sprintf("  \$fiCol->ofcTxFieldType = '%s';\n", $ofcTxFieldType));

    // $ofcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDbField());
    // if ($ofcTxDbField != null)
    //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcTxDbField = \"%s\";\n", $ofcTxDbField));

    // {
    //   $ofcTxRefField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxRefField());
    //   if ($ofcTxRefField != null)
    //     $sbFiColMethodBody->append(sprintf("  \$fiCol->ofcTxRefField = \"%s\";\n", $ofcTxRefField));
    // }


    //$ofcTxIdType = $fiCol->ofcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->ofcTxIdType);

    // if (!FiString.isEmpty(ofiTxIdType)) {
    // sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
    // sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
    // }

    // $ofcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoTransient());
    // if ($ofcBoTransient) {
    //   $sbFiColMethodBody->append("  fiCol.ofcBoTransient = true;\n");
    // }

    // $ofcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnLength()));
    // if ($ofcLnLength != null) {
    //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnLength = %s;\n", $ofcLnLength));
    // }

    // $ofcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnPrecision()));
    // if ($ofcLnPrecision != null) {
    //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnPrecision = %s;\n", $ofcLnPrecision));
    // }

    // $ofcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnScale()));
    // if ($ofcLnScale != null) {
    //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnScale = %s;\n", $ofcLnScale));
    // }

    // if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoNullable()))) {
    //   $sbFiColMethodBody->append("  fiCol.ofcBoNullable = false;\n");
    // }


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

    public function genFkbColMethodBodyDetail(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    //String
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::ofcTxFieldType()));
    //$txKey = $fkb->getValueByFiCol(FicFiMeta::txKey());
    //if ($txKey != null) {
    //  $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
    //}

    //    $fkbCol->addFiCol(FicFiCol::ofcTxFieldName(),'evsTxMustKod'); // "ofcTxFieldType", 'string'); //->ofcTxFieldType = 'string';
    // $fkbCol->addFiCol(FicFiCol::ofcTxFieldType(), 'string'); //->ofcTxFieldType = 'string';

    $ofcTxFieldName = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldName());
    if ($ofcTxFieldName != null)
      $sbFiColMethodBody->append(sprintf("  \$fkbCol->addFm(FimFiCol::ofcTxFieldName(), '%s');\n", $ofcTxFieldName));

    $ofcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader());
    if ($ofcTxHeader != null)
      $sbFiColMethodBody->append(sprintf("  \$fkbCol->addFm(FimFiCol::ofcTxHeader(), '%s');\n", $ofcTxHeader));

    $ofcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldType());
    if ($ofcTxFieldType != null)
      $sbFiColMethodBody->append(sprintf("  \$fkbCol->addFm(FimFiCol::ofcTxFieldType(), '%s');\n", $ofcTxFieldType));

    // $ofcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDbField());
    // if ($ofcTxDbField != null)
    //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcTxDbField = \"%s\";\n", $ofcTxDbField));

    // {
    //   $ofcTxRefField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxRefField());
    //   if ($ofcTxRefField != null)
    //     $sbFiColMethodBody->append(sprintf("  \$fiCol->ofcTxRefField = \"%s\";\n", $ofcTxRefField));
    // }


    //$ofcTxIdType = $fiCol->ofcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->ofcTxIdType);

    // if (!FiString.isEmpty(ofiTxIdType)) {
    // sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
    // sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
    // }

    // $ofcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoTransient());
    // if ($ofcBoTransient) {
    //   $sbFiColMethodBody->append("  fiCol.ofcBoTransient = true;\n");
    // }

    // $ofcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnLength()));
    // if ($ofcLnLength != null) {
    //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnLength = %s;\n", $ofcLnLength));
    // }

    // $ofcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnPrecision()));
    // if ($ofcLnPrecision != null) {
    //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnPrecision = %s;\n", $ofcLnPrecision));
    // }

    // $ofcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnScale()));
    // if ($ofcLnScale != null) {
    //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnScale = %s;\n", $ofcLnScale));
    // }

    // if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoNullable()))) {
    //   $sbFiColMethodBody->append("  fiCol.ofcBoNullable = false;\n");
    // }


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

  public function genFiMetaMethodBody(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    //String
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::ofcTxFieldType()));
    //$txKey = $fkb->getValueByFiCol(FicFiMeta::txKey());
    //if ($txKey != null) {
    //  $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
    //}

    // $ofcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::ofcTxHeader());
    // if ($ofcTxHeader != null)
    //   $sbFiColMethodBody->append(sprintf(" \$fiCol->ofcTxHeader = '%s';\n", $ofcTxHeader));

    // $ofcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldType());
    // if ($ofcTxFieldType != null)
    //   $sbFiColMethodBody->append(sprintf("  \$fiCol->ofcTxFieldType = '%s';\n", $ofcTxFieldType));

    // $ofcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDbField());
    // if ($ofcTxDbField != null)
    //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcTxDbField = \"%s\";\n", $ofcTxDbField));

    // {
    //   $ofcTxRefField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxRefField());
    //   if ($ofcTxRefField != null)
    //     $sbFiColMethodBody->append(sprintf("  fiCol.ofcTxRefField = \"%s\";\n", $ofcTxRefField));
    // }


    //$ofcTxIdType = $fiCol->ofcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->ofcTxIdType);

    // if (!FiString.isEmpty(ofiTxIdType)) {
    // sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
    // sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
    // }

    // $ofcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoTransient());
    // if ($ofcBoTransient) {
    //   $sbFiColMethodBody->append("  fiCol.ofcBoTransient = true;\n");
    // }

    // $ofcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnLength()));
    // if ($ofcLnLength != null) {
    //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnLength = %s;\n", $ofcLnLength));
    // }

    // $ofcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnPrecision()));
    // if ($ofcLnPrecision != null) {
    //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnPrecision = %s;\n", $ofcLnPrecision));
    // }

    // $ofcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnScale()));
    // if ($ofcLnScale != null) {
    //   $sbFiColMethodBody->append(sprintf("  fiCol.ofcLnScale = %s;\n", $ofcLnScale));
    // }

    // if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoNullable()))) {
    //   $sbFiColMethodBody->append("  fiCol.ofcBoNullable = false;\n");
    // }


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

    $ofcTxDesc = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDesc());
    //if ($ofcTxDesc != null)
    $sbFiColMethodBody->append(sprintf("  \$fiCol->ofcTxDesc = \"%s\";\n", $ofcTxDesc));

    return $sbFiColMethodBody;
  }

  public function genFiMetaMethodBodyFieldDefs(FiKeybean $fkb): FiStrbui
  {
    //StringBuilder
    $sbFmtMethodBodyFieldDefs = new FiStrbui();

    $txKey = $fkb->getValueByFiCol(FicFiMeta::ofmTxKey());
    if ($txKey != null) {
      $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
    }

    $txValue = $fkb->getValueByFiCol(FicFiMeta::ofmTxValue());
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
public static function genTableColsExtra() : FicList {
    \$ficList = new FicList();

  {{ficListBodyExtra}}

  return \$ficList;
}
EOD;
  }

  /**
   * @return string
   */
  public function getTempGenGiColsTransList(): string
  {
    return <<<EOD
public static function genTableColsTrans() : FicList {
  \$ficList = new FicList();

  {{ficListBodyTrans}}

  return \$ficList;
}
EOD;
  }

    /**
   * @return string
   */
  public function getTempGenFkbColsTransList(): string
  {
    return <<<EOD
public static function genTableColsTrans() : FkbList {
  \$ficList = new FkbList();

  {{ficListBodyTrans}}

  return \$ficList;
}
EOD;
  }

  /**
   * @return string
   */
  public function getTempGenFiColsMethod(): string
  {
    return <<<EOD
public static function genTableCols() : FicList {
  \$ficList = new FicList();

  {{ficListBody}}

  return \$ficList;
}
EOD;
  }

    /**
   * @return string
   */
  public function getTempGenFkbColsList(): string
  {
    return <<<EOD
public static function genTableCols() : FkbList {
  \$ficList = new FkbList();

  {{ficListBody}}

  return \$ficList;
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
    $sbFclListBody->append("\$ficList->add(self::$methodName());\n");
    // $sbFclListBodyExtra->append("ficList.Add($methodName" . "Ext());\n");
  }

  /**
   * @param FiStrbui $sbFclListBodyTrans
   * @param string $methodName
   * @return void
   */
  public function doTransientFieldOps(FiStrbui $sbFclListBodyTrans, string $methodName): void
  {
    $sbFclListBodyTrans->append("ficList->add($methodName());\n");
  }

  public function genFiColAddDescDetail(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbText = new FiStrbui(); // new StringBuilder();

    $ofcTxFielDesc = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDesc());

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
    return self::convertFieldNameToLowerCamelCase($fieldName);
  }

  /**
   * Converts a field name to standard method name format.
   *
   * @param string $fieldName
   * @return string
   */
  public static function convertFieldNameToLowerCamelCase(string $fieldName): string
  {
    // Başlangıçta eğer fieldName boşsa direkt döndür
    if (FiString::isEmpty($fieldName)) return "";

    if (!FiString::hasLowercaseLetter($fieldName)) {
      $fieldName = strtolower($fieldName);
      return lcfirst($fieldName);
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
        if (ctype_lower($characters[$i-1])) { // && ctype_lower($characters[$i])
          $result .= $characters[$i];
        } // Kendinden önceki büyükse küçült
        else if (ctype_upper($characters[$i-1])) {
          $result .= strtolower($characters[$i]);
        } else if ($characters[$i-1]=='_') { 
          $result .= strtolower($characters[$i]);
        } else {  // Kendinden önceki sayı vs (_ dışında karakterse) ise büyült
          $result .= strtoupper($characters[$i]);
        }
      }

      return $result;
    }
  }
}
