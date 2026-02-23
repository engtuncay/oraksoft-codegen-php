<?php

namespace Codegen\Modals;

/**
 * Interface-Code-Generator-Specs
 */
interface ICogSpecs
{

  // Method and Class Name Standards

  public function checkMethodNameStd(mixed $fieldName): string;

  public function checkClassNameStd(mixed $entityName): string;

  public function getTxLang(): string;

}
