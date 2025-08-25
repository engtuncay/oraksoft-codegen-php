<?php
namespace Codegen\Modals;

class SpecsJavaFiMeta
{
  // 
  public static function getTemplateFiMetaMethod(): string
  {
    //String
    $template = <<<EOD

use Engtuncay\Phputils8\FiDto\FiMeta;
use Engtuncay\Phputils8\FiDto\FmtList;

class {{classPref}}{{entityName}} {

{{classBody}}

}
EOD;

    return $template;
  }




}
