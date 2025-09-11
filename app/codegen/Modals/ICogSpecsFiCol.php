<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\FiDto\FiKeybean;

/**
 * Interface for FiCol Specs (Csharp, Java ...)
 */
interface ICogSpecsFiCol
{

  //public function getTemplateFiColClass():string;

  public function getTemplateFicClass(): string;

  public function getTemplateFiColMethod():string;

  public function getTemplateFiColMethodExtra(): string;

  public function getTempGenFiColsTransList(): string;

  public function getTempGenFiColsExtraList(): string;

  public function getTempGenFiColsMethod(): string;

  public function genFiColMethodBodyDetailExtra(FiKeybean $fkbItem): FiStrbui;

  public function genFiColMethodBodyDetail(FiKeybean $fkbItem): FiStrbui;

  public function genFiColAddDescDetail(FiKeybean $fkbItem, ICogSpecs $iCogSpecs): FiStrbui;

  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, string $methodName): void; //, FiStrbui $sbFclListBodyExtra

  public function doTransientFieldOps(FiStrbui $sbFclListBodyTrans, string $methodName): void;

}