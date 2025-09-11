<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiDto\FiKeybean;
use Engtuncay\Phputils8\Core\FiStrbui;

class CogSpecsCSharpFkbCol implements ICogSpecsFkbCol
{
  public function getTempGenFkbColsList(): string
  {
    return "";
  }

  public function getTempGenFkbColsTransList(): string
  {
    return "";
  }

  public function genFkbColMethodBodyDetail(FiKeybean $fkbItem): FiStrbui
  {
    return new FiStrbui();
  }

  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, string $methodName): void {}

  public function doTransientFieldOps(FiStrbui $sbFclListBodyTrans, string $methodName): void {}
  
  public function getTemplateFkbColClass(): string
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

  public function getTemplateFkbColMethod(): string
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

  
}
