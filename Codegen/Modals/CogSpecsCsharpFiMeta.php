<?php

namespace Codegen\Modals;

use Codegen\FiCols\FicFiMeta;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiDtos\FiKeybean;

class CogSpecsCsharpFiMeta implements ICogSpecsGenCol
{

  public function getTemplateColClass(): string
  {
    //String
    $template = <<<EOD
using OrakYazilimLib.Util.core;

public class {{classPref}}{{entityName}}
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
public static FiMeta {{fieldMethodName}}()
{ 
  FiMeta fiMeta = new FiMeta("{{fieldName}}");
{{fiMethodBody}}
  return fiMeta;
}

EOD;

    return $template;
  }

  public function genColMethodBody(FiKeybean $fkb): FiStrbui
  {
    //StringBuilder
    $sbFmtMethodBodyFieldDefs = new FiStrbui();

    // constructor'da tanımlanmış
    // $txKey = $fkb->getValueByFiCol(FicFiMeta::ofmTxKey());
    // if ($txKey != null) {
    //   $sbFmtMethodBodyFieldDefs->append(sprintf(" fiMeta.txKey = \"%s\";\n", $txKey));
    // }

    $txValue = $fkb->getValueByFiCol(FicFiMeta::ofmTxValue());
    if ($txValue != null) {
      $sbFmtMethodBodyFieldDefs->append(sprintf(" fiMeta.txValue = \"%s\";\n", $txValue));
    }

    return $sbFmtMethodBodyFieldDefs;
  }

  /**
   * FiMeta üreten metodun gövdesinin FiCol Template üzerinden dolduruldu
   * 
   * value olarak ofcTxHeader kullanıldı
   *
   * @param FiKeybean $fkb alan bilgisi (row)
   * @return FiStrbui
   */
  public function genColMethodBodyByFiColTemp(FiKeybean $fkb): FiStrbui
  {
    $sb = new FiStrbui();

    $ofcTxHeader = $fkb->getValueByFiCol(FicFiCol::ofcTxHeader());
    if ($ofcTxHeader != null) {
      $sb->append(sprintf("  fiMeta.txValue = \"%s\";\n", $ofcTxHeader));
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

  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    throw new \Exception('Not implemented');
  }

  public function doTransientFieldOps(FiStrbui $sbFclListBodyTrans, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    throw new \Exception('Not implemented');
  }
  
}
