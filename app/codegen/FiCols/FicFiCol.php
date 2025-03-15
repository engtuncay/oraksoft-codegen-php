<?php

namespace codegen\ficols;

// Php FiCol Class Generating

use Engtuncay\Phputils8\FiCol\IFiTableMeta;
use Engtuncay\Phputils8\Meta\FiCol;
use Engtuncay\Phputils8\Meta\FclList;

class FicFiCol implements IFiTableMeta
{

  public function getITxTableName(): string
  {
    return self::GetTxTableName();
  }

  public static function GetTxTableName(): string
  {
    return "FicFiCol";
  }

  public function genITableCols(): FclList
  {
    return self::GenTableCols();
  }

  public function genITableColsTrans(): FclList
  {
    return self::GenTableColsTrans();
  }


  public static function GenTableCols(): FclList
  {

    $fiColList = new FclList();

    $fiColList->add(self::ofcTxEntityName());
    $fiColList->add(self::ofcTxFieldType());
    $fiColList->add(self::ofcTxFieldName());
    $fiColList->add(self::ofcTxDbField());
    $fiColList->add(self::ofcTxHeader());
    $fiColList->add(self::ofcTxIdType());
    $fiColList->add(self::ofcBoTransient());
    $fiColList->add(self::ofcLnLength());
    $fiColList->add(self::ofcBoNullable());
    $fiColList->add(self::ofcLnPrecision());
    $fiColList->add(self::ofcLnScale());
    $fiColList->add(self::ofcBoUnique());
    $fiColList->add(self::ofcBoUniqGro1());
    $fiColList->add(self::ofcTxDefValue());
    $fiColList->add(self::ofcTxCollation());
    $fiColList->add(self::ofcTxTypeName());
    $fiColList->add(self::ofcBoFilterLike());
    $fiColList->add(self::ofcTxFieldDesc());
    $fiColList->add(self::ofcTxPrefix());

    return $fiColList;
  }

  public static function GenTableColsTrans(): FclList
  {

    $fiColList = new FclList();



    return $fiColList;
  }

  public static function ofcTxEntityName(): FiCol
  {
    $fiCol = new FiCol("ofcTxEntityName", "ofcTxEntityName");

    return $fiCol;
  }

  public static function ofcTxPrefix(): FiCol
  {
    $fiCol = new FiCol("ofcTxPrefix", "ofcTxPrefix");

    return $fiCol;
  }

  public static function ofcTxFieldType(): FiCol
  {
    $fiCol = new FiCol("ofcTxFieldType", "ofcTxFieldType");

    return $fiCol;
  }

  public static function ofcTxFieldName(): FiCol
  {
    $fiCol = new FiCol("ofcTxFieldName", "ofcTxFieldName");

    return $fiCol;
  }

  public static function ofcTxDbField(): FiCol
  {
    $fiCol = new FiCol("ofcTxDbField", "ofcTxDbField");

    return $fiCol;
  }


  public static function ofcTxHeader(): FiCol
  {
    $fiCol = new FiCol("ofcTxHeader", "ofcTxHeader");

    return $fiCol;
  }

  public static function ofcTxFieldDesc(): FiCol
  {
    $fiCol = new FiCol("ofcTxFieldDesc", "ofcTxFieldDesc");
    $fiCol->ofcBoTransient = true;

    return $fiCol;
  }

  public static function ofcTxIdType(): FiCol
  {
    $fiCol = new FiCol("ofcTxIdType", "ofcTxIdType");

    return $fiCol;
  }

  public static function ofcBoTransient(): FiCol
  {
    $fiCol = new FiCol("ofcBoTransient", "ofcBoTransient");

    return $fiCol;
  }

  public static function ofcLnLength(): FiCol
  {
    $fiCol = new FiCol("ofcLnLength", "ofcLnLength");

    return $fiCol;
  }

  public static function ofcBoNullable(): FiCol
  {
    $fiCol = new FiCol("ofcBoNullable", "ofcBoNullable");

    return $fiCol;
  }

  public static function ofcLnPrecision(): FiCol
  {
    $fiCol = new FiCol("ofcLnPrecision", "ofcLnPrecision");

    return $fiCol;
  }

  public static function ofcLnScale(): FiCol
  {
    $fiCol = new FiCol("ofcLnScale", "ofcLnScale");

    return $fiCol;
  }

  public static function ofcBoUnique(): FiCol
  {
    $fiCol = new FiCol("ofcBoUnique", "ofcBoUnique");

    return $fiCol;
  }

  public static function ofcBoUniqGro1(): FiCol
  {
    $fiCol = new FiCol("ofcBoUniqGro1", "ofcBoUniqGro1");

    return $fiCol;
  }

  public static function ofcTxDefValue(): FiCol
  {
    $fiCol = new FiCol("ofcTxDefValue", "ofcTxDefValue");

    return $fiCol;
  }

  public static function ofcTxCollation(): FiCol
  {
    $fiCol = new FiCol("ofcTxCollation", "ofcTxCollation");

    return $fiCol;
  }

  public static function ofcTxTypeName(): FiCol
  {
    $fiCol = new FiCol("ofcTxTypeName", "ofcTxTypeName");

    return $fiCol;
  }

  public static function ofcBoFilterLike(): FiCol
  {
    $fiCol = new FiCol("ofcBoFilterLike", "ofcBoFilterLike");

    return $fiCol;
  }


}

