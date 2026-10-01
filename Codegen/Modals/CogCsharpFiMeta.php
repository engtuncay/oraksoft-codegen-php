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
use Engtuncay\Phputils8\FiMetas\FimFtFieldType;

class CogCsharpFiMeta implements ICogGenClassCode
{
  public function genClassCode(FkbList $fkbList): string
  {
    $iCogSpecs = new CogSpecsCsharp();

    //if (FiCollection.isEmpty(fiCols)) return;
    $sbClassContent = new FiStrbui();
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

      //$sbFiColAddDescDetail->append($iCogSpecs->genFiColAddDescDetail($fkbItem)->toString());

      /** @var Fkb|null $fkbClassComm */
      $fkbClassComm = null;

      //Fkb
      $fkbFimMetParams = new Fkb();

      //String
      $fcTxFieldName = $fkbItem->getValueByFiMeta(FimFiCol::fcTxFieldName());

      if (FiString::isEmpty($fcTxFieldName)) continue;

      if (FiString::startWith($fcTxFieldName, "tfc")) {
        if ($fcTxFieldName == FimFtFieldType::tfcTxCodeComm()->ftTxValue) {
          $fkbClassComm = $fkbItem;
        }
        continue;
      }

      /**
       * Alanların FiCol Metod İçeriği (özellikleri tanımlanır)
       */
      $sbFiMetaMetContent = $this->processFiMetaContent($fkbItem);

      //$fcTxHeader = FiString::orEmpty($fkbItem->getValueByFiCol(FicFiCol::fcTxHeader()));

      $fkbFimMetParams->addFim(FimFiCodeTemp::fieldMethodName(), $iCogSpecs->checkMethodNameStd($fcTxFieldName));
      $fkbFimMetParams->addFim(FimFiCodeTemp::fieldName(), $fcTxFieldName);
      $fkbFimMetParams->addFim(FimFiCodeTemp::fiMethodBody(), $sbFiMetaMetContent->toString());
      //$fkbFiColMethodBody->add("fieldHeader", $fcTxHeader);

      $txMethodCodeFull = FiTemplate::replaceParams($this->getTempFiMetaMethod(), $fkbFimMetParams);
      $sbFiMetaMethods->append($txMethodCodeFull)->append("\n\n");

      //$index++;
    }

    $sbClassContent->append("\n");
    $sbClassContent->append($sbFiMetaMethods->toString());

    //
    $txClassPref = "Fim";

    // String
    $txEntityName = $fkbList->get(0)?->getValueByFiCol(FicFiCol::fcTxEntityName());

    $txTablePrefix = $fkbList->get(0)?->getValueByFiCol(FicFiCol::fcTxPrefix());
    //fikeysExcelFiCols.get(0).getTosOrEmpty(FiColsMetaTable.fcTxEntityName());

    $sbClassBodyExtra = new FiStrbui();
    $sbClassBodyExtra->append("// Extras");

    $fkbClassParams = new Fkb();
    $fkbClassParams->addFim(FimFiCodeTemp::classPref(), $txClassPref);
    $fkbClassParams->addFim(FimFiCodeTemp::entityName(), $iCogSpecs->checkClassNameStd($txEntityName));
    $fkbClassParams->addFim(FimFiCodeTemp::tableName(), $txEntityName);
    $fkbClassParams->addFim(FimFiCodeTemp::tablePrefix(), $txTablePrefix);
    $fkbClassParams->addFim(FimFiCodeTemp::classBody(), $sbClassContent->toString());
    $fkbClassParams->addFim(FimFiCodeTemp::classBlockExtra(), $sbClassBodyExtra->toString());
    //$fkbParamsMain->add("addFieldDescDetail", $sbFiColAddDescDetail->toString());

    // String
    $txResult = FiTemplate::replaceParams($this->getTempClass(), $fkbClassParams);

    return $txResult;
  }

  public function getTempClass(): string
  {
    //String
    $template = <<<EOD
//using OrakYazilimLib.Util.core;
using OrakUtilDotNetCore.FiDataContainer;

public class {{classPref}}{{entityName}}
{
{{classBody}}
}
EOD;

    return $template;
  }

  public function getTempFiMetaMethod(): string
  {
    //String
    $template = <<<EOD
public static FiMeta {{fieldMethodName}}()
{ 
  FiMeta fiMeta = new FiMeta("{{fieldName}}");
{{fiMethodBody}}
  return fiMeta;
}

EOD;

    return $template;
  }

  public function processFiMetaContent(Fkb $fkb): FiStrbui
  {
    //StringBuilder
    $sbFimContent = new FiStrbui();

    // constructor'da tanımlanmış
    // $txKey = $fkb->getValueByFiCol(FicFiMeta::ftTxKey());
    // if ($txKey != null) {
    //   $sbFmtMethodBodyFieldDefs->append(sprintf(" fiMeta.txKey = \"%s\";\n", $txKey));
    // }

    $txValue = $fkb->getValueByFiCol(FicFiMeta::ftTxValue());
    if ($txValue != null) {
      $sbFimContent->append(sprintf(" fiMeta.txValue = \"%s\";\n", $txValue));
    }

    return $sbFimContent;
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
      $sb->append(sprintf("  fiMeta.txValue = \"%s\";\n", $fcTxHeader));
    }

    return $sb;
  }
}
