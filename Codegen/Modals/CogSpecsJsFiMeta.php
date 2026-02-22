<?php

namespace Codegen\Modals;

use Codegen\FiCols\FicFiMeta;
use Codegen\OcgConfigs\OcgLogger;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCols\FicFiCol;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

class CogSpecsJsFiMeta implements ICogSpecsGenCol
{

  public function getTemplateColClass(): string
  {
    OcgLogger::info("CogSpecsJsFiMeta::getTemplateColClass called");
    //String
    $template = <<<EOD
import { FiMeta } from "../../../orak_modules/oraksoft-ui/oraksoft-ui.js";

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
static {{fieldMethodName}}()
{ 
  let fiMeta = FiMeta.create("{{fieldName}}");
{{fiMethodBody}}
  return fiMeta;
}
EOD;

    return $template;
  }

  public function genColMethodBody(FiKeybean $fkb): FiStrbui
  {
    // Field Definitions
    //StringBuilder
    $sbFimMethodBody = new FiStrbui();

    // ftTxKey alanı constructor'da tanımlanmış
    // $txKey = $fkb->getValueByFiCol(FicFiMeta::ftTxKey());
    // if ($txKey != null) {
    //   $sbFmtMethodBodyFieldDefs->append(sprintf(" fiMeta.ftTxKey = \"%s\";\n", $txKey));
    // }

    $txValue = $fkb->getValueByFiCol(FicFiMeta::ftTxValue());
    if ($txValue != null) {
      $sbFimMethodBody->append(sprintf(" fiMeta.ftTxValue = \"%s\";\n", $txValue));
    }

    $fcLnId = $fkb->getValueByFim(FimFiCol::fcLnId());
    OcgLogger::info("fcLnId: " . $fcLnId);
    if ($fcLnId != null) {
      $sbFimMethodBody->append(sprintf("  fiMeta.ftLnKey = %s;\n", $fcLnId));
    }

    return $sbFimMethodBody;
  }

  /**
   * FiMeta üreten metodun gövdesinin FiCol Template üzerinden dolduruldu
   * 
   * value olarak fcTxHeader kullanıldı
   *
   * @param FiKeybean $fkb alan bilgisi (row)
   * @return FiStrbui
   */
  public function genColMethodBodyByFiColTemp(FiKeybean $fkb): FiStrbui
  {
    $sb = new FiStrbui();

    $fcTxHeader = $fkb->getValueByFim(FimFiCol::fcTxHeader());
    if ($fcTxHeader != null) {
      $sb->append(sprintf("  fiMeta.ftTxValue = \"%s\";\n", $fcTxHeader));
    }

    $fcLnId = $fkb->getValueByFim(FimFiCol::fcLnId());
    OcgLogger::info("fcLnId: " . $fcLnId);
    if ($fcLnId != null) {
      $sb->append(sprintf("  fiMeta.ftLnKey = %s;\n", $fcLnId));
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
