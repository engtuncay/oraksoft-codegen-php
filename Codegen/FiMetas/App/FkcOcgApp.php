<?php
namespace Codegen\FiMetas\App;

// FkbCol Class Generation - v0.3 

use Engtuncay\Phputils8\FiCols\IFkbTableMeta;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;
use Engtuncay\Phputils8\FiMetas\FimFiCol;

class FkcOcgApp implements IFkbTableMeta {

public function getITxTableName() : string {
  return self::GetTxTableName();
}

public static function  getTxTableName() : string{
  return "OcgApp";
}


public static function genTableCols() : FkbList {
  $fkbList = new FkbList();

  $fkbList->add(self::fapDbProfiles());


  return $fkbList;
}

public static function genTableColsTrans() : FkbList {
  $fkbList = new FkbList();

  

  return $fkbList;
}

public static function fapDbProfiles() : FiKeybean
{ 
  $fkbCol = new FiKeybean();
  $fkbCol->addFm(FimFiCol::ofcTxFieldName(), 'fapDbProfiles');
  $fkbCol->addFm(FimFiCol::ofcTxFieldType(), 'csv');

  return $fkbCol;
}



public function genITableCols() : FkbList {
  return self::genTableCols();
}

public function genITableColsTrans():FkbList {
  return self::genTableColsTrans();
}

}
