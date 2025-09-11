<?php

namespace Codegen\Modals;

class SpecsCSharpFiCol implements ISpecsFiCol
{
  
  public function getTemplateFiColClass(): string
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

  public function getTemplateFiColMethod(): string
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
