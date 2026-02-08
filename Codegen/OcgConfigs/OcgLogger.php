<?php
namespace Codegen\OcgConfigs;

use Psr\Log\LogLevel;

/**
 * Logger Helper Class
 * 
 * Böylelikle log verileri merkezi bir yerden yönetilebilir.
 */
class OcgLogger
{
  // 
  public static function info(string $message): void
  {
    log_message(LogLevel::INFO, $message);
  }

  public static function debug(string $message): void
  {
    log_message(LogLevel::DEBUG, $message);
  }

  public static function error(string $message): void
  {
    log_message(LogLevel::ERROR, $message);
  }

}
