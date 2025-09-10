<?php

namespace Codegen\Modals;

class SpecsCsharpFiMeta implements ISpecsFiMeta
{
  public function getTemplateFiMetaClass(): string
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

  public function getTemplateFiMetaMethod(): string
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
