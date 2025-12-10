<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\Core\FiString;

/**
 * Typescript Templates For Code Generator
 */
class CogSpecsTypescript implements ICogSpecs
{

  /**
   * 
   * Hepsi büyük harf ise, hepsini küçültür
   * 
   * İlk harf büyük olarak döner.
   * 
   * @param mixed $txEntityName
   * @return mixed
   */
  public function checkClassNameStd(mixed $txEntityName): string
  {
    // hiç küçük harf yoksa (hepsi büyükse), tüm harfleri küçük yap
    if (!FiString::hasLowercaseLetter($txEntityName)) {
      $txEntityName = strtolower($txEntityName);
    }

    // Her zaman ilk harfi büyük yap
    return ucfirst($txEntityName);
  }


  /**
   * @param mixed $fieldName
   * @return string
   */
  public function checkMethodNameStd(mixed $fieldName): string
  {
    return CgmUtils::convertToLowerCamelCase($fieldName);
  }
}
