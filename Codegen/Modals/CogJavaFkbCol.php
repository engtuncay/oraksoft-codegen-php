<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiBool;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCols\FicValue;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCores\FiTemplate;
use Engtuncay\Phputils8\FiDtos\Fkb;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCodeTemp;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

class CogJavaFkbCol implements ICogGenClassCode
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
     * @var Fkb $fkbItem
     */
    foreach ($fkbList as $fkbItem) {
      self::processFkbItem($this, $fkbItem, $iCogSpecs, $templateFiColMethod, $sbFiColMethodsBody, $sbFclListBody, $sbFclListBodyTrans, $sbPrepFkbFields);
      //$index++;
    }

    // String
    $txGenTableColsMethod = FiTemplate::replaceParams($this->getTemplateColListMethod(), Fkb::bui()->buiPut("fkbListBody", $sbFclListBody->toString()));

    $sbClassBlock->append("\n")->append($txGenTableColsMethod)->append("\n");

    // String
    $txGenTableColsMethodTrans = FiTemplate::replaceParams($this->getTemplateColListTransMethod(), Fkb::bui()->buiPut("fkbListBodyTrans", $sbFclListBodyTrans->toString()));

    $sbClassBlock->append("\n")->append($txGenTableColsMethodTrans)->append("\n");

    $txGenFkbFields = FiTemplate::replaceParams($this->getTemplateGenFkbFields(), Fkb::bui()->buiPut("genFkbFieldsBlock", $sbPrepFkbFields->toString()));

    $sbClassBlock->append("\n")->append($txGenFkbFields)->append("\n");

    //$tempGenFiColsExt = $iCogSpecs->getTempGenFiColsExtraList();

    //$txResGenTableColsMethodExtra = FiTemplate::replaceParams($tempGenFiColsExt, Fkb::bui()->buiPut("fkbListBodyExtra", $sbFclListBodyExtra->toString()));
    //$sbClassBlock->append("\n")->append($txResGenTableColsMethodExtra)->append("\n");

    $sbClassBlock->append("\n");
    $sbClassBlock->append($sbFiColMethodsBody->toString());

    // Fkc: Fkb Col
    $classPref = "Fkc";

    // String
    $txEntityName = $fkbList->get(0)?->getFimValue(FimFiCol::fcTxEntityName());

    $txTablePrefix = $fkbList->get(0)?->getFimValue(FimFiCol::fcTxPrefix());
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.fcTxEntityName());
    //
    $fkbParamsMain = new Fkb();
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
    $templateMain = $this->getTempColClass();
    $txResult = FiTemplate::replaceParams($templateMain, $fkbParamsMain);

    return $txResult;
  }


  public function getTemplateColMethod(): string
  {
    return <<<EOD
public static Fkb {{fieldMethodName}}()
{
  Fkb fkbCol = new Fkb();
{{fkbColMethodBody}}
  return fkbCol;
}
EOD;
  }



  public function getTemplateColMethodExtra(): string
  {
    return <<<EOD
public static Fkb {{fieldMethodName}}Ext()
{
  Fkb fkbCol = {{fieldMethodName}}();
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
  public function getTempColClass(): string
  {
    //FicFiCol::fcTxHeader();

    //String
    $templateMain = <<<EOD
import ozpasyazilim.utils.datatypes.Fkb;
import ozpasyazilim.utils.fidborm.AbsFkbTable;
import ozpasyazilim.utils.datatypes.FkbList;
import ozpasyazilim.utils.ficols.FimFiCol;

public class {{classPref}}{{entityName}} extends AbsFkbTable
{

{{classBody}}

}
EOD;

    return $templateMain;
  }


  public function genColMethodBody(Fkb $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    //String
    //$fieldType = FiCodeGen::convertExcelTypeToOzColType($fiCol->getTosOrEmpty(FicMeta::fcTxFieldType()));


    $fcTxFieldName = $fkbItem->getValueByFiMeta(FimFiCol::fcTxFieldName());
    if ($fcTxFieldName != null) {
      $sbFiColMethodBody->append("  fkbCol.addFieldBy(FimFiCol.fcTxFieldName(), \"{$fcTxFieldName}\");\n");
    }

    $fcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::fcTxHeader());
    if ($fcTxHeader != null) {
      $sbFiColMethodBody->append("  fkbCol.addFieldBy(FimFiCol.fcTxHeader(), \"{$fcTxHeader}\");\n");
    }

    $fcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldType());
    if ($fcTxFieldType != null) {
      $sbFiColMethodBody->append("  fkbCol.addFieldBy(FimFiCol.fcTxFieldType(), \"{$fcTxFieldType}\");\n");
    }

    $fcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::fcTxDbField());
    if ($fcTxDbField != null) {
      $sbFiColMethodBody->append("  fkbCol.addFieldBy(FimFiCol.fcTxDbField(), \"{$fcTxDbField}\");\n");
    }

    $fcTxUid = $fkbItem->getFimValue(FimFiCol::fcTxUid());
    if ($fcTxUid != null) {
      $sbFiColMethodBody->append("  fkbCol.addFieldBy(FimFiCol.fcTxUid(), \"{$fcTxUid}\");\n");
    }

    $fcLnId = $fkbItem->getFimValue(FimFiCol::fcLnId());
    if ($fcLnId != null) {
      $sbFiColMethodBody->append("  fkbCol.addFieldBy(FimFiCol.fcLnId(), {$fcLnId});\n");
    }

    $fcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoTransient());
    if ($fcBoTransient) {
      $sbFiColMethodBody->append("  fkbCol.addFieldBy(FimFiCol.fcBoTransient(), true);\n");
    }

    $fcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnLength()));
    if ($fcLnLength != null) {
      $sbFiColMethodBody->append("  fkbCol.addFieldBy(FimFiCol.fcLnLength(), {$fcLnLength});\n");
    }

    $fcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnPrecision()));
    if ($fcLnPrecision != null) {
      $sbFiColMethodBody->append("  fkbCol.addFieldBy(FimFiCol.fcLnPrecision(), {$fcLnPrecision});\n");
    }

    $fcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnScale()));
    if ($fcLnScale != null) {
      $sbFiColMethodBody->append("  fkbCol.addFieldBy(FimFiCol.fcLnScale(), {$fcLnScale});\n");
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

  public function genGenMethodBodyDetailExtra(Fkb $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    $fcTxFielDesc = $fkbItem->getValueByFiCol(FicFiCol::fcTxDesc());
    $sbFiColMethodBody->append("  fkbCol.addFieldBy(FimFiCol.fcTxFieldDesc(), \"{$fcTxFielDesc}\");\n");

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
  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, Fkb $fkbItem, ICogSpecs $iCogSpecs): void
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
  public function doTransientFieldOps(FiStrbui $sbContent, Fkb $fkbItem, ICogSpecs $iCogSpecs): void
  {
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbContent->append("fkbList.add($methodName());\n");
  }

  public function genFiColAddDescDetail(Fkb $fkbItem, ICogSpecs $cogFicSpecs): FiStrbui
  {
    // TODO: Implement genFiColAddDescBody() method.
    $sbFiColAddDescBody = new FiStrbui();
    return $sbFiColAddDescBody;
  }

  public function getTemplateGenFkbFields(): string
  {
    return "";
  }

  public function prepBodyGenFkbFields(FiStrbui $sbContent, Fkb $fkbItem, ICogSpecs $iCogSpecs): void
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

    //Fkb
    $fkbFiColMethodBody = new Fkb();

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
    //      $fkbFiColMethodBodyExtra = new Fkb();
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
