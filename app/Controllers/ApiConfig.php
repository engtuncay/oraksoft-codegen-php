<?php

namespace App\Controllers;

use Codegen\FiMetas\App\FkcOcgApp;
use CodeIgniter\RESTful\ResourceController;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiDtos\Fdr;
use Engtuncay\Phputils8\FiDtos\FiKeybean;
use Engtuncay\Phputils8\FiDtos\FiResponse;

class ApiConfig extends ResourceController
{
  public function index()
  {
    return $this->respond(['message' => 'API Config is working (index)']);
  }

  public function getDbProfiles()
  {
    $txEnv = env('fapDbProfiles', '');
    $arrProfiles = FiString::toArray($txEnv, ',', true);

    $fdr = new Fdr();
    $fkb = $fdr->getFkbValueInit();

    $fkb->addFkbCol(FkcOcgApp::fapDbProfiles(), $arrProfiles);
    
    return $this->respond($fdr->genArrResponse());
  }
}
