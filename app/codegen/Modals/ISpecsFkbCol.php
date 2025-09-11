<?php

namespace Codegen\Modals;

/**
 * Interface for FiKeybean Specs (Csharp, Java ...)
 */
interface ISpecsFkbCol
{

  public function getTemplateFkbColClass():string;

  public function getTemplateFkbColMethod():string;

  // cogspecs içinde
  //public function genFkbColMethodBody(FiKeybean $fkbItem): FiStrbui;

}