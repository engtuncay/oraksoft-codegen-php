<?php

namespace Codegen\OcgConfigs;

use Engtuncay\Phputils8\FiConfigs\IFiConfigManager;
use Engtuncay\Phputils8\FiDbs\FiConnConfig;
use Engtuncay\Phputils8\FiDbs\FiDbTypes;

class OcgConfig implements IFiConfigManager
{
  public function getFiConnConfig(?string $profile = null): FiConnConfig
  {
    return $this->getConnConfig($profile);
  }

  public function getProfile(): string
  {
    return "default";
  }

  public function getConnConfig(?string $profile = "default"): FiConnConfig
  {
    $fiConnConfig  = new FiConnConfig;

    // getenv ile de alabilirsiniz
    $host = env("database.$profile.hostname");
    $username = env("database.$profile.username") ?: 'username';
    $password = env("database.$profile.password") ?: 'pass';
    $dbName = env("database.$profile.database") ?: 'dbname';
    $dbType = env("database.$profile.dbType") ?: FiDbTypes::MYSQL;

    $fiConnConfig->setTxServer($host);
    $fiConnConfig->setTxDatabase($dbName);
    $fiConnConfig->setTxUsername($username);
    $fiConnConfig->setTxPass($password);
    $fiConnConfig->setTxDbType($dbType);

    return $fiConnConfig;
  }

  public function getConnString(?string $profile = "default"): string
  {
    return "";
  }

  public function getApiUrl(?string $txProfile = ""): string
  {
    return "";
  }

}
