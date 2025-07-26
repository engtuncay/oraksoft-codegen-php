<?php

namespace Codegen\FiMetas;

// Php FiMeta Class Generation v1

use Engtuncay\Phputils8\FiDto\FiMeta;
use Engtuncay\Phputils8\FiDto\FmtList;

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
    $fiMeta->ofmTxKey = 'txKey';
    $fiMeta->ofmTxValue = 'txKey';

    return $fiMeta;
  }

  public static function txValue(): FiMeta
  {
    $fiMeta = new FiMeta();
    $fiMeta->ofmTxKey = 'txValue';
    $fiMeta->ofmTxValue = 'txValue';

    return $fiMeta;
  }

  public static function lnKey(): FiMeta
  {
    $fiMeta = new FiMeta();
    $fiMeta->ofmTxKey = 'lnKey';
    $fiMeta->ofmTxValue = 'lnKey';

    return $fiMeta;
  }

  public static function txLabel(): FiMeta
  {
    $fiMeta = new FiMeta();
    $fiMeta->ofmTxKey = 'txLabel';
    $fiMeta->ofmTxValue = 'txLabel';

    return $fiMeta;
  }


}