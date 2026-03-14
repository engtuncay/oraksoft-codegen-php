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

  public function genColMethodBody(FiKeybean $fkbItem): FiStrbui;

  // TemplateColList Metodlar

  // old name : getTemplateGenTableColsMethod 
  public function getTemplateColListMethod(): string;

  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void;

  // TemplateColListTrans Metodlar
  public function getTemplateColListTransMethod(): string;
  public function doTransientFieldOps(FiStrbui $sbContent, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void;

  // PrepFkbFields ile ilgili metodlar
  public function getTemplateGenFkbFields(): string;
  public function prepBodyGenFkbFields(FiStrbui $sbContent, FiKeybean $fkbItem, ICogSpecs $iCogSpecs): void;
}
