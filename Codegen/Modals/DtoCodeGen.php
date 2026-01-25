<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiStrbui;

/**
 * Her bir Entity için Data Sınıfı
 */
class DtoCodeGen
{
  public FiStrbui $sbCodeGen;

  public string $dcgId;

  public function getSbCodeGen(): FiStrbui
  {
    return $this->sbCodeGen;
  }

  public function setSbCodeGen(FiStrbui $sbCodeGen): void
  {
    $this->sbCodeGen = $sbCodeGen;
  }

  public function getDcgId(): string
  {
    return $this->dcgId;
  }

  public function setDcgId(string $dcgId): void
  {
    $this->dcgId = $dcgId;
  }


}