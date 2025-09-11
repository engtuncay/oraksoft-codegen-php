<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\Core\FiString;

/**
 * Csharp Templates For Code Generator
 */
class CogSpecsCsharp implements ICogSpecs
{

  /**
   * @param mixed $txEntityName
   * @return mixed
   */
  public function checkClassNameStd(mixed $txEntityName): string
  {
    if (!FiString::hasLowercaseLetter($txEntityName)) {
      $txEntityName = strtolower($txEntityName);
    }

    return ucfirst($txEntityName);
  }


  /**
   * @param mixed $fieldName
   * @return string
   */
  public function checkMethodNameStd(mixed $fieldName): string
  {
    // Başlangıçta eğer fieldName boşsa direkt döndür
    if (FiString::isEmpty($fieldName)) return "";

    if (!FiString::hasLowercaseLetter($fieldName)) {
      $fieldName = strtolower($fieldName);
      return ucfirst($fieldName);
    } else {

      $characters = str_split($fieldName); // Dizeyi karakterlere böl
      $result = ''; // Sonuç dizesi oluştur
      $length = count($characters);

      for ($i = 0; $i < $length; $i++) {
        // İlk harf her zaman büyük kalacak
        if ($i === 0) {
          $result .= strtoupper($characters[$i]);
          $characters[$i] = strtoupper($characters[$i]);
          continue;
        }

        // Kendinden önceki küçükse, aynen ekle
        if (ctype_lower($characters[$i - 1])) { // && ctype_lower($characters[$i])
          $result .= $characters[$i];
        } // Kendinden önceki büyükse küçült
        else if (ctype_upper($characters[$i - 1])) {
          $result .= strtolower($characters[$i]);
        } else { // Kendinden önceki sayı vs ise büyült
          $result .= strtoupper($characters[$i]);
        }
      }

      return $result;
    }
  }


}
