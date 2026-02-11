<?php

namespace Codegen\Modals;


use Engtuncay\Phputils8\FiCores\FiBool;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCols\FicValue;
use Engtuncay\Phputils8\FiDtos\FiKeybean;

class CogSpecsJavaFiCol implements ICogSpecsGenCol
{

  /**
   * FicEntity Class -> FiCols
   *
   * @return string
   */
  public function getTemplateColClass(): string
  {
    //FicFiCol::fcTxHeader();

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

  // 
  public function getTemplateColMethod(): string
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


  public function getTemplateColMethodExtra(): string
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




  public function genColMethodBody(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    //String
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::fcTxFieldType()));

    $fcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::fcTxHeader());
    if ($fcTxHeader != null)
      $sbFiColMethodBody->append(sprintf("  fiCol.setFcTxHeader(\"%s\");\n", $fcTxHeader));

    $fcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldType());
    if ($fcTxFieldType != null)
      $sbFiColMethodBody->append(sprintf("  fiCol.setFcTxFieldType (\"%s\");\n", $fcTxFieldType));

    $fcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::fcTxDbField());
    if ($fcTxDbField != null)
      $sbFiColMethodBody->append(" fiCol.setFcTxDbField (\"$fcTxDbField\");\n");

    //$fcTxIdType = $fiCol->fcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->fcTxIdType);

    // if (!FiString.isEmpty(ofiTxIdType)) {
    // sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
    // sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
    // }

    $fcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoTransient());
    if ($fcBoTransient) {
      $sbFiColMethodBody->append("  fiCol.setFcBoTransient(true);\n");
    }

    $fcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnLength()));
    if ($fcLnLength != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.setFcLnLength(%s);\n", $fcLnLength));
    }

    $fcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnPrecision()));
    if ($fcLnPrecision != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.setFcLnPrecision(%s);\n", $fcLnPrecision));
    }

    $fcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnScale()));
    if ($fcLnScale != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.setFcLnScale(%s);\n", $fcLnScale));
    }

    if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoNullable()))) {
      $sbFiColMethodBody->append("  fiCol.setFcBoNullable(false);\n");
    }

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

  public function genColMethodBodyDetailExtra(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    $fcTxFielDesc = $fkbItem->getValueByFiCol(FicFiCol::fcTxDesc());
    //if ($fcTxFielDesc != null)
    $sbFiColMethodBody->append(sprintf("  fiCol.setFcTxFieldDesc(\"%s\");\n", $fcTxFielDesc));

    return $sbFiColMethodBody;
  }



  /**
   * @return string
   */
  public function getTemplateColListExtraMethod(): string
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
  public function getTemplateColListTransMethod(): string
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
  public function getTemplateColListMethod(): string
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
  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  { //, FiStrbui $sbFclListBodyExtra
    //$sbFclListBody->append("ficList.add($methodName());\n");
    //$sbFclListBodyExtra->append("ficList.add($methodName" . "Ext());\n");
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbFclListBody->append("ficList.add($methodName());\n");
  }

  /**
   * @param FiStrbui $sbFclListBodyTrans
   * @param string $methodName
   * @return void
   */
  public function doTransientFieldOps(FiStrbui $sbFclListBodyTrans, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    //$sbFclListBodyTrans->append("ficList.add($methodName());\n");
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbFclListBodyTrans->append("ficList.add($methodName());\n");
  }


  public function genFiColAddDescMethodBody(FiKeybean $fkbItem, ICogSpecs $iCogSpecs): FiStrbui
  {
    // TODO: Implement genFiColAddDescBody() method.
    $sbFiColAddDescBody = new FiStrbui();
    return $sbFiColAddDescBody;
  }
}
