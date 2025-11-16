<?php
namespace App\Codegen\OcdConfig;

use Engtuncay\Phputils8\FiConfig\IFiConfigManager;
use Engtuncay\Phputils8\FiDb\FiConnConfig;

class OcdConfig implements IFiConfigManager
{
  public function getConnString(?string $profile): string
  {
    return "";
  }

  public function getApiUrl(?string $txProfile): string
  {
    return "";
  }

  public function getFiConnConfig(?string $profile = null): FiConnConfig
  {
    return $this->getConnConfig($profile);
  }

  public function getProfile(): string
  {
    return "default";
  }

  public function getConnConfig(?string $profile): FiConnConfig
  {
    $fiConnConfig  = new FiConnConfig;

    // getenv ile de alabilirsiniz
    $host = env('database.default.hostname');
    $username = env('database.default.username') ?: 'kullanici_adi';
    $password = env('database.default.password') ?: 'sifre';
    $dbName = env('database.default.database') ?: 'veritabani_adi';

    $fiConnConfig->setTxServer($host);
    $fiConnConfig->setTxDatabase($dbName);
    $fiConnConfig->setTxUsername($username);
    $fiConnConfig->setTxPass($password);

    return $fiConnConfig;
  }
}
