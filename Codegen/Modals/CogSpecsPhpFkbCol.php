<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

class CogSpecsPhpFkbCol implements ICogSpecsGenCol
{
    public function genClassCode(ICogSpecs $iCogSpecs, FkbList $fkbList): FiStrbui
    {
        return FiStrbui::empty();
    }


  public function getTemplateColMethod(): string
  {
    return <<<EOD
public static function {{fieldMethodName}}() : FiKeybean
{ 
  \$fkbCol = new FiKeybean();
{{fkbColMethodBody}}
  return \$fkbCol;
}
EOD;
  }


  public function getTemplateColClass(): string
  {
    //String
    $templateMain = <<<EOD

use Engtuncay\Phputils8\FiCols\AbsFkbTable;
use Engtuncay\Phputils8\FiCols\IFkbTableMeta;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

class {{classPref}}{{entityName}} extends AbsFkbTable implements IFkbTableMeta {

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

{{classBlockExtra}}


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
    //$txKey = $fkb->getValueByFiCol(FicFiMeta::txKey());
    //if ($txKey != null) {
    //  $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
    //}

    //    $fkbCol->addFiCol(FicFiCol::fcTxFieldName(),'evsTxMustKod'); // "fcTxFieldType", 'string'); //->fcTxFieldType = 'string';
    // $fkbCol->addFiCol(FicFiCol::fcTxFieldType(), 'string'); //->fcTxFieldType = 'string';

    $fcTxFieldName = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldName());
    if ($fcTxFieldName != null)
      $sbFiColMethodBody->append(sprintf("  \$fkbCol->addFim(FimFiCol::fcTxFieldName(), '%s');\n", $fcTxFieldName));

    $fcTxHeader = $fkbItem->getValueByFiCol(FicFiCol::fcTxHeader());
    if ($fcTxHeader != null)
      $sbFiColMethodBody->append(sprintf("  \$fkbCol->addFim(FimFiCol::fcTxHeader(), '%s');\n", $fcTxHeader));

    $fcTxFieldType = $fkbItem->getValueByFiCol(FicFiCol::fcTxFieldType());
    if ($fcTxFieldType != null)
      $sbFiColMethodBody->append(sprintf("  \$fkbCol->addFim(FimFiCol::fcTxFieldType(), '%s');\n", $fcTxFieldType));

    // $fcTxDbField = $fkbItem->getValueByFiCol(FicFiCol::fcTxDbField());
    // if ($fcTxDbField != null)
    //   $sbFiColMethodBody->append(sprintf("  fiCol.fcTxDbField = \"%s\";\n", $fcTxDbField));

    // {
    //   $fcTxRefField = $fkbItem->getValueByFiCol(FicFiCol::fcTxRefField());
    //   if ($fcTxRefField != null)
    //     $sbFiColMethodBody->append(sprintf("  \$fiCol->fcTxRefField = \"%s\";\n", $fcTxRefField));
    // }


    //$fcTxIdType = $fiCol->fcTxIdType;
    //CgmCodeGen::convertExcelIdentityTypeToFiColAttribute($fiCol->fcTxIdType);

    // if (!FiString.isEmpty(ofiTxIdType)) {
    // sbFiColMethodBody.append("\tfiCol.boKeyIdField = true;\n");
    // sbFiColMethodBody.append(String.format("\tfiCol.ofiTxIdType = FiIdGenerationType.%s.toString();\n", ofiTxIdType));
    // }

    // $fcBoTransient = $fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoTransient());
    // if ($fcBoTransient) {
    //   $sbFiColMethodBody->append("  fiCol.fcBoTransient = true;\n");
    // }

    // $fcLnLength = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnLength()));
    // if ($fcLnLength != null) {
    //   $sbFiColMethodBody->append(sprintf("  fiCol.fcLnLength = %s;\n", $fcLnLength));
    // }

    // $fcLnPrecision = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnPrecision()));
    // if ($fcLnPrecision != null) {
    //   $sbFiColMethodBody->append(sprintf("  fiCol.fcLnPrecision = %s;\n", $fcLnPrecision));
    // }

    // $fcLnScale = FicValue::toInt($fkbItem->getValueByFiCol(FicFiCol::fcLnScale()));
    // if ($fcLnScale != null) {
    //   $sbFiColMethodBody->append(sprintf("  fiCol.fcLnScale = %s;\n", $fcLnScale));
    // }

    // if (FiBool::isFalse($fkbItem->getValueAsBoolByFiCol(FicFiCol::fcBoNullable()))) {
    //   $sbFiColMethodBody->append("  fiCol.fcBoNullable = false;\n");
    // }


    //    if (FiBool::isTrue($fiCol->fcBoNullable)) {
    //      $sbFiColMethodBody->append("fiCol.fcBoNullable = true;\n");
    //    }

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
   * @return string
   */
  public function getTemplateColListTransMethod(): string
  {
    return <<<EOD
public static function genTableColsTrans() : FkbList {
  \$fkbList = new FkbList();

  {{fkbListBodyTrans}}

  return \$fkbList;
}
EOD;
  }


  /**
   * @return string
   */
  public function getTemplateColListMethod(): string
  {
    return <<<EOD
public static function genTableCols() : FkbList {
  \$fkbList = new FkbList();

  {{fkbListBody}}

  return \$fkbList;
}
EOD;
  }

  /**
   * @param FiStrbui $sbFclListBody
   * @param string $methodName
   * @param FiStrbui $sbFclListBodyExtra
   * @return void
   */
  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void //, string $methodName
  {
    $fieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbFclListBody->append("\$fkbList->add(self::$methodName());\n");
  }


  /**
   * @param FiStrbui $sbContent
   * @param string $methodName
   * @return void
   */
  public function doTransientFieldOps(FiStrbui $sbContent, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    $fieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbContent->append("\$fkbList->add(self::$methodName());\n");
  }

  public function prepBodyGenFkbFields(FiStrbui $sbContent, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    $fieldName = $fkbItem->getFimValue(FimFiCol::fcTxFieldName());
    $methodName = $iCogSpecs->checkMethodNameStd($fieldName);
    $sbContent->append("\$fkb->addFieldFkb(self::$methodName(),self::$methodName());\n");
  }


  public function genFiColAddDescDetail(FiKeybean $fkbItem, ICogSpecs $iCogSpecs): FiStrbui
  {
    //StringBuilder
    $sbText = new FiStrbui(); // new StringBuilder();

    $fcTxFielDesc = $fkbItem->getFimValue(FimFiCol::fcTxDesc());

    if (!FiString::isEmpty($fcTxFielDesc)) {
      $methodNameStd = $iCogSpecs->checkMethodNameStd($fkbItem->getFimValue(FimFiCol::fcTxFieldName()));

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
    return <<<EOD
public static function genFkbFields() : FiKeybean {
  \$fkb = new FiKeybean();

  {{genFkbFieldsBlock}}
  return \$fkb;
}
EOD;
  }

  public function genClassBlockExtra(ICogSpecs $iCogSpecs, FkbList $fkbList): FiStrbui
  {
    $sbClassBlockExtra = new FiStrbui();

    // $templateMethodFkbTable = $this->getTemplateMethodFkbTable();

    // $sbClassBlockExtra->append($templateMethodFkbTable);
    
    return $sbClassBlockExtra;
  }

  public function getTemplateMethodFkbFields()
  {

    $txMethodName = CgmUtilsName::getMethodNameGetFkbFieldsAll();

    return <<<EOD
public static function {$txMethodName}() : FiKeybean {

    \$fkb = new FiKeybean();

    {{genFkbItems}}

     return \$fkb;

  } 
EOD;
  }


}
