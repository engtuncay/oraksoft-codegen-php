<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiDtos\FiKeybean;

/**
 * Interface for FiKeybean Specs (Csharp, Java ...)
 */
interface ICogSpecsGenCol
{

  public function getTemplateColClass(): string;

  public function getTemplateColMethod(): string;

  // old name : getTemplateGenTableColsMethod 
  public function getTemplateColListMethod(): string;

  public function getTemplateColListTransMethod(): string;
  public function genColMethodBody(FiKeybean $fkbItem): FiStrbui;

  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void;

  public function doTransientFieldOps(FiStrbui $sbFclListBodyTrans, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void;

}
