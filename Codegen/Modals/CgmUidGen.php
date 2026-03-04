<?php
namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiCores\FiUid;
use Engtuncay\Phputils8\FiDtos\Fdr;

/**
 * Generates unique identifiers (UUID, short UUID, CUID)
 */
class CgmUidGen
{
  public static function genCuid(int $count): Fdr
  {
    $fdr = new Fdr();

    $sbValue = new FiStrbui();

    for ($i = 0; $i < $count; $i++) {
      $sbValue->append(FiUid::genCuid())->append("\n");
    }

    $fdr->setBoResult(true);
    $fdr->setTxValue($sbValue->toString());
    $fdr->setTxMessage("$count adet CUID başarıyla oluşturuldu.");

    return $fdr;
  }

}