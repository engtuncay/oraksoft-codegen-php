<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

class CogSpecsJavaFiMeta implements ICogSpecsGenCol
{
  public function genClassCode(ICogSpecs $iCogSpecs, FkbList $fkbList): FiStrbui
  {
    return FiStrbui::empty();
  }

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
  FiMeta fim = new FiMeta("{{fieldName}}");
{{fiMethodBody}}
  return fim;
}
EOD;

    return $template;
  }

  public function genColMethodBody(FiKeybean $fkb): FiStrbui
  {
    //StringBuilder
    $sbFmtMethodBodyFieldDefs = new FiStrbui();

    // $txKey = $fkb->getValueByFiCol(FicFiMeta::ftTxKey());
    // if ($txKey != null) {
    //   $sbFmtMethodBodyFieldDefs->append(sprintf(" fiMeta.setTxKey('%s');\n", $txKey));
    // }

    $txValue = $fkb->getFimValue(FimFiCol::fcTxHeader());
    if ($txValue != null) {
      $sbFmtMethodBodyFieldDefs->append(sprintf(" fim.setFtTxValue(\"%s\");\n", $txValue));
    }

    return $sbFmtMethodBodyFieldDefs;
  }

  // public function genColMethodBodyByFiColTemp(FiKeybean $fkb): FiStrbui
  // {
  // 
  //   return new FiStrbui();
  // }

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

  public function doTransientFieldOps(FiStrbui $sbContent, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    throw new \Exception('Not implemented');
  }

  public function genClassBlockExtra(ICogSpecs $iCogSpecs, FkbList $fkbList): FiStrbui
  {
    return new FiStrbui();
  }

  public function getTemplateGenFkbFields(): string
  {
    throw new \Exception('Not implemented');
  }

  public function prepBodyGenFkbFields(FiStrbui $sbContent, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void
  {
    throw new \Exception('Not implemented');
  }
}
