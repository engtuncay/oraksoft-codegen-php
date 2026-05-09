<?php

namespace Codegen\Modals;

use Codegen\FiCols\FicFiMeta;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiCores\FiTemplate;
use Engtuncay\Phputils8\FiDtos\Fkb;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCodeTemp;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

class CogTsFiMeta implements ICogGenClassCode
{
  public function genClassCode(FkbList $fkbList): string
  {
    $iCogSpecs = new CogSpecsTs();

        $iCogSpecs = new CogSpecsJava();

    //if (FiCollection.isEmpty(fiCols)) return;
    $sbClassBody = new FiStrbui();
    $sbFiMetaMethods = new FiStrbui();

    //int
    //$index = 0;

    //$sbFclListBody = new FiStrbui();
    //$sbFclListBodyExtra = new FiStrbui();
    //$sbFclListBodyTrans = new FiStrbui();
    //$sbFiColAddDescDetail = new FiStrbui();

    //$templateFiColMethodExtra = $iFiColClass->getTemplateFiColMethodExtra();

    /**
     * @var Fkb $fkbItem
     */
    foreach ($fkbList as $fkbItem) {

      /**
       * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
       */
      $sbColMethodBody = $this->genColMethodBody($fkbItem);

      //$sbFiColAddDescDetail->append($iCogSpecs->genFiColAddDescDetail($fkbItem)->toString());

      //Fkb
      $fkbFiMetaMethod = new Fkb();

      //String
      $fieldName = $fkbItem->getValueByFiMeta(FimFiCol::fcTxFieldName());

      if (FiString::isEmpty($fieldName)) continue;
      //$fcTxHeader = FiString::orEmpty($fkbItem->getValueByFiCol(FicFiCol::fcTxHeader()));

      $fkbFiMetaMethod->addFim( FimFiCodeTemp::fieldMethodName() , $iCogSpecs->checkMethodNameStd($fieldName));
      $fkbFiMetaMethod->addFim( FimFiCodeTemp::fieldName() , $fieldName);
      $fkbFiMetaMethod->addFim(FimFiCodeTemp::fiMethodBody(), $sbColMethodBody->toString());
      //$fkbFiColMethodBody->add("fieldHeader", $fcTxHeader);

      $txMethodCode = FiTemplate::replaceParams($this->getTemplateColMethod(), $fkbFiMetaMethod);

      $sbFiMetaMethods->append($txMethodCode)->append("\n\n");

      // $fcBoTransient = FicValue::toBool($fkbItem->getValueByFiCol(FicFiCol::fcBoTransient()));
      // $methodName = $iCogSpecs->checkMethodNameStd($fieldName);

      // if (!$fcBoTransient === true) {
      //   $iCogSpecs->doNonTransientFieldOps($sbFclListBody, $methodName);
      //   //sbFclListBody.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      // } else {
      //   $iCogSpecs->doTransientFieldOps($sbFclListBodyTrans, $methodName);
      //   //sbFclListBodyTrans.append("\tfclList.Add(").append(FiString.capitalizeFirstLetter(fieldName)).append("());\n");
      // }

      //$index++;
    }

    // String
    //$templateFiMetaMethod = $iSpecsFiMeta->getTemplateFiMetaMethod();

    // String
    //$txGenAllFiMetasMethod = FiTemplate::replaceParams($templateFiMetaMethod, Fkb::bui()->buiPut("fmtListBody", $sbFclListBody->toString()));

    //$sbClassBody->append("\n")->append($txGenAllFiMetasMethod)->append("\n");

    // String
    //$tempGenFiColsTrans = $iCogSpecs->getTempGenFmtColsTransList();

    // String
    //$txResGenTableColsMethodTrans = FiTemplate::replaceParams($tempGenFiColsTrans, Fkb::bui()->buiPut("fmtListBodyTrans", $sbFclListBodyTrans->toString()));
    //$sbClassBody->append("\n")->append($txResGenTableColsMethodTrans)->append("\n");

    //$tempGenFiColsExt = $iCogSpecs->getTempGenFiColsExtraList();

    //$txResGenTableColsMethodExtra = FiTemplate::replaceParams($tempGenFiColsExt, Fkb::bui()->buiPut("ficListBodyExtra", $sbFclListBodyExtra->toString()));
    //$sbClassBody->append("\n")->append($txResGenTableColsMethodExtra)->append("\n");

    $sbClassBody->append("\n");
    $sbClassBody->append($sbFiMetaMethods->toString());

    //
    $classPref = "Fim";

    // String
    $txEntityName = $fkbList->get(0)?->getValueByFiCol(FicFiCol::fcTxEntityName());

    $txTablePrefix = $fkbList->get(0)?->getValueByFiCol(FicFiCol::fcTxPrefix());
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.fcTxEntityName());

    $sbClassBodyExtra = new FiStrbui();
    $sbClassBodyExtra->append("// Extras");

    $fkbClassParams = new Fkb();
    $fkbClassParams->addFim(FimFiCodeTemp::classPref(), $classPref);
    $fkbClassParams->addFim(FimFiCodeTemp::entityName(), $iCogSpecs->checkClassNameStd($txEntityName));
    $fkbClassParams->addFim(FimFiCodeTemp::tableName(), $txEntityName);
    $fkbClassParams->addFim(FimFiCodeTemp::tablePrefix(), $txTablePrefix);
    $fkbClassParams->addFim(FimFiCodeTemp::classBody(), $sbClassBody->toString());
    $fkbClassParams->addFim(FimFiCodeTemp::classBlockExtra(), $sbClassBodyExtra->toString());
    //$fkbParamsMain->add("addFieldDescDetail", $sbFiColAddDescDetail->toString());

    // String
    $templateFiMetaClass = $this->getTemplateColClass();
    $txResult = FiTemplate::replaceParams($templateFiMetaClass, $fkbClassParams);

    return $txResult;
  }

  public function getTemplateGenFkbFields(): string
  {
    return "";
  }

  public function prepBodyGenFkbFields(FiStrbui $sbContent, Fkb $fkbItem, ICogSpecs $iCogSpecs): void
  {
    return;
  }

  public function getTemplateColClass(): string
  {
    //String
    $template = <<<EOD
import { FiMeta } from "orak-util-ts";

export class {{classPref}}{{entityName}}
{
{{classBody}}
}
EOD;

    return $template;
  }

  public function getTemplateColMethod(): string
  {
    //String
    $template = <<<EOD
public static {{fieldMethodName}}():FiMeta
{ 
  let fiMeta = FiMeta.create("{{fieldName}}");
{{fiMethodBody}}
  return fiMeta;
}
EOD;

    return $template;
  }

  public function genColMethodBody(Fkb $fkb): FiStrbui
  {
    //StringBuilder
    $sbFmtMethodBodyFieldDefs = new FiStrbui();

    // constructor'da tanımlanmış
    // $txKey = $fkb->getValueByFiCol(FicFiMeta::ftTxKey());
    // if ($txKey != null) {
    //   $sbFmtMethodBodyFieldDefs->append(sprintf(" fiMeta.txKey = \"%s\";\n", $txKey));
    // }

    $txValue = $fkb->getValueByFiCol(FicFiMeta::ftTxValue());
    if ($txValue != null) {
      $sbFmtMethodBodyFieldDefs->append(sprintf(" fiMeta.ftTxValue = \"%s\";\n", $txValue));
    }

    return $sbFmtMethodBodyFieldDefs;
  }

  /**
   * FiMeta üreten metodun gövdesinin FiCol Template üzerinden dolduruldu
   * 
   * value olarak fcTxHeader kullanıldı
   *
   * @param Fkb $fkb alan bilgisi (row)
   * @return FiStrbui
   */
  public function genColMethodBodyByFiColTemp(Fkb $fkb): FiStrbui
  {
    $sb = new FiStrbui();

    $fcTxHeader = $fkb->getValueByFiCol(FicFiCol::fcTxHeader());
    if ($fcTxHeader != null) {
      $sb->append(sprintf("  fiMeta.ftTxValue = \"%s\";\n", $fcTxHeader));
    }

    return $sb;
  }

  public function getTemplateColListMethod(): string
  {
    throw new \Exception('Not implemented');
  }

  public function getTemplateColListTransMethod(): string
  {
    throw new \Exception('Not implemented');
  }

  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, Fkb $fkbItem, ICogSpecs $iCogSpecs): void
  {
    throw new \Exception('Not implemented');
  }

  public function doTransientFieldOps(FiStrbui $sbContent, Fkb $fkbItem, ICogSpecs $iCogSpecs): void
  {
    throw new \Exception('Not implemented');
  }

  public function genClassBlockExtra(ICogSpecs $iCogSpecs, FkbList $fkbList): FiStrbui
  {
    return new FiStrbui();
  }
}
