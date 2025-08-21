<?php

namespace Codegen\Modals;

use Codegen\ficols\FicFiCol;
use Codegen\ficols\FicFiMeta;
use Engtuncay\Phputils8\Core\FiBool;
use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\Core\FiString;
use Engtuncay\Phputils8\Core\FiTemplate;
use Engtuncay\Phputils8\FiCol\FicValue;
use Engtuncay\Phputils8\FiDto\FiKeybean;
use Engtuncay\Phputils8\FiDto\FkbList;

/**
 * Java Templates For Code Generator
 */
class CgmJavaSpecs implements ICogFicSpecs
{
  public function getTemplateFkbColMethod(): string
  {
    return "";
  }

  public function getTemplateFiMetaMethod(): string
  {
    return "";
  }

  public function getTemplateFkbColClass(): string
  {
    return "";
  }

  public function genFiMetaMethodBody(FiKeybean $fkbItem): FiStrbui
  {
    return new FiStrbui();
  }

  public function genFkbColMethodBodyDetail(FiKeybean $fkbItem): FiStrbui
  {
    return new FiStrbui();
  }

  public function getTempGenFkbColsList(): string
  {
    return "";
  }

  public function getTempGenFkbColsTransList(): string
  {
    return "";
  }

  /**
   * @param mixed $txEntityName
   * @return mixed
   */
  public function checkClassNameStd(mixed $txEntityName): string
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

  /**
   * @param mixed $fieldName
   * @return string
   */
  public function checkMethodNameStd(mixed $fieldName): string
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

        // Kendinden önceki küçükse veya büyükse, aynen ekle
        if (ctype_lower($characters[$i - 1]) || ctype_upper($characters[$i - 1])) { // && ctype_lower($characters[$i])
          $result .= $characters[$i];
        } // Kendinden önceki büyükse, aynen ekle
        //        else if (ctype_upper($characters[$i - 1])) {
        //          $result .= $characters[$i];
        //        }
        else { // Kendinden önceki sayı vs ise büyült
          $result .= strtoupper($characters[$i]);
        }
      }

      return $result;
    }
  }

  public function getTemplateFicClass(): string
  {
    //FicFiCol::ofcTxHeader();

    //String
    $templateMain = <<<EOD
import ozpasyazilim.utils.table.FiCol;
import ozpasyazilim.utils.table.OzColType;
import ozpasyazilim.utils.table.FiColList;
import ozpasyazilim.utils.fidbanno.FiIdGenerationType;
import ozpasyazilim.utils.fidborm.IFiTableMeta;
      
public class {{classPref}}{{entityName}} implements IFiTableMeta
{

  public static String getTxTableName()
  {
    return "{{tableName}}";
  }
  
  public String getITxTableName()
  {
    return getTxTableName();
  }

  public FiColList genITableCols()
  {
    return genTableCols();
  }
  
  public FiColList genITableColsTrans()
  {
    return genTableColsTrans();
  }
  
  public static String getTxPrefix()
  {
    return "{{tablePrefix}}";
  }

  public String getITxPrefix()
  {
    return getTxPrefix();
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
      $sbFiColMethodBody->append(sprintf("  fiCol.setOfcTxHeader(\"%s\");\n", $ofcTxHeader));

    $ofcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::ofcTxFieldType());
    if ($ofcTxFieldType != null)
      $sbFiColMethodBody->append(sprintf("  fiCol.setOfcTxFieldType (\"%s\");\n", $ofcTxFieldType));

    $ofcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDbField());
    if ($ofcTxDbField != null)
      $sbFiColMethodBody->append(" fiCol.setOfcTxDbField (\"$ofcTxDbField\");\n");

    //$ofcTxIdType = $fiCol->ofcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->ofcTxIdType);

    // if (!FiString.isEmpty(ofiTxIdType)) {
    // sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
    // sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
    // }

    $ofcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoTransient());
    if ($ofcBoTransient) {
      $sbFiColMethodBody->append("  fiCol.setOfcBoTransient(true);\n");
    }

    $ofcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnLength()));
    if ($ofcLnLength != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.setOfcLnLength(%s);\n", $ofcLnLength));
    }

    $ofcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnPrecision()));
    if ($ofcLnPrecision != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.setOfcLnPrecision(%s);\n", $ofcLnPrecision));
    }

    $ofcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::ofcLnScale()));
    if ($ofcLnScale != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.setOfcLnScale(%s);\n", $ofcLnScale));
    }

    if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::ofcBoNullable()))) {
      $sbFiColMethodBody->append("  fiCol.setOfcBoNullable(false);\n");
    }

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

    $ofcTxFielDesc = $fkbItem->getValueByFiCol(FicFiCol::ofcTxDesc());
    //if ($ofcTxFielDesc != null)
    $sbFiColMethodBody->append(sprintf("  fiCol.setOfcTxFieldDesc(\"%s\");\n", $ofcTxFielDesc));

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
public static FiColList genTableColsExtra() {
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
public static FiColList genTableColsTrans() {
  FiColList ficList = new FiColList();
  
  {{ficListBodyTrans}}
  
  return ficList;
}
EOD;
  }

  /**
   * @return string
   */
  public function getTempGenFiColsMethod(): string
  {
    return <<<EOD
public static FiColList genTableCols() {
  FiColList ficList = new FiColList();

  {{ficListBody}}

  return ficList;
}
EOD;
  }

  /**
   * @param FiStrbui $sbFclListBody
   * @param string $methodName
   * @return void
   */
  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, string $methodName): void
  { //, FiStrbui $sbFclListBodyExtra
    $sbFclListBody->append("ficList.add($methodName());\n");
    //$sbFclListBodyExtra->append("ficList.add($methodName" . "Ext());\n");
  }

  /**
   * @param FiStrbui $sbFclListBodyTrans
   * @param string $methodName
   * @return void
   */
  public function doTransientFieldOps(FiStrbui $sbFclListBodyTrans, string $methodName): void
  {
    $sbFclListBodyTrans->append("ficList.add($methodName());\n");
  }

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

  public function genFiColAddDescDetail(FiKeybean $fkbItem): FiStrbui
  {
    // TODO: Implement genFiColAddDescBody() method.
    $sbFiColAddDescBody = new FiStrbui();
    return $sbFiColAddDescBody;
  }
}
