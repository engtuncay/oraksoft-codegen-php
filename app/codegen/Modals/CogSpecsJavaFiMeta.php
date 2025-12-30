<?php

namespace Codegen\Modals;

use Codegen\FiCols\FicFiMeta;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiDto\FiKeybean;

class CogSpecsJavaFiMeta implements ICogSpecsFiMeta
{

  public function getTemplateFiMetaClass(): string
  {
    //String
    $template = <<<EOD
public class {{classPref}}{{entityName}} {

{{classBody}}

}
EOD;

    return $template;
  }



  // 
  public function getTemplateFiMetaMethod(): string
  {
    //String
    $template = <<<EOD

EOD;

    return $template;
  }

  public function genFiMetaMethodBody(FiKeybean $fkb): FiStrbui
  {
    //StringBuilder
    $sbFmtMethodBodyFieldDefs = new FiStrbui();

    $txKey = $fkb->getValueByFiCol(FicFiMeta::ofmTxKey());
    if ($txKey != null) {
      $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txKey = '%s';\n", $txKey));
    }

    $txValue = $fkb->getValueByFiCol(FicFiMeta::ofmTxValue());
    if ($txValue != null) {
      $sbFmtMethodBodyFieldDefs->append(sprintf(" \$fiMeta->txValue = '%s';\n", $txValue));
    }

    return $sbFmtMethodBodyFieldDefs;
  }

  public function genFiMetaMethodBodyByFiColTemp(FiKeybean $fkb): FiStrbui
  {
    return new FiStrbui();
  }
}
