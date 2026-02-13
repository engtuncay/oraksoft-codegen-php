<?php

namespace App\Controllers;

use Codegen\FiMetas\App\FkcOcgApp;
use Engtuncay\Phputils8\FiCores\FiCollection;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiDtos\FiKeybean;

class Home extends BaseController
{
  public function index(): string
  {
    $fapProfiles = env(FkcOcgApp::fapDbProfiles()->getFcTxFn(),'');

    $arr = FiString::toArray($fapProfiles,',', true);

    $fkbData = new FiKeybean();
    $fkbData->addFieldFkb(FkcOcgApp::fapDbProfiles(), $arr);
    
    return view('viewHome',['data' => $fkbData->ToArr()]);
  }

}
