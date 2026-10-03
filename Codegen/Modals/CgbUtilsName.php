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
  public static function getMetNameGetFkfAll()
  {
    //eski kullanım
    //return "genFkbFields";
    return "getFkfAll";
  }

  //getFkbDdFields
  public static function getMetNameGetFkfDefs()
  {
    return "getFkfDefs";
  }

  public static function getMetNameGetFclDto()
  {
    return "getFclDto";
  }

  public static function getMetNameGetTableColsTrans()
  {
    return "getTableColsTrans";
  }

  public static function getMetNameGetTableCols()
  {
    return "getTableCols";
  }

  /**
   * kullanımdan kaldırıldı GetFclDto aynı işlevde kullanılabilir
   * 
   * @return string 
   */
  public static function getMetNameGetFkfDto()
  {
    return "getFkfDto";
  }
}
