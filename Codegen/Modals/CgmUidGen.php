<?php
namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiSnowFlakeId;
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

  public static function genUid(int $count): Fdr
  {
    $fdr = new Fdr();

    $sbValue = new FiStrbui();

    for ($i = 0; $i < $count; $i++) {
      $sbValue->append(FiUid::genUid())->append("\n");
    }

    $fdr->setBoResult(true);
    $fdr->setTxValue($sbValue->toString());
    $fdr->setTxMessage("$count adet UID başarıyla oluşturuldu.");

    return $fdr;
  }

   public static function genSfId(int $count): Fdr
  {
    $fdr = new Fdr();

    $sbValue = new FiStrbui();

    $snowFlake = new FiSnowFlakeId(1);

    for ($i = 0; $i < $count; $i++) {
      $sbValue->append($snowFlake->generate())->append("\n");
    }

    $fdr->setBoResult(true);
    $fdr->setTxValue($sbValue->toString());
    $fdr->setTxMessage("$count adet Snowflake id başarıyla oluşturuldu.");

    return $fdr;
  }

}