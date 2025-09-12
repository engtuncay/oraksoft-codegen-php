<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\FiDto\FiKeybean;

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

  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, string $methodName): void; //, FiStrbui $sbFclListBodyExtra

  public function doTransientFieldOps(FiStrbui $sbFclListBodyTrans, string $methodName): void;

}
