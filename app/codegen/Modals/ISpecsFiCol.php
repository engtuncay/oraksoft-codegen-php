<?php

namespace Codegen\Modals;

/**
 * Interface for FiCol Specs (Csharp, Java ...)
 */
interface ISpecsFiCol
{

  public function getTemplateFiColClass():string;

  public function getTemplateFiColMethod():string;

  // cogspecs içinde
  //public function genFiColMethodBody(FiKeybean $fkbItem): FiStrbui;

}