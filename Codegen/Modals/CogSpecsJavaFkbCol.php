<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiBool;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCols\FicValue;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCores\FiTemplate;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCodeTemp;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

class CogSpecsJavaFkbCol implements ICogSpecsGenCol
{
  public function genClassCode(FkbList $fkbList): string
  {
    $iCogSpecs = new CogSpecsJava();

    $sbClassBlock = new FiStrbui(); //new StringBuilder();
    $sbFiColMethodsBody = new FiStrbui(); //new StringBuilder();

    //int
    //$index = 0;

    $sbFclListBody = new FiStrbui();
    //$sbFclListBodyExtra = new FiStrbui();
    $sbFclListBodyTrans = new FiStrbui();
    //$sbFiColAddDescDetail = new FiStrbui();
    $sbPrepFkbFields = new FiStrbui();

    $templateFiColMethod = $this->getTemplateColMethod();
    //$templateFiColMethodExtra = $iFiColClass->getTemplateFiColMethodExtra();

    /**
     * @var FiKeybean $fkbItem
     */
    foreach ($fkbList as $fkbItem) {
      self::processFkbItem($this, $fkbItem, $iCogSpecs, $templateFiColMethod, $sbFiColMethodsBody, $sbFclListBody, $sbFclListBodyTrans, $sbPrepFkbFields);
      //$index++;
    }

    // String
    $txGenTableColsMethod = FiTemplate::replaceParams($this->getTemplateColListMethod(), FiKeybean::bui()->buiPut("fkbListBody", $sbFclListBody->toString()));

    $sbClassBlock->append("\n")->append($txGenTableColsMethod)->append("\n");

    // String
    $txGenTableColsMethodTrans = FiTemplate::replaceParams($this->getTemplateColListTransMethod(), FiKeybean::bui()->buiPut("fkbListBodyTrans", $sbFclListBodyTrans->toString()));

    $sbClassBlock->append("\n")->append($txGenTableColsMethodTrans)->append("\n");

    $txGenFkbFields = FiTemplate::replaceParams($this->getTemplateGenFkbFields(), FiKeybean::bui()->buiPut("genFkbFieldsBlock", $sbPrepFkbFields->toString()));

    $sbClassBlock->append("\n")->append($txGenFkbFields)->append("\n");

    //$tempGenFiColsExt = $iCogSpecs->getTempGenFiColsExtraList();

    //$txResGenTableColsMethodExtra = FiTemplate::replaceParams($tempGenFiColsExt, FiKeybean::bui()->buiPut("fkbListBodyExtra", $sbFclListBodyExtra->toString()));
    //$sbClassBlock->append("\n")->append($txResGenTableColsMethodExtra)->append("\n");

    $sbClassBlock->append("\n");
    $sbClassBlock->append($sbFiColMethodsBody->toString());

    // Fkc: FiKeybean Col
    $classPref = "Fkc";

    // String
    $txEntityName = $fkbList->get(0)?->getFimValue(FimFiCol::fcTxEntityName());

    $txTablePrefix = $fkbList->get(0)?->getFimValue(FimFiCol::fcTxPrefix());
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.fcTxEntityName());
    //
    $fkbParamsMain = new FiKeybean();
    $fkbParamsMain->addFim(FimFiCodeTemp::classPref() , $classPref);
    $fkbParamsMain->addFim( FimFiCodeTemp::entityName() , $iCogSpecs->checkClassNameStd($txEntityName));
    $fkbParamsMain->addFim(FimFiCodeTemp::tableName(), $txEntityName);
    $fkbParamsMain->addFim(FimFiCodeTemp::tablePrefix(), $txTablePrefix);
    $fkbParamsMain->addFim(FimFiCodeTemp::classBody(), $sbClassBlock->toString());
    //$sbFiColAddDescDetail->toString()
    $fkbParamsMain->add("addFieldDescDetail", "");
    $sbClassBlockExtra = $this->genClassBlockExtra($iCogSpecs, $fkbList);

    $sbClassBlockExtra->prepend("// Extra \n\n");

    $fkbParamsMain->addFim(FimFiCodeTemp::classBlockExtra(), $sbClassBlockExtra->toString());

    $sbClassBlockExtra = $this->genClassBlockExtra($iCogSpecs, $fkbList);


    // String
    $templateMain = $this->getTemplateColClass();
    $txResult = FiTemplate::replaceParams($templateMain, $fkbParamsMain);

    return $txResult;
  }


  public function getTemplateColMethod(): string
  {
    return <<<EOD
public static FiKeybean {{fieldMethodName}}()
{
  FiKeybean fkbCol = new FiKeybean();
{{fkbColMethodBody}}
  return fkbCol;
}
EOD;
  }



  public function getTemplateColMethodExtra(): string
  {
    return <<<EOD
public static FiKeybean {{fieldMethodName}}Ext()
{
  FiKeybean fkbCol = {{fieldMethodName}}();
{{fkbColMethodExtraBody}}
  return fkbCol;
}
EOD;
  }

  /**
   * FkcEntity Class -> FkbCols
   * 
   * @return string
   */
  public function getTemplateColClass(): string
  {
    //FicFiCol::fcTxHeader();

    //String
    $templateMain = <<<EOD

import ozpasyazilim.utils.datatypes.FiKeybean;
import ozpasyazilim.utils.fidborm.AbsFkbTable;
import ozpasyazilim.utils.fidborm.IFiTableMetaFkc;
import ozpasyazilim.utils.datatypes.FkbList;
import ozpasyazilim.utils.ficols.FimFiCol;

public class {{classPref}}{{entityName}} extends AbsFkbTable implements IFiTableMetaFkc
{

  public static String getTxTableName()
  {
    return "{{tableName}}";
  }
  
  public String getITxTableName()
  {
    return getTxTableName();
  }

  public FkbList genITableCols()
  {
    return genTableCols();
  }
  
  public FkbList genITableColsTrans()
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


  public function genColMethodBody(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    //String
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::fcTxFieldType()));

    $fcTxFieldName = $fkbItem->getValueByFiMeta(FimFiCol::fcTxFieldName());
    if ($fcTxFieldName != null) {
      $sbFiColMethodBody->append(sprintf("  fkbCol.addFieldBy(FimFiCol.fcTxFieldName(), \"%s\");\n", $fcTxFieldName));
    }

    $fcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::fcTxHeader());
    if ($fcTxHeader != null) {
      $sbFiColMethodBody->append(sprintf("  fkbCol.addFieldBy(FimFiCol.fcTxHeader(), \"%s\");\n", $fcTxHeader));
    }


    $fcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldType());
    if ($fcTxFieldType != null) {
      $sbFiColMethodBody->append(sprintf("  fkbCol.addFieldBy(FimFiCol.fcTxFieldType(), \"%s\");\n", $fcTxFieldType));
    }

    $fcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::fcTxDbField());
    if ($fcTxDbField != null) {
      $sbFiColMethodBody->append(sprintf("  fkbCol.addFieldBy(FimFiCol.fcTxDbField(), \"%s\");\n", $fcTxDbField));
    }

    //$fcTxIdType = $fiCol->fcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->fcTxIdType);

    // if (!FiString.isEmpty(ofiTxIdType)) {
    // sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
    // sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
    // }

    $fcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoTransient());
    if ($fcBoTransient) {
      $sbFiColMethodBody->append(sprintf("  fkbCol.addFieldBy(FimFiCol.fcBoTransient(), true);\n"));
    }

    $fcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnLength()));
    if ($fcLnLength != null) {
      $sbFiColMethodBody->append(sprintf("  fkbCol.addFieldBy(FimFiCol.fcLnLength(), %s);\n", $fcLnLength));
    }

    $fcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnPrecision()));
    if ($fcLnPrecision != null) {
      $sbFiColMethodBody->append(sprintf("  fkbCol.addFieldBy(FimFiCol.fcLnPrecision(), %s);\n", $fcLnPrecision));
    }

    $fcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnScale()));
    if ($fcLnScale != null) {
      $sbFiColMethodBody->append(sprintf("  fkbCol.addFieldBy(FimFiCol.fcLnScale(), %s);\n", $fcLnScale));
    }

    if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoNullable()))) {
      $sbFiColMethodBody->append("  fkbCol.addFieldBy(FimFiCol.fcBoNullable(), false);\n");
    }

    //        if (FiBool.isTrue(fiCol.getFcBoUnique())) {
    //          sbFiColMethodBody.append("\tfiCol.fcBoUnique = true;\n");
    //        }
    //
    //        if (FiBool.isTrue(fiCol.getFcBoUniqGro1())) {
    //          sbFiColMethodBody.append("\tfkbCol.fcBoUniqGro1 = true;\n");
    //        }
    //
    //        if (FiBool.isTrue(fiCol.getFcBoUtfSupport())) {
    //          sbFiColMethodBody.append("\tfkbCol.fcBoUtfSupport = true;\n");
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

  public function genGenMethodBodyDetailExtra(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    $fcTxFielDesc = $fkbItem->getValueByFiCol(FicFiCol::fcTxDesc());
    //if ($fcTxFielDesc != null)
    $sbFiColMethodBody->append(sprintf("  fkbCol.addFieldBy(FimFiCol.fcTxFieldDesc(), \"%s\");\n", $fcTxFielDesc));

    return $sbFiColMethodBody;
  }

  /**
   * @return string
   */
  public function getTempGenTableColsExtraMethod(): string
  {
    return <<<EOD
public static FiColList genTableColsExtra() {
  FkbList fkbList = new FkbList();

  {{fkbListBodyExtra}}

  return fkbList;
}
EOD;
  }

  /**
   * @return string
   */
  public function getTemplateColListTransMethod(): string
  {
    return <<<EOD
public static FkbList genTableColsTrans() {
  FkbList fkbList = new FkbList();
  
  {{fkbListBodyTrans}}
  
  return fkbList;
}
EOD;
  }

  /**
   * 
   * genTableCols Method
   * 
   * @return string
   */
  public function getTemplateColListMethod(): string
  {
    return <<<EOD
public static FkbList genTableCols() {
  FkbList fkbList = new FkbList();

  {{fkbListBody}}

  return fkbList;
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
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbFclListBody->append("fkbList.add($methodName());\n");
    //$sbFclListBodyExtra->append("ficList.add($methodName" . "Ext());\n");
  }

  /**
   * @param FiStrbui $sbContent
   * @param string $methodName
   * @return void
   */
  public function doTransientFieldOps(FiStrbui $sbContent, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbContent->append("fkbList.add($methodName());\n");
  }

  public function genFiColAddDescDetail(FiKeybean $fkbItem, ICogSpecs $cogFicSpecs): FiStrbui
  {
    // TODO: Implement genFiColAddDescBody() method.
    $sbFiColAddDescBody = new FiStrbui();
    return $sbFiColAddDescBody;
  }

  public function getTemplateGenFkbFields(): string
  {
    return "";
  }

  public function prepBodyGenFkbFields(FiStrbui $sbContent, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    // will be implemented
  }

  public function genClassBlockExtra(ICogSpecs $iCogSpecs, FkbList $fkbList): FiStrbui
  {
    return new FiStrbui();
  }

  public function processFkbItem($iSpecsFkbCol, $fkbItem, $iCogSpecs, $templateFiColMethod, $sbFiColMethodsBody, $sbFclListBody, $sbFclListBodyTrans, $sbPrepFkbFields)
  {
    /**
     * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
     */
    $sbFiColMethodBody = $iSpecsFkbCol->genColMethodBody($fkbItem); //StringBuilder

    //$sbFiColAddDescDetail->append($iCogSpecs->genFiColAddDescDetail($fkbItem)->toString());

    //FiKeybean
    $fkbFiColMethodBody = new FiKeybean();

    //String
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $fcTxHeader = FiString::orEmpty($fkbItem->getValueByFiCol(FicFiCol::fcTxHeader()));

    //fkbFiColMethodBody.add("fieldMethodName", FiString.capitalizeFirstLetter(fieldName));
    $fkbFiColMethodBody->add("fieldMethodName", $iCogSpecs->checkMethodNameStd($fieldName));
    $fkbFiColMethodBody->add("fieldName", $fieldName);
    $fkbFiColMethodBody->add("fieldHeader", $fcTxHeader);
    $fkbFiColMethodBody->add("fkbColMethodBody", $sbFiColMethodBody->toString());

    /**
     * @var string $txFiColMethod
     */
    $txFiColMethod = FiTemplate::replaceParams($templateFiColMethod, $fkbFiColMethodBody);

    $sbFiColMethodsBody->append($txFiColMethod)->append("\n\n");

    //$sbFiColMethodBodyExtra = $iFiColClass->genFiColMethodBodyDetailExtra($fkbItem);
    //      $fkbFiColMethodBodyExtra = new FiKeybean();
    //      $fkbFiColMethodBodyExtra->add("fieldMethodName", $iFiColClass->checkMethodNameStd($fieldName));
    //      $fkbFiColMethodBodyExtra->add("fieldName", $fieldName);
    //      $fkbFiColMethodBodyExtra->add("fieldHeader", $fcTxHeader);
    //      $fkbFiColMethodBodyExtra->add("fiColMethodBody", $sbFiColMethodBodyExtra->toString());
    //      $txFiColMethodExtra = FiTemplate::replaceParams($templateFiColMethodExtra, $fkbFiColMethodBodyExtra);

    //      $sbFiColMethodsBody->append($txFiColMethodExtra)->append("\n\n");

    //
    $fcBoTransient = FicValue::toBool($fkbItem->getValueByFiCol(FicFiCol::fcBoTransient()));
    //$methodName = $iCogSpecs->checkMethodNameStd($fieldName);

    if (!$fcBoTransient === true) {
      $iSpecsFkbCol->doNonTransientFieldOps($sbFclListBody,  $fkbItem, $iCogSpecs);
      //sbFclListBody.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
    } else {
      $iSpecsFkbCol->doTransientFieldOps($sbFclListBodyTrans, $fkbItem, $iCogSpecs);
      //sbFclListBodyTrans.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
    }

    $iSpecsFkbCol->prepBodyGenFkbFields($sbPrepFkbFields, $fkbItem, $iCogSpecs);
  }
}
