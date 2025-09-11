<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\Core\FiString;


/**
 * Java Templates For Code Generator
 */
class CogSpecsJava implements ICogSpecs
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
    return CgmUtils::convertToLowerCamelCase($fieldName);
    
    // // Başlangıçta eğer fieldName boşsa direkt döndür
    // if (empty($fieldName)) return "";

    // if (!FiString::hasLowercaseLetter($fieldName)) {
    //   $fieldName = strtolower($fieldName);
    //   return $fieldName; // ucfirst($fieldName);
    // } else {
    //   $characters = str_split($fieldName); // Dizeyi karakterlere böl
    //   $result = ''; // Sonuç dizesi oluştur
    //   $length = count($characters);

    //   for ($i = 0; $i < $length; $i++) {
    //     // İlk harf her zaman küçük kalacak
    //     if ($i === 0) {
    //       $result .= strtolower($characters[$i]);
    //       $characters[$i] = strtolower($characters[$i]);
    //       continue;
    //     }

    //     // Kendinden önceki küçükse veya büyükse, aynen ekle
    //     if (ctype_lower($characters[$i - 1]) || ctype_upper($characters[$i - 1])) { // && ctype_lower($characters[$i])
    //       $result .= $characters[$i];
    //     } // Kendinden önceki büyükse, aynen ekle
    //     //        else if (ctype_upper($characters[$i - 1])) {
    //     //          $result .= $characters[$i];
    //     //        }
    //     else { // Kendinden önceki sayı vs ise büyült
    //       $result .= strtoupper($characters[$i]);
    //     }
    //   }

    //   return $result;
    //}
  }



  
}
