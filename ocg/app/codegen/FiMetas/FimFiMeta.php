<?php

namespace codegen\FiMetas;

// Php FiMeta Class Generation v1

use Engtuncay\Phputils8\Meta\FiMeta;
use Engtuncay\Phputils8\Meta\FmtList;

class FimFiMeta
{

  public static function GenFmtList(): FmtList
  {
    $fmtList = new FmtList();
    $fmtList->add(self::txKey());
    $fmtList->add(self::txValue());
    $fmtList->add(self::lnKey());
    $fmtList->add(self::txLabel());

    return $fmtList;
  }

  public static function txKey(): FiMeta
  {
    $fiMeta = new FiMeta();
    $fiMeta->txKey = 'txKey';
    $fiMeta->txValue = 'txKey';

    return $fiMeta;
  }

  public static function txValue(): FiMeta
  {
    $fiMeta = new FiMeta();
    $fiMeta->txKey = 'txValue';
    $fiMeta->txValue = 'txValue';

    return $fiMeta;
  }

  public static function lnKey(): FiMeta
  {
    $fiMeta = new FiMeta();
    $fiMeta->txKey = 'lnKey';
    $fiMeta->txValue = 'lnKey';

    return $fiMeta;
  }

  public static function txLabel(): FiMeta
  {
    $fiMeta = new FiMeta();
    $fiMeta->txKey = 'txLabel';
    $fiMeta->txValue = 'txLabel';

    return $fiMeta;
  }


}