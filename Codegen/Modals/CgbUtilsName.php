<?php

namespace Codegen\Modals;

/**
 * code generate ederken kullanılan metod isimleri 
 * 
 * farklı dillerde ortak metod ismi kullanılmalı
 * 
 * @package Codegen\Modals
 */
class CgbUtilsName
{
  // 
  public static function getMethodNameGetFkfAll()
  {
    //return "genFkbFields";
    return "getFkfAll";
  }

  //getFkbDdFields
  public static function getMethodNameGetFkfDefs()
  {
    return "getFkfDefs";
  }

  public static function getMethodNameGetFkfDto()
  {
    return "getFkfDto";
  }

  public static function getMethodNameGetFclDto()
  {
    return "getFclDto";
  }

  public static function getMethodNameGetTableColsTrans()
  {
    return "getTableColsTrans";
  }

  public static function getMethodNameGetTableCols()
  {
    return "getTableCols";
  }
}
