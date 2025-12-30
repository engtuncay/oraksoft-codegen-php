<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiDtos\FiKeybean;

/**
 * Interface for FiKeybean Specs (Csharp, Java ...)
 */
interface ICogSpecsFkbCol
{

  public function getTemplateFkbColClass(): string;

  public function getTemplateFkbColMethod(): string;

  public function getTemplateFkbColsListMethod(): string;

  public function getTemplateFkbColsListTransMethod(): string;

  public function genFkbColMethodBody(FiKeybean $fkbItem): FiStrbui;

  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void;

  public function doTransientFieldOps(FiStrbui $sbFclListBodyTrans, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void;

}
