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
use Engtuncay\Phputils8\FiMetas\FimQcFieldType;
use Engtuncay\Phputils8\FiMetas\FimQcSpecFields;

class CogJavaFiCol implements ICogGenClassCode
{
  public function genClassCode(FkbList $fkbList): string
  {
    $iCogSpecs = $this->getCogSpecs(); // 

    //if (FiCollection.isEmpty(fiCols)) return;
    $sbClassBody = new FiStrbui(); //new StringBuilder();
    //$sbFiColMethodsBody = new FiStrbui(); //new StringBuilder();

    //int
    //$index = 0;
    
    $sbFiColMethods = new FiStrbui();
    // content'ler
    $sbGenTableColsContent = new FiStrbui();
    $sbGenTableColsTransContent = new FiStrbui();
    $sbGetFclDtoContent = new FiStrbui();

    //$sbFiColAddDescDetail = new FiStrbui();
    $sbGetFkbFieldsAll = new FiStrbui();
    $sbGetFkfDefMethodContent = new FiStrbui();
    
    //$templateFiColMethodExtra = $iFiColClass->getTemplateFiColMethodExtra();

    /**
     * @var Fkb $fkbItem
     */
    foreach ($fkbList as $fkbItem) {

      //String
      $fcTxFieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());
      if (FiString::isEmpty($fcTxFieldName)) continue;

      $this->processGetFclDtoContent($sbGetFclDtoContent, $fkbItem);
      $this->processFiColsMethods($sbFiColMethods, $fkbItem);

      $fcBoTransient = FicValue::toBool($fkbItem->getValueByFiCol(FicFiCol::fcBoTransient()));

      if (!$fcBoTransient === true) {
        $this->processGenTableColsContent($sbGenTableColsContent, $fkbItem);
      } else {
        $this->processGenTableColsTransContent($sbGenTableColsTransContent, $fkbItem);
      }

      $this->processGetFkfDefsMethod($sbGetFkfDefMethodContent, $fkbItem);
      //$this->processGetFkfDto($sbGetFkfDto, $fkbItem);

      $stMethodName = trim($iCogSpecs->checkMethodNameStd($fcTxFieldName));
      $sbGetFkbFieldsAll->append("fkf.addFic({$stMethodName}());\n");

      //$index++;
    }

    // String
    $txGetFclDtoMethodFull = FiTemplate::replaceParams($this->getTempMethodFclDto(), Fkb::bui()->buiPut(CgmUtilsName::getMethodNameGetFclDto(), $sbGetFclDtoContent->toString()));

    // String
    $txGenTableColsMethodFull = FiTemplate::replaceParams($this->getTempMethGenTableCols(), Fkb::bui()->buiPut("ficListBody", $sbGenTableColsContent->toString()));

    // String
    $txGenTableColsMethodTransFull = FiTemplate::replaceParams($this->getTemplateColListTransMethod(), Fkb::bui()->buiPut("ficListBodyTrans", $sbGenTableColsTransContent->toString()));

    //$txResGenTableColsMethodExtra = FiTemplate::replaceParams($tempGenFiColsExt, Fkb::bui()->buiPut("ficListBodyExtra", $sbFclListBodyExtra->toString()));
    //$tempGenFiColsExt = $iCogSpecsFiCol->getTemplateFiColsExtraListMethod();

    $sbClassBody->append("\n")->append($txGenTableColsMethodFull)->append("\n");
    $sbClassBody->append("\n")->append($txGenTableColsMethodTransFull)->append("\n");
    //$sbClassBody->append("\n")->append($txResGenTableColsMethodExtra)->append("\n");
    $sbClassBody->append("\n");
    $sbClassBody->append($sbFiColMethods->toString());
    $sbClassBody->append("\n");
    $sbClassBody->append($txGetFclDtoMethodFull);

    $txTempMethodFkbAllFields = $this->getTempMethodFkfAll();
    $txGetFkfAllMethodFull = FiTemplate::replaceParams($txTempMethodFkbAllFields, Fkb::bui()->buiPut("getFkbFieldsAllContent", $sbGetFkbFieldsAll->toString()));

    $txTempGetFkbDdFieldsMethod = $this->getTempGetFkbDdFieldsMethod();
    $txGetFkfDefsMethodFull = FiTemplate::replaceParams($txTempGetFkbDdFieldsMethod,
      Fkb::bui()->buiPut("getFkbDdFields",$sbGetFkfDefMethodContent->toString())
    );

    $sbClassBody->append("\n\n");
    $sbClassBody->append($txGetFkfAllMethodFull);
    $sbClassBody->append("\n\n");
    $sbClassBody->append($txGetFkfDefsMethodFull);
    
    // Class Şablonu Uygulaması
    $classPref = "Fic";
    // URFIX entity name çekilecek
    // String
    $txEntityName = $fkbList->get(0)?->getValueByFiCol(FicFiCol::fcTxEntityName());
    $txTablePrefix = $fkbList->get(0)?->getValueByFiCol(FicFiCol::fcTxPrefix());
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.fcTxEntityName());
    
    //
    $fkbParamsMain = new Fkb();
    $fkbParamsMain->addFim(FimFiCodeTemp::classPref(), $classPref);
    $fkbParamsMain->addFim(FimFiCodeTemp::entityName(), $iCogSpecs->checkClassNameStd($txEntityName));
    $fkbParamsMain->addFim(FimFiCodeTemp::tableName(), $txEntityName);
    $fkbParamsMain->addFim(FimFiCodeTemp::tablePrefix(), $txTablePrefix);
    $fkbParamsMain->addFim(FimFiCodeTemp::classContent(), $sbClassBody->toString());
    //$fkbParamsMain->addFim(FimFiCodeTemp::classBlockExtra(), $sbClassBodyExtra->toString());
    //$fkbParamsMain->add("addFieldDescDetail", $sbFiColAddDescDetail->toString());

    //$sbExtra = $this->genClassBlockExtra($iCogSpecs, $fkbList);
    //$fkbParamsMain->addFim(FimFiCodeTemp::classBlockExtra(),  $sbExtra->toString());

    // String
    $tempFicClass = $this->getTempFicClass();
    $txResult = FiTemplate::replaceParams($tempFicClass, $fkbParamsMain);

    return $txResult;
  }

  /**
   * İlgili alanın FiCol Metod İçeriğini üreten metod
   * 
   * @param Fkb $fkbItem 
   * @return FiStrbui 
   */
  public function genColMethodContent(Fkb $fkbItem): FiStrbui
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

    $fcLnId = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnId()));
    if ($fcLnId != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.setFcLnId(%s);\n", $fcLnId));
    }

    if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoNullable()))) {
      $sbFiColMethodBody->append("  fiCol.setFcBoNullable(false);\n");
    }

    $fcTxUid = $fkbItem->getValueByFiCol(FicFiCol::fcTxUid());
    if ($fcTxUid != null) {
      $sbFiColMethodBody->append(sprintf("  fiCol.setFcTxUid(\"%s\");\n", $fcTxUid));
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
  public function getTempFicClass(): string
  {

    //$txKeyClassBloEx = FimFiCodeTemp::classBlockExtra()->getTxKey();
    //$txKeyClassContent = FimFiCodeTemp::classContent()->getTxKey();

    //String
    $templateMain = <<<EOD

import ozpasyazilim.utils.table.FiCol;
import ozpasyazilim.utils.table.FicList;
import ozpasyazilim.utils.datatypes.Fkf;
import ozpasyazilim.utils.fidborm.AbsFicTable;
      
public class {{classPref}}{{entityName}} extends AbsFicTable
{

{{classContent}}

}
EOD;

    return $templateMain;
  }

  // 
  public function getTempFiColMethod(): string
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


  public function getTempFiColExtraMethod(): string
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

  public function genColMethodBodyDetailExtra(Fkb $fkbItem): FiStrbui
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
  public function getTempMethGenTableCols(): string
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
   * @param Fkb $fkbItem
   * @param ICogSpecs $iCogSpecs
   * @return void
   */
  public function processGenTableColsContent(FiStrbui $sbFclListBody, Fkb $fkbItem): void
  { 
    $iCogSpecs = $this->getCogSpecs();
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbFclListBody->append("ficList.add($methodName());\n");
  }

  /**
   * @param FiStrbui $sbContent
   * 
   * @return void
   */
  public function processGenTableColsTransContent(FiStrbui $sbContent, Fkb $fkbItem): void
  {
    $iCogSpecs = $this->getCogSpecs();
    
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbContent->append("ficList.add($methodName());\n");
  }


  public function genFiColAddDescMethodBody(Fkb $fkbItem, ICogSpecs $iCogSpecs): FiStrbui
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
    $sbExtra = new FiStrbui();

    $sbGetFkbFieldsAll = new FiStrbui();
    $sbGetFkbDdFields = new FiStrbui();
    //$sbGetFkfDto = new FiStrbui();

    /** @var Fkb $fkbItem  */
    foreach ($fkbList as $fkbItem) {
      # code...
      $fcTxFieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());

      // Eğer fieldName boşsa bu alanı atla
      if (FiString::isEmpty($fcTxFieldName)) continue;

      $this->processGetFkfDefsMethod($sbGetFkbDdFields, $fkbItem);
      //$this->processGetFkfDto($sbGetFkfDto, $fkbItem);

      $stMethodName = trim($iCogSpecs->checkMethodNameStd($fcTxFieldName));
      $sbGetFkbFieldsAll->append("fkf.addFic({$stMethodName}());\n");
    
    } // end for

    // getDdFields

    $txTempMethodFkbAllFields = $this->getTempMethodFkfAll();
    $txFkbAllFieldsFull = FiTemplate::replaceParams($txTempMethodFkbAllFields, Fkb::bui()->buiPut("getFkbFieldsAllContent", $sbGetFkbFieldsAll->toString()));

    $txTempGetFkbDdFieldsMethod = $this->getTempGetFkbDdFieldsMethod();
    $txFkbDdFieldsFull = FiTemplate::replaceParams($txTempGetFkbDdFieldsMethod,
      Fkb::bui()->buiPut("getFkbDdFields",$sbGetFkbDdFields->toString())
    );

    // getFkfDto

    //$txTempMethodFkfDto = $this->getTempMethodFkfDto();
    //$keyMethodContent = FimFiColClassTempAreas::getFkfDto()->getTxKey();
    // $txFkfDto = FiTemplate::replaceParams(
    //   $txTempMethodFkfDto,
    //   Fkb::bui()->buiPut(
    //     $keyMethodContent,
    //     $sbGetFkfDto->toString()
    //   )
    // );

    // extra content'e ekleme

    $sbExtra->append($txFkbAllFieldsFull);
    $sbExtra->append("\n\n");
    $sbExtra->append($txFkbDdFieldsFull);
    $sbExtra->append("\n\n");
    //$sbExtra->append($txFkfDto);

    return $sbExtra;
  }

  public function getTempMethodFkfAll()
  {

    $txMethodName = CgmUtilsName::getMethodNameGetFkfAll();
    $cogSpecs = new CogSpecsJava();
    $stdTxMethodName = $cogSpecs->checkMethodNameStd($txMethodName);

    return <<<EOD
public static Fkf {$stdTxMethodName}(){

  Fkf fkf = new Fkf();
  
{{getFkbFieldsAllContent}}
  return fkf;
}
EOD;
  }

  public function getTempGetFkbDdFieldsMethod()
  {

    $txMethodName = CgmUtilsName::getMethodNameGetFkfDefs();

    $cogSpecs = new CogSpecsJava();
    $stdTxMethodName = $cogSpecs->checkMethodNameStd($txMethodName);

    $keyGetFkbDdFields = FimFiColClassTempAreas::getFkbDdFields()->getTxKey();

    return <<<EOD
public static Fkf {$stdTxMethodName}(){

  Fkf fkf = new Fkf();
  
{{{$keyGetFkbDdFields}}}
  return fkf;
}
EOD;
  }

  /**
   * getFkfDto metod şablonu
   * 
   * @return string 
   */

  /**
   * getFkfDto metod şablonu
   * 
   * @return string 
   */
  public function getTempMethodFkfDto()
  {

    $txMethodName = CgmUtilsName::getMethodNameGetFkfDto();

    $cogSpecs = new CogSpecsJava();
    $stdTxMethodName = $cogSpecs->checkMethodNameStd($txMethodName);

    $keyMethodContent = FimFiColClassTempAreas::getFkfDto()->getTxKey();

    return <<<EOD
public static Fkf {$stdTxMethodName}(){

  Fkf fkf = new Fkf();
  
{{{$keyMethodContent}}}
  return fkf;
}
EOD;
  }

  public function getTempMethodFclDto()
  {
    $txMethodName = CgmUtilsName::getMethodNameGetFclDto();

    $cogSpecs = new CogSpecsJava();
    $stdTxMethodName = $cogSpecs->checkMethodNameStd($txMethodName);

    $keyMethodContent = CgmUtilsName::getMethodNameGetFclDto(); //& "content"; //FimFiColClassTempAreas::getFkfDto()->getTxKey();

    return <<<EOD
public static FicList {$stdTxMethodName}(){

  FicList ficList = new FicList();

  // cols
{{{$keyMethodContent}}}
  return fkf;
}
EOD;
  }

  public function processFiColsMethods(FiStrbui $sbContent, Fkb  $fkbItem): void
  {
    $iCogSpecs = $this->getCogSpecs(); // 
    
    $fcTxFieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());
    $fcTxFieldName = trim($fcTxFieldName);
    
    /**
     * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
     */
    $sbFiColMethodContent = $this->genColMethodContent($fkbItem);


    $fcTxHeader = FiString::orEmpty($fkbItem->getValueByFiCol(FicFiCol::fcTxHeader()));

    //Fkb
    $fkbFicMethBody = new Fkb();
    
    //fkbFiColMethodBody.add("fieldMethodName", FiString.capitalizeFirstLetter(fieldName));
    $fkbFicMethBody->addFim(FimFiCodeTemp::fieldMethodName(), $iCogSpecs->checkMethodNameStd($fcTxFieldName));
    $fkbFicMethBody->addFim(FimFiCodeTemp::fieldName(), $fcTxFieldName);
    $fkbFicMethBody->addFim(FimFiCodeTemp::fieldHeader(), $fcTxHeader);
    $fkbFicMethBody->addFim(FimFiCodeTemp::colMethodBody(), $sbFiColMethodContent->toString());

    $tempFiColMethod = $this->getTempFiColMethod();

    /**
     * @var string $txFiColMethod
     */
    $txFiColMethod = FiTemplate::replaceParams($tempFiColMethod, $fkbFicMethBody);

    $sbContent->append($txFiColMethod)->append("\n\n");
  }

  public function processGetFclDtoContent(FiStrbui $sbContent, Fkb  $fkbItem): void
  {
    $fcTxFieldName = trim($fkbItem->getFimValue(FimFiCol::fcTxFieldName()));
    //$fcTxFieldType = $fkbItem->getFimValue(FimFiCol::fcTxFieldType());

    $tfcBoDto = $fkbItem->getValueAsBoolByFiCol(FicFiCol::tfcBoDto());
    if ($tfcBoDto) {
      $iCogSpecs = $this->getCogSpecs();
      $txStdMethodName = $iCogSpecs->checkMethodNameStd($fcTxFieldName);
      $sbContent->append("ficList.addFic({$txStdMethodName}());\n");
    }
  }

  public function processGetFkfDefsMethod(FiStrbui $sbGetFkbDdFields, Fkb  $fkbItem): void
  {
    $fcTxFieldName = trim($fkbItem->getFimValue(FimFiCol::fcTxFieldName()));
    $fcTxFieldType = $fkbItem->getFimValue(FimFiCol::fcTxFieldType());

    if (
      FiString::any(
        $fcTxFieldName,
        FimQcSpecFields::qcfTxSqTableName()->getTxKey()
      ) ||
      FiString::any(
        $fcTxFieldType,
        FimQcFieldType::sq_unique()->getTxKey()
      )
    ) {
      $iCogSpecs = new CogSpecsJava();
      $stMethodName = $iCogSpecs->checkMethodNameStd($fcTxFieldName);
      $sbGetFkbDdFields->append("fkf.addFic({$stMethodName}());\n");
    }
  }

  public function getCogSpecs()
  {
    return new CogSpecsJava();
  }
}
