<?php

namespace Codegen\Modals;

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

class CogSpecsCSharpFiCol implements ICogSpecsGenCol
{
  public function genClassCode(FkbList $fkbList): string
  {
    $iCogSpecs = new CogSpecsCsharp();
    
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
      $sbFiColMethodBody = $this->genColMethodBody($fkbItem); //StringBuilder

      //$sbFiColAddDescDetail->append($iCogSpecsFiCol->genColAddDescMethodBody($fkbItem,$iCogSpecs)->toString());

      //FiKeybean
      $fkbFiColMethodBody = new FiKeybean();

      //String
      $fcTxFieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());

      if(FiString::isEmpty($fcTxFieldName)) continue;

      $fcTxHeader = FiString::orEmpty($fkbItem->getValueByFiCol(FicFiCol::fcTxHeader()));

      //fkbFiColMethodBody.add("fieldMethodName", FiString.capitalizeFirstLetter(fieldName));
      $fkbFiColMethodBody->addFim(FimFiCodeTemp::fieldMethodName(), $iCogSpecs->checkMethodNameStd($fcTxFieldName));
      $fkbFiColMethodBody->addFim(FimFiCodeTemp::fieldName() , $fcTxFieldName);
      $fkbFiColMethodBody->addFim(FimFiCodeTemp::fieldHeader() , $fcTxHeader);
      $fkbFiColMethodBody->addFim(FimFiCodeTemp::colMethodBody() , $sbFiColMethodBody->toString());

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

    $fkbParamsMain->addFim( FimFiCodeTemp::classBlockExtra() ,  $sbExtra->toString());

    // String
    $templateMain = $this->getTemplateColClass();
    $txResult = FiTemplate::replaceParams($templateMain, $fkbParamsMain);

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

  public function getTemplateColClass(): string
  {
    //String
    $templateMain = <<<EOD
using OrakYazilimLib.Util.core;

public class {{classPref}}{{entityName}}
{

  public static string GetTxTableName()
  {
    return "{{tableName}}";
  }
  
  public string GetITxTableName()
  {
    return GetTxTableName();
  }

  public FicList GenITableCols()
  {
    return GenTableCols();
  }
  
  public FicList GenITableColsTrans()
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
  
  public static void AddFieldDesc(FicList ficolList) {

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

  public function genColMethodBody(FiKeybean $fkbItem): FiStrbui
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
      $sbFiColMethodBody->append(sprintf("  fiCol.fcTxDbField = \"%s\";\n", $fcTxDbField)); {
      $fcTxRefField = $fkbItem->getValueByFiCol(FicFiCol::fcTxRefField());
      if ($fcTxRefField != null)
        $sbFiColMethodBody->append(sprintf("  fiCol.fcTxRefField = \"%s\";\n", $fcTxRefField));
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

  public function genColMethodBodyDetailExtra(FiKeybean $fkbItem): FiStrbui
  {
    //StringBuilder
    $sbFiColMethodBody = new FiStrbui(); // new StringBuilder();

    $fcTxDesc = $fkbItem->getValueByFiCol(FicFiCol::fcTxDesc());
    //if ($fcTxDesc != null)
    $sbFiColMethodBody->append(sprintf("  fiCol.fcTxDesc = \"%s\";\n", $fcTxDesc));

    return $sbFiColMethodBody;
  }

  // public function genFiMetaMethodBodyFieldDefs(FiKeybean $fkb): FiStrbui
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
  public function getTemplateColListTransMethod(): string
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
  public function getTemplateColListMethod(): string
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
   * @param string $methodName
   * @param FiStrbui $sbFclListBodyExtra
   * @return void
   */
  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  { //, FiStrbui $sbFclListBodyExtra
    //$sbFclListBody->append("ficList.Add($methodName());\n");
    $fieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbFclListBody->append("ficList.Add($methodName());\n");
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
    $sbContent->append("ficList.Add($methodName());\n");
  }

  public function genFiColAddDescMethodBody(FiKeybean $fkbItem, ICogSpecs $iCogSpecs): FiStrbui
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

  public function prepBodyGenFkbFields(FiStrbui $sbContent, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    // will be implemented
  }

  public function genClassBlockExtra(ICogSpecs $iCogSpecs, FkbList $fkbList): FiStrbui
  {
    return new FiStrbui();
  }
}
