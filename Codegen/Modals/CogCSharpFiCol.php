<?php

namespace Codegen\Modals;

use Codegen\FiMetas\App\FimFiColClassTempAreas;
use Engtuncay\Phputils8\FiCores\FiBool;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCols\FicValue;
use Engtuncay\Phputils8\FiCores\FiTemplate;
use Engtuncay\Phputils8\FiDtos\Fkb;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCodeTemp;
use Engtuncay\Phputils8\FiMetas\FimFiCol;


class CogCSharpFiCol implements ICogGenClassCode
{
  public function genClassCode(FkbList $fkbList): string
  {
    $iCogSpecs = $this->getCogSpecs();

    //if (FiCollection.isEmpty(fiCols)) return;
    $sbClassContent = new FiStrbui(); //new StringBuilder();
    //$sbAllFiColMethods = new FiStrbui(); //new StringBuilder();

    $sbGetFkfAllContent = new FiStrbui(); //new StringBuilder();

    //int
    //$index = 0;

    $sbGetTableColsContent = new FiStrbui();
    //$sbFclListBodyExtra = new FiStrbui();
    $sbGenTableColsTransContent = new FiStrbui();
    //$sbFiColAddDescDetail = new FiStrbui();

    /**
     * ficol metodlar
     */
    $sbFiColMethods = new FiStrbui();

    $tempFiColMethod = $this->getTemplateColMethod();
    //$templateFiColMethodExtra = $iFiColClass->getTemplateFiColMethodExtra();

    /**
     * @var Fkb $fkbItem
     */
    foreach ($fkbList as $fkbItem) {

      //String
      $fcTxFieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());

      if (FiString::isEmpty($fcTxFieldName)) continue;

      $this->processFiColMethods($sbFiColMethods, $fkbItem);
      
      // Önce content oluşturulur. Sonra content, şablona eklenir.
      $this->processGetTableColsContent($sbGetTableColsContent, $fkbItem);
      $this->processGetTableColsTransContent($sbGenTableColsTransContent, $fkbItem);
      $this->processGetFkfAllContent($sbGetFkfAllContent, $fkbItem);

    }

    // String
    $tempGenFiCols = $this->getTempGenTableColsMethods();

    // String
    $txResGenTableColsMethod = FiTemplate::replaceParams($tempGenFiCols, Fkb::bui()->buiPut("ficListBody", $sbGetTableColsContent->toString()));

    $sbClassContent->append("\n")->append($txResGenTableColsMethod)->append("\n");

    $txMethodFullGetFkfAll = $this->getMethodFullGetfAll($sbGetFkfAllContent);

    // String
    $tempGenTableColsTrans = $this->getTempGenTableColsTransMethod();

    // String
    $txGenTableColsMethodFull = FiTemplate::replaceParams($tempGenTableColsTrans, Fkb::bui()->buiPut("ficListBodyTrans", $sbGenTableColsTransContent->toString()));

    $sbClassContent->append("\n")->append($txGenTableColsMethodFull)->append("\n");

    //$tempGenFiColsExt = $iCogSpecsFiCol->getTemplateFiColsExtraListMethod();
    //$txResGenTableColsMethodExtra = FiTemplate::replaceParams($tempGenFiColsExt, Fkb::bui()->buiPut("ficListBodyExtra", $sbFclListBodyExtra->toString()));
    //$sbClassBody->append("\n")->append($txResGenTableColsMethodExtra)->append("\n");

    $sbClassContent->append("\n");
    //$sbClassBody->append($sbAllFiColMethods->toString());
    $sbClassContent->append($sbFiColMethods->toString());


    // GetFkfAll metodu ekleniyor
    $sbClassContent->append("\n");
    $sbClassContent->append($txMethodFullGetFkfAll);

    //
    $classPref = "Fic";
    // URFIX entity name çekilecek
    // String
    $txEntityName = $fkbList->get(0)?->getValueByFiCol(FicFiCol::fcTxEntityName());

    $txTablePrefix = $fkbList->get(0)?->getValueByFiCol(FicFiCol::fcTxPrefix());
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.fcTxEntityName());
    //
    $fkbParamsClass = new Fkb();
    $fkbParamsClass->addFim(FimFiCodeTemp::classPref(), $classPref);
    $fkbParamsClass->addFim(FimFiCodeTemp::entityName(), $iCogSpecs->checkClassNameStd($txEntityName));
    $fkbParamsClass->addFim(FimFiCodeTemp::tableName(), $txEntityName);
    $fkbParamsClass->addFim(FimFiCodeTemp::tablePrefix(), $txTablePrefix);
    $fkbParamsClass->addFim(FimFiCodeTemp::classContent(), $sbClassContent->toString());
    //$fkbParamsMain->addFim(FimFiCodeTemp::classBlockExtra(), $sbClassBodyExtra->toString());
    //$fkbParamsClass->add("addFieldDescDetail", $sbFiColAddDescDetail->toString());

    //$fkbParamsClass->addFim(FimFiCodeTemp::classBlockExtra(),  $sbExtra->toString());

    // String
    $templateMain = $this->getTempFicClass();
    $txResult = FiTemplate::replaceParams($templateMain, $fkbParamsClass);

    return $txResult;
  }

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

  public function getTempFicClass(): string
  {
    //FimFiCodeTemp::classContent()


    //String
    $templateMain = <<<EOD
using OrakYazilimLib.Util.core;
//using OrakUtilDotNetCore.FiCollections;
//using OrakUtilDotNetCore.FiDataContainer;

public class {{classPref}}{{entityName}}
{

{{classContent}}

}
EOD;

    // template'den çıkarıldl
    //  public static void AddFieldDesc(FicList ficolList) {

    //   foreach (FiCol fiCol in ficolList)
    //   {
    //       {{addFieldDescDetail}}
    //   }

    // }

    return $templateMain;
  }

  public function genFiColMethodAssignments(Fkb $fkbItem): FiStrbui
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

    $fcTxRefField = $fkbItem->getValueByFiCol(FicFiCol::fcTxRefField());
    if ($fcTxRefField != null)
      $sbFiColMethodBody->append(sprintf("  fiCol.fcTxRefField = \"%s\";\n", $fcTxRefField));

    $fcTxIdType = $fkbItem->getValueByFiCol(FicFiCol::fcTxIdType());
    if ($fcTxIdType != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.fcTxIdType = \"%s\";\n", $fcTxIdType));
    }

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

    //        if (FiBool.isTrue(fiCol.getfcBoUnique())) {
    //          sbFiColMethodBody.append("\tfiCol.fcBoUnique = true;\n");
    //        }
    //
    //        if (FiBool.isTrue(fiCol.getfcBoUniqGro1())) {
    //          sbFiColMethodBody.append("\tfiCol.fcBoUniqGro1 = true;\n");
    //        }
    //
    //        if (FiBool.isTrue(fiCol.getfcBoUtfSupport())) {
    //          sbFiColMethodBody.append("\tfiCol.fcBoUtfSupport = true;\n");
    //        }
    //
    //        if (!FiString.isEmpty(fiCol.getfcTxDefValue())) {
    //          sbFiColMethodBody.append(String.format("\tfiCol.fcTxDefValue = \"%s\";\n", fiCol.getfcTxDefValue()));
    //        }
    //
    //        if (FiBool.isTrue(fiCol.getBoFilterLike())) {
    //          sbFiColMethodBody.append("\tfiCol.fcBoFilterLike = true;\n");
    //        }
    //
    //        // fcTxCollation	fcTxTypeName

    return $sbFiColMethodBody;
  }

  public function genColMethodBodyDetailExtra(Fkb $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    $fcTxDesc = $fkbItem->getValueByFiCol(FicFiCol::fcTxDesc());
    //if ($fcTxDesc != null)
    $sbFiColMethodBody->append(sprintf("  fiCol.fcTxDesc = \"%s\";\n", $fcTxDesc));

    return $sbFiColMethodBody;
  }

  // public function genFiMetaMethodBodyFieldDefs(Fkb $fkb): FiStrbui
  // {
  //   //StringBuilder
  //   $sbFmtMethodBodyFieldDefs = new FiStrbui();

  //   $txKey = $fkb->getValueByFiCol(FicFiMeta::ftTxKey());
  //   if ($txKey != null) {
  //     $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
  //   }

  //   $txValue = $fkb->getValueByFiCol(FicFiMeta::ftTxValue());
  //   if ($txValue != null) {
  //     $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txValue = '%s';\n", $txValue));
  //   }

  //   return $sbFmtMethodBodyFieldDefs;
  // }

  /**
   * @return string
   */
  public function getTemplateColListExtraMethod(): string
  {
    return <<<EOD
public static FicList GenTableColsExtra() {
  FicList ficList = new FicList();

  {{ficListBodyExtra}}

  return ficList;
}
EOD;
  }

  /**
   * @return string
   */
  public function getTempGenTableColsTransMethod(): string
  {
    return <<<EOD
public static FicList GenTableColsTrans() {
  FicList ficList = new FicList();
  
  {{ficListBodyTrans}}
  
  return ficList;
}
EOD;
  }

  /**
   * @return string
   */
  public function getTempGenTableColsMethods(): string
  {
    return <<<EOD
public static FicList GenTableCols() {
  FicList ficList = new FicList();

  {{ficListBody}}

  return ficList;
}
EOD;
  }

  /**
   * @param FiStrbui $sbFclListBody
   * @return void
   */
  public function processGetTableColsContent(FiStrbui $sbFclListBody, Fkb $fkbItem): void
  { 
    $fcBoTransient = FicValue::toBool($fkbItem->getValueByFiCol(FicFiCol::fcBoTransient()));

    if ($fcBoTransient === true) {
      return;
    }

    $iCogSpecs = $this->getCogSpecs();
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbFclListBody->append("ficList.Add($methodName());\n");
  }

  /**
   * @param FiStrbui $sbContent
   * @return void
   */
  public function processGetTableColsTransContent(FiStrbui $sbContent, Fkb $fkbItem): void
  {
    $fcBoTransient = FicValue::toBool($fkbItem->getValueByFiCol(FicFiCol::fcBoTransient()));

    if (!$fcBoTransient === true) {
      return;
    }

    $iCogSpecs = $this->getCogSpecs();
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbContent->append("ficList.Add($methodName());\n");
  }

  public function genFiColAddDescMethodBody(Fkb $fkbItem, ICogSpecs $iCogSpecs): FiStrbui
  {
    //StringBuilder
    $sbText = new FiStrbui(); // new StringBuilder();

    $fcTxFielDesc = $fkbItem->getValueByFiCol(FicFiCol::fcTxDesc());

    if (!FiString::isEmpty($fcTxFielDesc)) {
      $methodNameStd = $iCogSpecs->checkMethodNameStd($fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName()));

      $sbText->append(
        <<<EOD

    if(FiString.Equals(fiCol.fcTxFieldName,$methodNameStd().fcTxFieldName)){
      fiCol.fcTxFieldDesc = "$fcTxFielDesc";
    }
      
EOD
      );
    }

    return $sbText;
  }

  public function getTemplateGenFkbFields(): string
  {
    return "";
  }

  public function prepBodyGenFkbFields(FiStrbui $sbContent, Fkb $fkbItem, ICogSpecs $iCogSpecs): void
  {
    // will be implemented
  }



  // *** GetFkfAll Methods ***
  public function getTempMethodFkfAll()
  {

    $txMethodName = CgbUtilsName::getMethodNameGetFkfAll();
    $cogSpecs = new CogSpecsCsharp();
    $stdTxMethodName = $cogSpecs->checkMethodNameStd($txMethodName);

    //FimFcColClassTempAreas::getFkfAll();

    return <<<EOD
public static Fkf {$stdTxMethodName}(){

  Fkf fkf = new Fkf();
  
{{getFkfAll}}
  return fkf;
}
EOD;
  }

  public function processGetFkfAllContent(FiStrbui $sbMethodContent, Fkb  $fkbItem): void
  {
    $fcTxFieldName = trim($fkbItem->getFimValue(FimFiCol::fcTxFieldName()));
    $fcTxFieldType = $fkbItem->getFimValue(FimFiCol::fcTxFieldType());
    $iCogSpecs = new CogSpecsCsharp();
    $stMethodName = $iCogSpecs->checkMethodNameStd($fcTxFieldName);

    $sbMethodContent->append("fkf.AddFic({$stMethodName}());\n");
  }


  public function processFiColMethods(FiStrbui $sbContent, Fkb  $fkbItem): void
  {
    $iCogSpecs = new CogSpecsCsharp();
    $fcTxFieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());
    $tempFiColMethod = $this->getTemplateColMethod();
    $fcTxHeader = FiString::orEmpty($fkbItem->getValueByFiCol(FicFiCol::fcTxHeader()));
    // $fcTxFieldType = $fkbItem->getFimValue(FimFiCol::fcTxFieldType());
    // $stMethodName = $iCogSpecs->checkMethodNameStd($fcTxFieldName);

    /**
     * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
     */
    $sbFiColAssignments = $this->genFiColMethodAssignments($fkbItem); //StringBuilder

    //Fkb
    $fkbFiColMethodBody = new Fkb();
    //fkbFiColMethodBody.add("fieldMethodName", FiString.capitalizeFirstLetter(fieldName));
    $fkbFiColMethodBody->addFim(FimFiCodeTemp::fieldMethodName(), $iCogSpecs->checkMethodNameStd($fcTxFieldName));
    $fkbFiColMethodBody->addFim(FimFiCodeTemp::fieldName(), $fcTxFieldName);
    $fkbFiColMethodBody->addFim(FimFiCodeTemp::fieldHeader(), $fcTxHeader);
    $fkbFiColMethodBody->addFim(FimFiCodeTemp::colMethodBody(), $sbFiColAssignments->toString());

    /**
     * @var string $txFiColMethod
     */
    $txFiColMethod = FiTemplate::replaceParams($tempFiColMethod, $fkbFiColMethodBody);

    $sbContent->append($txFiColMethod);
    $sbContent->append("\n");
    $sbContent->append("\n");
  }

  public function getMethodFullGetfAll(FiStrbui $sbGetFkfAllContent): string
  {
    $txMethodTemplate = $this->getTempMethodFkfAll();
    $fkbParams = new Fkb();
    $fkbParams->addFim(FimFiColClassTempAreas::getFkfAll(), $sbGetFkfAllContent->toString());

    return FiTemplate::replaceParams($txMethodTemplate, $fkbParams);
  }

  // end - GetFkfAll Methods ***

  public function getCogSpecs()
  {
    return new CogSpecsJava();
  }
}
