<?php

namespace codegen\modals;

use Engtuncay\Phputils8\Core\FiStrbui;
use Engtuncay\Phputils8\Meta\FiKeybean;
use Engtuncay\Phputils8\Meta\FkbList;

/**
 * Intrf-Code-Generator-FiCol-Specs
 */
interface ICogFicSpecs
{
  public function getTemplateFiColMethod():string;

  public function getTemplateFiColMethodExtra():string;

  public  function checkMethodNameStd(mixed $fieldName): string;

  public function getTemplateFicClass(): string;

  //public function actGenFiColClassByFkb(FkbList $fkbListExcel): string;

  public function getTempGenGiColsTransList(): string;

  public function getTempGenFiColsExtraList(): string;

  public function getTempGenFiColsList(): string;

  public function doNonTransientFieldOps(FiStrbui $sbFclListBody, string $methodName): void; //, FiStrbui $sbFclListBodyExtra

  public function doTransientFieldOps(FiStrbui $sbFclListBodyTrans, string $methodName): void;

  public function genFiColMethodBodyDetail(FiKeybean $fkbItem): FiStrbui;

  public function genFiColMethodBodyDetailExtra(FiKeybean $fkbItem): FiStrbui;

  public function genFiMetaMethodBodyFieldDefs(FiKeybean $fkb): FiStrbui;

  public static function getTemplateFiMetaClass(): string;

  public function genFiColAddDescDetail(FiKeybean $fkbItem): FiStrbui;

}