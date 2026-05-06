<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiCores\FiStrbui;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FkbList;

/**
 * Interface for FiKeybean Specs (Csharp, Java ...)
 */
interface ICogGenClassCode
{

  /**
   * class kodunu üreten metod 
   * 
   * @param FkbList $fkbList 
   * @return string 
   */
  public function genClassCode(FkbList $fkbList): string;
    
}
