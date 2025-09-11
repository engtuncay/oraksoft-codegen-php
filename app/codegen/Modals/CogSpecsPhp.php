<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\Core\FiString;

/**
 * Csharp Templates For Code Generator
 */
class CogSpecsPhp implements ICogSpecs
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
    return self::convertFieldNameToLowerCamelCase($fieldName);
  }

  /**
   * Converts a field name to standard method name format.
   *
   * @param string|null $fieldName
   * @return string
   */
  public static function convertFieldNameToLowerCamelCase(string $fieldName): string
  {
    // Başlangıçta eğer fieldName boşsa direkt döndür
    if (FiString::isEmpty($fieldName)) return "";

    if (!FiString::hasLowercaseLetter($fieldName)) {
      $fieldName = strtolower($fieldName);
      return lcfirst($fieldName);
    } else {

      $characters = str_split($fieldName); // Dizeyi karakterlere böl
      $result = ''; // Sonuç dizesi oluştur
      $length = count($characters);

      for ($i = 0; $i < $length; $i++) {
        // İlk harf her zaman küçük kalacak
        if ($i === 0) {
          $result .= strtolower($characters[$i]);
          $characters[$i] = strtolower($characters[$i]);
          continue;
        }

        // Kendinden önceki küçükse, aynen ekle
        if (ctype_lower($characters[$i - 1])) { // && ctype_lower($characters[$i])
          $result .= $characters[$i];
        } // Kendinden önceki büyükse küçült
        else if (ctype_upper($characters[$i - 1])) {
          $result .= strtolower($characters[$i]);
        } else if ($characters[$i - 1] == '_') {
          $result .= strtolower($characters[$i]);
        } else {  // Kendinden önceki sayı vs (_ dışında karakterse) ise büyült
          $result .= strtoupper($characters[$i]);
        }
      }

      return $result;
    }
  }
  
}
