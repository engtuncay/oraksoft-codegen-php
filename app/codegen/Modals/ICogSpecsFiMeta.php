<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiDto\FiKeybean;

/**
 * Interface for FiMeta Specs (Csharp, Java ...)
 */
interface ICogSpecsFiMeta
{
  
  public function getTemplateFiMetaClass():string;

  public function getTemplateFiMetaMethod():string;

  public function genFiMetaMethodBody(FiKeybean $fkb): FiStrbui;

  public function genFiMetaMethodBodyByFiColTemp(FiKeybean $fkb): FiStrbui;

}