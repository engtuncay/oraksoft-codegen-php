<?php

namespace Codegen\FiCols;

// Php FiCol Class Generating

use Engtuncay\Phputils8\FiCols\IFiTableMeta;
use Engtuncay\Phputils8\FiDtos\FiCol;
use Engtuncay\Phputils8\FiDtos\FicList;

class FicFiMeta implements IFiTableMeta
{

  public function getITxTableName(): string
  {
    return self::GetTxTableName();
  }

  public static function  GetTxTableName(): string
  {
    return "FicFiMeta";
  }


  public static function GenTableCols(): FicList
  {
    $fclList = new FicList();
    $fclList->add(self::ftTxKey());
    $fclList->add(self::ftTxValue());
    $fclList->add(self::ftLnKey());
    $fclList->add(self::ftTxLabel());

    return $fclList;
  }

  public static function GenTableColsTrans(): FicList
  {
    $fclList = new FicList();

    return $fclList;
  }

  public static function ftTxKey(): FiCol
  {
    $fiCol = new FiCol("ftTxKey", "ftTxKey");

    return $fiCol;
  }

  public static function ftTxValue(): FiCol
  {
    $fiCol = new FiCol("ftTxValue", "ftTxValue");

    return $fiCol;
  }

  public static function ftLnKey(): FiCol
  {
    $fiCol = new FiCol("ftLnKey", "ftLnKey");

    return $fiCol;
  }

  public static function ftTxLabel(): FiCol
  {
    $fiCol = new FiCol("ftTxLabel", "ftTxLabel");

    return $fiCol;
  }



  public function genITableCols(): FicList
  {
    return self::GenTableCols();
  }

  public function genITableColsTrans(): FicList
  {
    return self::GenTableColsTrans();
  }
}
