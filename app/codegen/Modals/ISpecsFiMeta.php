<?php

namespace Codegen\Modals;

/**
 * Interface for FiMeta Specs (Csharp, Java ...)
 */
interface ISpecsFiMeta
{
  
  public function getTemplateFiMetaClass():string;

  public function getTemplateFiMetaMethod():string;

  // cogspecs içinde 
  //public function genFiMetaMethodBody(FiKeybean $fkbItem): FiStrbui;

}