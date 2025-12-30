<?php

namespace Codegen\FiCols;

// Php FiCol Class Generating

use Engtuncay\Phputils8\FiCols\IFiTableMeta;
use Engtuncay\Phputils8\FiDtos\FiCol;
use Engtuncay\Phputils8\FiDtos\FicList;

class FicFiMeta implements IFiTableMeta {

  public function getITxTableName() : string {
    return self::GetTxTableName();
  }

  public static function  GetTxTableName() : string{
    return "FicFiMeta";
  }


  public static function GenTableCols() : FicList {
    $fclList = new FicList();
    $fclList->add(self::ofmTxKey());
    $fclList->add(self::ofmTxValue());
    $fclList->add(self::ofmLnKey());
    $fclList->add(self::ofmTxLabel());

    return $fclList;
  }

  public static function GenTableColsTrans() : FicList  {
    $fclList = new FicList();

    return $fclList;
  }

  public static function ofmTxKey () : FiCol {
    $fiCol = new FiCol("ofmTxKey", "ofmTxKey");

    return $fiCol;
  }

  public static function ofmTxValue () : FiCol {
    $fiCol = new FiCol("ofmTxValue", "ofmTxValue");

    return $fiCol;
  }

  public static function ofmLnKey () : FiCol {
    $fiCol = new FiCol("ofmLnKey", "ofmLnKey");

    return $fiCol;
  }

  public static function ofmTxLabel () : FiCol {
    $fiCol = new FiCol("ofmTxLabel", "ofmTxLabel");

    return $fiCol;
  }



  public function genITableCols() : FicList {
    return self::GenTableCols();
  }

  public function genITableColsTrans():FicList {
    return self::GenTableColsTrans();
  }

}

