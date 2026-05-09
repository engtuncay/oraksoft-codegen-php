<?php

namespace Codegen\Modals;

use Engtuncay\Phputils8\FiDtos\FkbList;

/**
 * Interface for Generate Class Code
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
