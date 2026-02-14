<?php

namespace Codegen\OcgConfigs;

use Engtuncay\Phputils8\FiLogs\IFiLogManager;

class FiLibLogger implements IFiLogManager
{
  public function debug(string $message): void
  {
    OcgLogger::debug($message);
  }

  public function error(string $message): void
  {
    OcgLogger::error($message);
  }
}
