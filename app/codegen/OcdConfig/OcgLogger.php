<?php
namespace Codegen\OcdConfig;
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
    log_message('info', $message);
  }

  public static function debug(string $message): void
  {
    log_message('debug', $message);
  }

  public static function error(string $message): void
  {
    log_message('error', $message);
  }

}
