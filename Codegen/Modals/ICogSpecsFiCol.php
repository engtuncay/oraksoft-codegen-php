<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiDtos\FiKeybean;

/**
 * Interface for FiCol Specs (Csharp, Java ...)
 */
interface ICogSpecsFiCol
{

  //public function getTemplateFiColClass():string;

  public function getTemplateFicClass(): string;

  public function getTemplateFiColMethod():string;

  public function getTemplateFiColMethodExtra(): string;

  public function getTemplateFiColsTransListMethod(): string;

  public function getTemplateFiColsExtraListMethod(): string;

  public function getTemplateFiColsListMethod(): string;

  public function genFiColMethodBodyDetailExtra(FiKeybean $fkbItem): FiStrbui;

  public function genFiColMethodBody(FiKeybean $fkbItem): FiStrbui;

  public function genFiColAddDescMethodBody(FiKeybean $fkbItem, ICogSpecs $iCogSpecs): FiStrbui;

  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, string $methodName): void; //, FiStrbui $sbFclListBodyExtra

  public function doTransientFieldOps(FiStrbui $sbFclListBodyTrans, string $methodName): void;

}