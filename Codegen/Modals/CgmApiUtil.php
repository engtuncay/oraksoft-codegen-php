<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiDtos\Fdr;
use Engtuncay\Phputils8\FiDtos\FiKeybean;

class CgmApiUtil
{
  // /**
  //  * Gelen JSON verisini diziye dönüştürür
  //  *
  //  * @param string $jsonString
  //  * @return array
  //  */
  // public static function parseJsonToArray(string $jsonString): array
  // {
  //     $dataArray = json_decode($jsonString, true);
  //     if (json_last_error() !== JSON_ERROR_NONE) {
  //         // Hata durumunda boş dizi döndürülebilir veya hata fırlatılabilir
  //         return [];
  //     }
  //     return $dataArray;
  // }

  public static function genFkbReturn(Fdr $fdr)
  {

    $fkbReturn = new FiKeybean();

    if ($fdr->getBoResult() !== null) $fkbReturn->add('boResult', $fdr->getBoResult());
    if ($fdr->getTxValue() !== null) $fkbReturn->add('txValue', $fdr->getTxValue());
    if ($fdr->getRefValue() !== null) $fkbReturn->add('refValue', $fdr->getRefValue());
    if ($fdr->getArrValue() !== null) $fkbReturn->add('arrValue', $fdr->getArrValue());
    if ($fdr->getFkbValue() !== null) $fkbReturn->add('fkbValue', $fdr->getFkbValue());
    if ($fdr->fklValue !== null) $fkbReturn->add('fklValue', $fdr->fklValue);
    if ($fdr->lnResponseCode !== null) $fkbReturn->add('lnResponseCode', $fdr->lnResponseCode);
    if ($fdr->txId !== null) $fkbReturn->add('txId', $fdr->txId);
    if ($fdr->txName !== null) $fkbReturn->add('txEntityName', $fdr->txName);
    if (!empty($fdr->logList)) $fkbReturn->add('logList', $fdr->logList);
    if ($fdr->getRowsAffected() !== null) $fkbReturn->add('rowsAffected', $fdr->getRowsAffected());
    // if($fdr->getLnTotalCount() !== null) $fkbReturn->add('lnTotalCount', $fdr->getLnTotalCount());
    if ($fdr->getException() !== null) $fkbReturn->add('exception', $fdr->getException());
    if (!empty($fdr->listException)) $fkbReturn->add('listException', $fdr->listException);

    return $fkbReturn;
  }
}
