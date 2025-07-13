<?php

namespace codegen\FiCols;

// Php FiCol Class Generating

use Engtuncay\Phputils8\FiCol\IFiTableMeta;
use Engtuncay\Phputils8\Meta\FiCol;
use Engtuncay\Phputils8\Meta\FicList;

class FicFiMeta implements IFiTableMeta {

  public function getITxTableName() : string {
    return self::GetTxTableName();
  }

  public static function  GetTxTableName() : string{
    return "FicFiMeta";
  }


  public static function GenTableCols() : FicList {
    $fclList = new FicList();
    $fclList->add(self::txKey());
    $fclList->add(self::txValue());
    $fclList->add(self::lnKey());
    $fclList->add(self::txLabel());

    return $fclList;
  }

  public static function GenTableColsTrans() : FicList  {
    $fclList = new FicList();

    return $fclList;
  }

  public static function txKey () : FiCol {
    $fiCol = new FiCol("txKey", "txKey");

    return $fiCol;
  }

  public static function txValue () : FiCol {
    $fiCol = new FiCol("txValue", "txValue");

    return $fiCol;
  }

  public static function lnKey () : FiCol {
    $fiCol = new FiCol("lnKey", "lnKey");

    return $fiCol;
  }

  public static function txLabel () : FiCol {
    $fiCol = new FiCol("txLabel", "txLabel");

    return $fiCol;
  }



  public function genITableCols() : FicList {
    return self::GenTableCols();
  }

  public function genITableColsTrans():FicList {
    return self::GenTableColsTrans();
  }

}

