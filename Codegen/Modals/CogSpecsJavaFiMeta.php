<?php

namespace Codegen\Modals;

use Codegen\FiCols\FicFiMeta;
use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiDtos\FiKeybean;

class CogSpecsJavaFiMeta implements ICogSpecsGenCol
{


  public function getTemplateColClass(): string
  {
    //String
    $template = <<<EOD
import ozpasyazilim.utils.datatypes.FiMeta;

public class {{classPref}}{{entityName}} {

{{classBody}}

}
EOD;

    return $template;
  }



  // 
  public function getTemplateColMethod(): string
  {
    //String
    $template = <<<EOD
public static FiMeta {{fieldMethodName}}()
{
  FiMeta fimeta = new FiMeta("{{fieldName}}");
{{fiMethodBody}}
  return fimeta;
}
EOD;

    return $template;
  }

  public function genColMethodBody(FiKeybean $fkb): FiStrbui
  {
    //StringBuilder
    $sbFmtMethodBodyFieldDefs = new FiStrbui();

    $txKey = $fkb->getValueByFiCol(FicFiMeta::ofmTxKey());
    if ($txKey != null) {
      $sbFmtMethodBodyFieldDefs->append(sprintf(" fiMeta.setTxKey('%s');\n", $txKey));
    }

    $txValue = $fkb->getValueByFiCol(FicFiMeta::ofmTxValue());
    if ($txValue != null) {
      $sbFmtMethodBodyFieldDefs->append(sprintf(" fiMeta.setTxValue('%s');\n", $txValue));
    }

    return $sbFmtMethodBodyFieldDefs;
  }

  public function genColMethodBodyByFiColTemp(FiKeybean $fkb): FiStrbui
  {



    return new FiStrbui();
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
