<?php

namespace Codegen\Modals;

use Codegen\FiMetas\App\FimQcColClassTempAreas;
use Engtuncay\Phputils8\FiCores\FiBool;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCols\FicValue;
use Engtuncay\Phputils8\FiCores\FiTemplate;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCodeTemp;
use Engtuncay\Phputils8\FiMetas\FimFiCol;
use Engtuncay\Phputils8\FiMetas\FimQcSpecFields;

class CogJavaFiCol implements ICogSpecsGenCol
{
  public function genClassCode(FkbList $fkbList): string
  {
    $iCogSpecs = new CogSpecsJava();

    //if (FiCollection.isEmpty(fiCols)) return;
    $sbClassBody = new FiStrbui(); //new StringBuilder();
    $sbFiColMethodsBody = new FiStrbui(); //new StringBuilder();

    //int
    //$index = 0;

    $sbFclListBody = new FiStrbui();
    //$sbFclListBodyExtra = new FiStrbui();
    $sbFclListBodyTrans = new FiStrbui();
    $sbFiColAddDescDetail = new FiStrbui();

    $templateFiColMethod = $this->getTemplateColMethod();
    //$templateFiColMethodExtra = $iFiColClass->getTemplateFiColMethodExtra();

    /**
     * @var FiKeybean $fkbItem
     */
    foreach ($fkbList as $fkbItem) {

      /**
       * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
       */
      $sbFiColMethodBody = $this->genColMethodBody($fkbItem);

      //$sbFiColAddDescDetail->append($iCogSpecsFiCol->genColAddDescMethodBody($fkbItem,$iCogSpecs)->toString());

      //FiKeybean
      $fkbFicMethBody = new FiKeybean();

      //String
      $fcTxFieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());

      if (FiString::isEmpty($fcTxFieldName)) continue;

      $fcTxHeader = FiString::orEmpty($fkbItem->getValueByFiCol(FicFiCol::fcTxHeader()));

      //fkbFiColMethodBody.add("fieldMethodName", FiString.capitalizeFirstLetter(fieldName));
      $fkbFicMethBody->addFim(FimFiCodeTemp::fieldMethodName(), $iCogSpecs->checkMethodNameStd($fcTxFieldName));
      $fkbFicMethBody->addFim(FimFiCodeTemp::fieldName(), $fcTxFieldName);
      $fkbFicMethBody->addFim(FimFiCodeTemp::fieldHeader(), $fcTxHeader);
      $fkbFicMethBody->addFim(FimFiCodeTemp::colMethodBody(), $sbFiColMethodBody->toString());

      /**
       * @var string $txFiColMethod
       */
      $txFiColMethod = FiTemplate::replaceParams($templateFiColMethod, $fkbFicMethBody);

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
      $methodName = $iCogSpecs->checkMethodNameStd($fcTxFieldName);

      if (!$fcBoTransient === true) {
        $this->doNonTransientFieldOps($sbFclListBody, $fkbItem, $iCogSpecs);
        //sbFclListBody.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      } else {
        $this->doTransientFieldOps($sbFclListBodyTrans, $fkbItem, $iCogSpecs);
        //sbFclListBodyTrans.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      }

      //$index++;
    }

    // String
    $tempGenFiCols = $this->getTemplateColListMethod();

    // String
    $txResGenTableColsMethod = FiTemplate::replaceParams($tempGenFiCols, FiKeybean::bui()->buiPut("ficListBody", $sbFclListBody->toString()));

    $sbClassBody->append("\n")->append($txResGenTableColsMethod)->append("\n");

    // String
    $tempGenFiColsTrans = $this->getTemplateColListTransMethod();

    //    String
    $txResGenTableColsMethodTrans = FiTemplate::replaceParams($tempGenFiColsTrans, FiKeybean::bui()->buiPut("ficListBodyTrans", $sbFclListBodyTrans->toString()));
    $sbClassBody->append("\n")->append($txResGenTableColsMethodTrans)->append("\n");

    //$tempGenFiColsExt = $iCogSpecsFiCol->getTemplateFiColsExtraListMethod();

    //$txResGenTableColsMethodExtra = FiTemplate::replaceParams($tempGenFiColsExt, FiKeybean::bui()->buiPut("ficListBodyExtra", $sbFclListBodyExtra->toString()));
    //$sbClassBody->append("\n")->append($txResGenTableColsMethodExtra)->append("\n");

    $sbClassBody->append("\n");
    $sbClassBody->append($sbFiColMethodsBody->toString());

    //
    $classPref = "Fic";
    // URFIX entity name çekilecek
    // String
    $txEntityName = $fkbList->get(0)?->getValueByFiCol(FicFiCol::fcTxEntityName());

    $txTablePrefix = $fkbList->get(0)?->getValueByFiCol(FicFiCol::fcTxPrefix());
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.fcTxEntityName());
    //
    $fkbParamsMain = new FiKeybean();


    $fkbParamsMain->addFim(FimFiCodeTemp::classPref(), $classPref);
    $fkbParamsMain->addFim(FimFiCodeTemp::entityName(), $iCogSpecs->checkClassNameStd($txEntityName));
    $fkbParamsMain->addFim(FimFiCodeTemp::tableName(), $txEntityName);
    $fkbParamsMain->addFim(FimFiCodeTemp::tablePrefix(), $txTablePrefix);
    $fkbParamsMain->addFim(FimFiCodeTemp::classBody(), $sbClassBody->toString());
    //$fkbParamsMain->addFim(FimFiCodeTemp::classBlockExtra(), $sbClassBodyExtra->toString());
    $fkbParamsMain->add("addFieldDescDetail", $sbFiColAddDescDetail->toString());

    $sbExtra = $this->genClassBlockExtra($iCogSpecs, $fkbList);

    $fkbParamsMain->addFim(FimFiCodeTemp::classBlockExtra(),  $sbExtra->toString());

    // String
    $templateMain = $this->getTemplateColClass();
    $txResult = FiTemplate::replaceParams($templateMain, $fkbParamsMain);

    return $txResult;
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


  /**
   * FicEntity Class -> FiCols
   *
   * @return string
   */
  public function getTemplateColClass(): string
  {
    //FicFiCol::fcTxHeader();
    $txKeyClassBloEx = FimFiCodeTemp::classBlockExtra()->getTxKey();

    //String
    $templateMain = <<<EOD

import ozpasyazilim.utils.table.FiCol;
import ozpasyazilim.utils.table.FicList;
import ozpasyazilim.utils.fidborm.IFiTableMeta;
import ozpasyazilim.utils.datatypes.Fkfic;
import ozpasyazilim.utils.fidborm.AbsFicTable;
      
public class {{classPref}}{{entityName}} extends AbsFicTable implements IFiTableMeta
{

  public static String getTxTableName()
  {
    return "{{tableName}}";
  }
  
  public String getITxTableName()
  {
    return getTxTableName();
  }

  public FicList genITableCols()
  {
    return genTableCols();
  }
  
  public FicList genITableColsTrans()
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

{{{$txKeyClassBloEx}}}

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
{{colMethodBody}}
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
{{colMethodBody}}
  return fiCol;
}
EOD;
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
public static FicList genTableColsExtra() {
  FicList ficList = new FicList();

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
public static FicList genTableColsTrans() {
  FicList ficList = new FicList();
  
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
public static FicList genTableCols() {
  FicList ficList = new FicList();

  {{ficListBody}}

  return ficList;
}
EOD;
  }

  /**
   * @param FiStrbui $sbFclListBody
   * @param FiKeybean $fkbItem
   * @param ICogSpecs $iCogSpecs
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
   * @param FiStrbui $sbContent
   * @param string $methodName
   * @return void
   */
  public function doTransientFieldOps(FiStrbui $sbContent, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    //$sbFclListBodyTrans->append("ficList.add($methodName());\n");
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbContent->append("ficList.add($methodName());\n");
  }


  public function genFiColAddDescMethodBody(FiKeybean $fkbItem, ICogSpecs $iCogSpecs): FiStrbui
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
    $sbExtra = new FiStrbui();

    $sbGetFkbFields = new FiStrbui();
    $sbGetFkbDdFields = new FiStrbui();

    /** @var FiKeybean $fkbItem  */
    foreach ($fkbList as $fkbItem) {
      # code...
      $fcTxFieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());

      // Eğer fieldName boşsa bu alanı atla
      if (FiString::isEmpty($fcTxFieldName)) continue;

      $this->processGetFkbDdFields($sbGetFkbDdFields, $fkbItem);

      $stMethodName = $iCogSpecs->checkMethodNameStd($fcTxFieldName);
      $sbGetFkbFields->append("fkb.addFic({$stMethodName}());\n");
    }

    // getDdFields

    $txTempMethodFkbAllFields = $this->getTempMethodFkbFields();
    $txFkbAllFieldsFull = FiTemplate::replaceParams($txTempMethodFkbAllFields, FiKeybean::bui()->buiPut("getFkbFieldsAllContent", $sbGetFkbFields->toString()));

    $txTempMethodFkbDdFields = $this->getTempMethodFkbDdFields();
    $txFkbDdFieldsFull = FiTemplate::replaceParams($txTempMethodFkbDdFields
      , FiKeybean::bui()->buiPut("getFkbDdFields"
      , $sbGetFkbDdFields->toString()));

    $sbExtra->append($txFkbAllFieldsFull);
    $sbExtra->append("\n\n");
    $sbExtra->append($txFkbDdFieldsFull);

    return $sbExtra;
  }

  public function getTempMethodFkbFields()
  {

    $txMethodName = CgmUtilsName::getMethodNameGetFkbFieldsAll();
    $cogSpecs = new CogSpecsJava();
    $stdTxMethodName = $cogSpecs->checkMethodNameStd($txMethodName);

    return <<<EOD
public static Fkfic {$stdTxMethodName}(){

  Fkfic fkb = new Fkfic();
  
{{getFkbFieldsAllContent}}
  return fkb;
}
EOD;
  }

  public function getTempMethodFkbDdFields()
  {

    $txMethodName = CgmUtilsName::getMethodNameGetFkbDdFields();

    $cogSpecs = new CogSpecsJava();
    $stdTxMethodName = $cogSpecs->checkMethodNameStd($txMethodName);
    
    $keyGetFkbDdFields = FimQcColClassTempAreas::getFkbDdFields()->getTxKey();

    return <<<EOD
public static Fkfic {$stdTxMethodName}(){

  Fkfic fkb = new Fkfic();
  
{{{$keyGetFkbDdFields}}}
  return fkb;
}
EOD;
  }

  public function processGetFkbDdFields(FiStrbui $sbGetFkbDdFields, FiKeybean  $fkbItem): void 
  {
    $fcTxFieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());

    if(FiString::any($fcTxFieldName
    , FimQcSpecFields::qcfTxSqTableName()->getTxKey()
    )) {
      $iCogSpecs = new CogSpecsJava();
      $stMethodName = $iCogSpecs->checkMethodNameStd($fcTxFieldName);
      $sbGetFkbDdFields->append("fkb.addFic({$stMethodName}());\n");
    }
    
  }

}
